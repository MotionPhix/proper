<?php

namespace App\Rules;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class NewCompanyForUserRule implements Rule
{
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    $user = User::find($value['user_id']);
    $customer = Customer::find($value['customer_id']);

    // Check if a new company is set for the selected user
    if ($user->company_id === $customer->company_id && $value['company_id'] !== $customer->company_id) {
      return false;
    }

    return true;
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'Cannot change users for the current customer since the customer hasn\'t changed companies.';
  }
}
