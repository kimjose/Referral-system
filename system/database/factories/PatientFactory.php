<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;


class PatientFactory extends Factory
{
    protected $model = Patient::class;
    public function definition()
    {
        return [
            'upi' => $this->faker->unique()->numerify('UPI-####'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
           // 'phone_number' => $this->faker->phoneNumber,
           // 'email' => $this->faker->email,
            'address' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
