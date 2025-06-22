<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for user management
        $userPermissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'export users',
            'bulk actions users',
            'change password users',
        ];

        // Create permissions for role management
        $rolePermissions = [
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign permissions',
            'clone roles',
        ];

        // Create permissions for system management
        $systemPermissions = [
            'view system settings',
            'edit system settings',
            'view audit logs',
            'export audit logs',
            'view statistics',
            'manage backups',
        ];

        // Create permissions for facility management
        $facilityPermissions = [
            'view facilities',
            'create facilities',
            'edit facilities',
            'delete facilities',
            'assign users to facilities',
        ];

        // Create permissions for referral management
        $referralPermissions = [
            'view referrals',
            'create referrals',
            'edit referrals',
            'delete referrals',
            'approve referrals',
            'reject referrals',
            'export referrals',
        ];

        // Create permissions for patient management
        $patientPermissions = [
            'view patients',
            'create patients',
            'edit patients',
            'delete patients',
            'export patients',
        ];

        // Create permissions for reports
        $reportPermissions = [
            'view reports',
            'generate reports',
            'export reports',
            'schedule reports',
        ];

        // Create permissions for verification
        $verificationPermissions = [
            'verify patient',
            'verify referral',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $systemPermissions,
            $facilityPermissions,
            $referralPermissions,
            $patientPermissions,
            $reportPermissions,
            $verificationPermissions
        );

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $this->createRolesWithPermissions();
    }

    /**
     * Create roles and assign permissions
     */
    private function createRolesWithPermissions()
    {
        // Super Admin Role
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin Role
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'view users', 'create users', 'edit users', 'delete users', 'export users', 'bulk actions users',
            'view roles', 'create roles', 'edit roles', 'assign permissions',
            'view system settings', 'view audit logs', 'view statistics',
            'view facilities', 'create facilities', 'edit facilities', 'assign users to facilities',
            'view referrals', 'create referrals', 'edit referrals', 'delete referrals', 'approve referrals', 'reject referrals', 'export referrals',
            'view patients', 'create patients', 'edit patients', 'delete patients', 'export patients',
            'view reports', 'generate reports', 'export reports',
        ]);

        // Doctor Role
        $doctor = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctor->givePermissionTo([
            'view users',
            'view facilities',
            'view referrals', 'create referrals', 'edit referrals', 'approve referrals', 'reject referrals',
            'view patients', 'create patients', 'edit patients',
            'view reports', 'generate reports',
        ]);

        // Nurse Role
        $nurse = Role::firstOrCreate(['name' => 'nurse', 'guard_name' => 'web']);
        $nurse->givePermissionTo([
            'view users',
            'view facilities',
            'view referrals', 'create referrals', 'edit referrals',
            'view patients', 'create patients', 'edit patients',
            'view reports',
        ]);

        // Receptionist Role
        $receptionist = Role::firstOrCreate(['name' => 'receptionist', 'guard_name' => 'web']);
        $receptionist->givePermissionTo([
            'view users',
            'view facilities',
            'view referrals', 'create referrals',
            'view patients', 'create patients', 'edit patients',
        ]);

        // Manager Role
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->givePermissionTo([
            'view users', 'create users', 'edit users', 'export users',
            'view facilities', 'edit facilities',
            'view referrals', 'create referrals', 'edit referrals', 'approve referrals', 'reject referrals', 'export referrals',
            'view patients', 'create patients', 'edit patients', 'export patients',
            'view reports', 'generate reports', 'export reports',
        ]);

        // Referral Coordinator Role
        $referralCoordinator = Role::firstOrCreate(['name' => 'referral_coordinator', 'guard_name' => 'web']);
        $referralCoordinator->givePermissionTo([
            'view users',
            'view facilities',
            'view referrals', 'create referrals', 'edit referrals', 'approve referrals', 'reject referrals', 'export referrals',
            'view patients', 'create patients', 'edit patients',
            'view reports', 'generate reports',
        ]);
    }
} 