<?php

namespace Database\Factories;

use App\Models\Referral;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Referral>
 */
class ReferralFactory extends Factory
{
    protected $model = Referral::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'clientUPI' => $this->faker->unique()->numerify('UPI-####'),
            'fhir_status' => $this->faker->randomElement(['Pending', 'Accepted', 'Rejected']),
            'priority' => $this->faker->randomElement(['urgent', 'less urgent', 'asap', 'mild']),
            'diagnosis' => $this->faker->numerify('####'),
            'referringOfficer' => $this->faker->numerify('DR-###'),
            'referredFacility' => $this->faker->numerify('FAC-###'),
            'reasonReferral' => $this->faker->sentence(),
            'additionalNotes' => $this->faker->paragraph(),
            'historyInvestigation' => $this->faker->paragraph(),
            'serviceNotes' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
