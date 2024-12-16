<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'first_name' => fake('en_ZA')->firstName(),
      'last_name' => fake('en_ZA')->lastName(),
      'email' => fake('en_ZA')->unique()->companyEmail,
      'email_verified_at' => now(),
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'remember_token' => Str::random(10),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   *
   * @return static
   */
  public function unverified()
  {
    return $this->state(fn (array $attributes) => [
      'email_verified_at' => null,
    ]);
  }

  public function configure()
  {
    return $this->afterCreating(function (\App\Models\User $user) {
      $numPhoneNumbers = fake('en_ZA')->numberBetween(0, 2);

      for ($i = 0; $i < $numPhoneNumbers; $i++) {
        $user->phoneNumbers()->create([
          'number' => fake('en_ZA')->phoneNumber(),
          'type' => fake('en_ZA')->randomElement(['work', 'home', 'mobile'])
        ]);
      }
    });
  }
}
