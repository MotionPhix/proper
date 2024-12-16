<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'first_name' => 'required|alpha',
      'last_name' => 'required|alpha',
      'email' => 'required|unique:contacts,email|email',
      'phone_numbers.*.number' => 'nullable',
      'phone_numbers.*.type' => 'nullable',
      'company_id' => 'required|exists:companies,id'
    ];
  }

  public function messages()
  {
    return [
      'first_name.required' => 'Provide contact\'s first name',
      'first_name.alpha' => 'First name can only contain letters',

      'last_name.required' => 'Provide contact\'s last name',
      'last_name.alpha' => 'Last name can only contain letters',

      'email.required' => 'Provide contact\'s email address',
      'email.unique' => 'Email is already in use by another contact',
      'email.email' => 'Looks like you have provided an invalid email',

      'company_id.required' => 'Pick a company the contact works with',
      'company_id.exists' => 'Selected company does\'t exist. Create it',
    ];
  }
}
