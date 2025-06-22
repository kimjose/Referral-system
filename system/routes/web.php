<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExtraFormsController;
use App\Http\Controllers\MflController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TriageController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Phq9Controller;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\GroupController;

Route::get('/', [UserController::class, 'signIn'])->name('user.signIn');
Route::post('/user-login', [UserController::class, 'login'])->name('user.login');
Route::get('/facility/set', [UserController::class, 'getFacilities'])->name('facility.set');
Route::post('/facility/select', [UserController::class, 'select'])->name('facility.select');
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
Route::get('/admin', [UserController::class, 'admin'])->name('admin.dashboard');
Route::get('/doctor', [UserController::class, 'doctor'])->name('doctor.dashboard');
Route::get('/fhirJson', [ReferralController::class, 'fhirJson']);

Route::get('/patients-count', [UserController::class, 'getPatientsCount'])->name('patients.count');
Route::get('/physicians-count', [UserController::class, 'getPhysiciansCount'])->name('physicians.count');
Route::get('/referrals-count', [UserController::class, 'getReferralsCount'])->name('referrals.count');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');

    // User Management Routes
    Route::prefix('user-management')->name('user-management.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/change-password', [UserManagementController::class, 'changePassword'])->name('change-password');
        Route::post('/bulk-action', [UserManagementController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/statistics', [UserManagementController::class, 'statistics'])->name('statistics');
        Route::get('/export', [UserManagementController::class, 'export'])->name('export');
    });

    // Role Management Routes
    Route::prefix('role-management')->name('role-management.')->group(function () {
        Route::get('/', [RoleManagementController::class, 'index'])->name('index');
        Route::get('/create', [RoleManagementController::class, 'create'])->name('create');
        Route::post('/', [RoleManagementController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleManagementController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleManagementController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleManagementController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{role}/assign-permissions', [RoleManagementController::class, 'assignPermissions'])->name('assign-permissions');
        Route::post('/{role}/clone', [RoleManagementController::class, 'clone'])->name('clone');
        Route::get('/statistics', [RoleManagementController::class, 'statistics'])->name('statistics');
    });

    Route::get('/facilities', [ReferralController::class, 'facilities'])->name('referral.facilities');
    Route::get('/medicalTerms', [ReferralController::class, 'medicalTerms'])->name('referral.medicalTerms');


    //patient routes
    Route::get('/getSubcounties/{county_id}', [PatientController::class, 'getSubcounties'])->name('getSubcounties');
    Route::get('/getWards/{contituency_name}', [PatientController::class, 'getWards'])->name('getWards');


    Route::get('/new-patient', [PatientController::class, 'addPatient'])->name('patients.addPatient');
    Route::post('/store-patient', [PatientController::class, 'addData'])->name('patients.storeData');
    Route::get('/search-patient', [PatientController::class, 'searchPatient'])->name('patients.searchPatients');
    Route::get('/search-patients', [PatientController::class, 'search'])->name('patients.search-patient');
    // Route::get('/search-patient/results/', [PatientController::class, 'searchResults'])->name('patients.searchResults');
    Route::get('/patients/view/{patient}', [PatientController::class, 'viewPatient'])->name('patients.viewPatient');
    Route::get('/patients-count', [PatientController::class, 'getPatientsCount']);



    //referral routes
    Route::get('/worklist', [ReferralController::class, 'worklist'])->name('referrals.worklist');
    Route::post('/submit-referral', [ReferralController::class, 'submitReferral'])->name('referrals.submitReferral');
    //Route::get('/referral/{patient}', [ReferralController::class, 'create'])->name('referrals.create');
    Route::get('/referral/create/{patient}', [ReferralController::class, 'createreferal'])->name('referrals.createReferral');
    Route::get('/incoming-referrals/reviewed-requests', [ReferralController::class, 'reviewed'])->name('referrals.incoming.reviewed');

    Route::get('/incoming-referrals/counter-referrals', [ReferralController::class, 'counterReferral'])->name('referrals.incoming.counterreferral');
    Route::get('/referral/view/{referral}', [ReferralController::class, 'viewReferal'])->name('referrals.viewReferral');
    Route::get('/referral/view-incoming/{referral}', [ReferralController::class, 'viewIncomingReferal'])->name('referrals.viewIncomingReferral');

    Route::get('/referral-success}', [ReferralController::class, 'submitReferral'])->name('referrals.success');
    Route::delete('/referral/{id}', [ReferralController::class, 'destroy'])->name('referral.destroy');

    //Report routes
    Route::get('/reports/incoming-referrals-reports', [ReportController::class, 'incomingReports'])->name('reports.incoming-reports');
    Route::get('/reports/outgoing-referrals-reports', [ReportController::class, 'outgoingReports'])->name('reports.outgoing-reports');
    Route::get('/reports/completed-referrals-reports', [ReportController::class, 'completedReports'])->name('reports.completed-reports');

    //incoming referral
    Route::get('/incoming-referrals', [ReferralController::class, 'incomingReferrals'])->name('referrals.incoming');
    Route::get('/add-referral', [ReferralController::class, 'addReferral'])->name('referrals.addReferral');
    Route::get('/outgoing-referrals', [ReferralController::class, 'outgoing'])->name('referrals.outgoing');

    Route::post('/storeReferral', [ReferralController::class, 'storeReferral'])->name('referral.storeReferral');

    //outgoing referral
    Route::get('/outgoing', [ReferralController::class, 'outgoing'])->name('referral.outgoing');
    Route::get('/accept-referral-request/{referral}', [ReferralController::class, 'acceptReferralRequest'])->name('referral.accept');
    Route::get('/reject-referral-request/{referral}', [ReferralController::class, 'rejectReferralRequest'])->name('referral.reject');


    //triage routes
    Route::get('/triages', [TriageController::class, 'addTriage'])->name('triages.addTriage');
    Route::post('/store-form', [TriageController::class, 'store'])->name('triages.store-form');

    // PHQ9 Assessment
    Route::get('phq9',[Phq9Controller::class, 'addAssessment'])->name('phq9.addAssessment');
    Route::post('phq9/store', [Phq9Controller::class, 'storeAssessment'])->name('phq9.storeAssessment');

    Route::get('gad7', [ExtraFormsController::class, 'addGad7'])->name('gad7.addGad7');
    Route::post('gad7/store', [ExtraFormsController::class, 'storeGad7'])->name('gad7.storeGad7');

    Route::get('ptsd5', [ExtraFormsController::class, 'addPtsd5'])->name('ptsd5.addPtsd5');
    Route::post('ptsd5/store', [ExtraFormsController::class, 'storePtsd5'])->name('ptsd5.storePtsd5');

    //admin routes
    Route::get('/admin/dashboard/charts', [AdminController::class, 'admin'])->name('admin.dashboard.charts');
    Route::get('/admin/test-charts', [AdminController::class, 'testCharts'])->name('admin.test-charts');


    //referral-testing routes
    Route::post('/testing', [ReferralController::class, 'sendtesting'])->name('sendreferral');
    Route::get('/service_category/get_services', [MflController::class, 'getServiceFromCategory'])->name('services_from_service_category');
    Route::get('/referralprocess', [ReferralController::class, 'outgoingReferralTabs']);


    //referral-tabs e.g referral/tabs/tab2
    Route::get('referral/tabs/{tab}', [ReferralController::class, 'show'])->name('referral.tabs');
    Route::post('referral/tabs/save/{tab}', [ReferralController::class, 'saveTabData'])->name('referral.tabs.save');

    // FHIR Routes
    Route::prefix('fhir')->group(function () {
        Route::get('/ServiceRequest/{id}', [ReferralController::class, 'fhirJson'])->name('fhir.referral.get');
        Route::post('/ServiceRequest/$validate', [ReferralController::class, 'validateReferral'])->name('fhir.referral.validate');
        Route::post('/ServiceRequest', [ReferralController::class, 'submitReferral'])->name('fhir.referral.submit');
        Route::get('/ServiceRequest', [ReferralController::class, 'search'])->name('fhir.referral.search');
    });

    // Verification Routes
    Route::prefix('verification')->name('verification.')->middleware('auth')->group(function () {
        Route::get('patient', [App\Http\Controllers\VerificationController::class, 'showPatientVerification'])->name('patient')->middleware('permission:verify patient');
        Route::get('referral', [App\Http\Controllers\VerificationController::class, 'showReferralVerification'])->name('referral')->middleware('permission:verify referral');
    });

    // Group Management Routes
    Route::resource('groups', GroupController::class)->middleware('auth');
});
