<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class ContactFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'first_name' => fake('en_ZA')->firstName,
      'last_name' => fake('en_ZA')->lastName,
      'position' => fake('en_ZA')->jobTitle(),
      'email' => fake('en_ZA')->companyEmail,
      'company_id' => \App\Models\Company::inRandomOrder()->first()->id,
      'user_id' => \App\Models\User::inRandomOrder()->first()->id,
    ];
  }

  public function configure()
  {
    return $this->afterCreating(function (\App\Models\Contact $contact) {
      // Create random phone numbers for the contact
      $numPhoneNumbers = fake()->numberBetween(0, 2);

      for ($i = 0; $i < $numPhoneNumbers; $i++) {
        $contact->phones()->create([
          'number' => fake('en_ZA')->phoneNumber(),
          'type' => fake('en_ZA')->randomElement(['work', 'mobile', 'home']),
        ]);
      }
    });
  }
}
