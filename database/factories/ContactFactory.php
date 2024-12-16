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
      'email' => fake('en_ZA')->companyEmail,
      'company_id' => \App\Models\Company::inRandomOrder()->first()->id,
      'user_id' => \App\Models\User::inRandomOrder()->first()->id,
    ];
  }

  public function configure()
  {
    return $this->afterCreating(function (\App\Models\Contact $contact) {
      // add the entry to `company_contact` table for `company - contact` relation tracking
      $contact->companies()->attach($contact->company_id, [
        'from_date' => now()->subYears(rand(2, 4))->format('Y-m-d H:i:s'),
        'to_date' => null
      ]);

      // add the entry to `contact_user` table for `contact - user` relation tracking
      $contact->users()->attach($contact->user_id, [
        'company_id' => $contact->company_id,
        'from_date' => now()->subMonths(rand(5, 8))->format('Y-m-d H:i:s'),
        'to_date' => null
      ]);

      // Create random phone numbers for the contact
      $numPhoneNumbers = fake()->numberBetween(0, 2);

      for ($i = 0; $i < $numPhoneNumbers; $i++) {
        $contact->phoneNumbers()->create([
          'number' => fake('en_ZA')->phoneNumber(),
          'type' => fake('en_ZA')->randomElement(['work', 'mobile', 'home']),
        ]);
      }
    });
  }
}
