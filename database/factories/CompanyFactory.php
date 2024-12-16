<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'name' => fake('en_ZA')->company,
      'address' => fake('en_ZA')->streetAddress
    ];
  }

  /**
   * Indicate that the model has many customers.
   *
   * @return \Illuminate\Database\Eloquent\Factories\Factory
   */
  /*public function hasCustomers()
  {
    return $this->afterCreating(function (Company $company) {
      $contact = \App\Models\Contact::factory()->create();
      $company->contacts()->attach($contact, [
        'from_date' => now(),
        'to_date' => now()->addYear(),
      ]);
    });
  }*/
}
