<?php

namespace App\Http\Requests;

use App\Rules\NewCompanyForUserRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
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
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|email|unique:contacts,email,' . $this->contact->id,
      'company_id' => 'required|exists:companies,id',
      'user_id' => [
        'nullable',
        'exists:users,id',
        new NewCompanyForUserRule($this->contact, $this->input('company_id'), $this->input('user_id'))
      ],
      'phone_numbers.*.number' => [
        'required',
        'string',
        Rule::unique('phone_numbers')->where(function ($query) {
          $query->where('morphable_type', 'contact');
        })
      ],
      'phone_numbers.*.type' => [
        'required',
        Rule::in(['home', 'work', 'mobile', 'fax'])
      ],
    ];
  }

  public function messages()
  {
    return [
      'first_name.required' => 'The first name field is required.',
      'first_name.string' => 'The first name field must be a string.',
      'first_name.max' => 'The first name may not be greater than :max characters.',
      'last_name.required' => 'The last name field is required.',
      'last_name.string' => 'The last name field must be a string.',
      'last_name.max' => 'The last name may not be greater than :max characters.',

      'email.required' => 'The email field is required.',
      'email.email' => 'The email must be a valid email address.',
      'email.unique' => 'The email address has already been taken.',

      'phone_numbers.*.number.required' => 'The phone number field is required.',
      'phone_numbers.*.number.unique' => 'The phone number has already been taken.',
      'phone_numbers.*.type.required' => 'The phone number type field is required.',
      'phone_numbers.*.type.in' => 'Invalid phone number type selected.',

      'company_id.required' => 'The company field is required.',
      'company_id.exists' => 'The selected company is invalid.',

      'status.string' => 'The status field must be a string.',
      'status.in' => 'The selected status is invalid.',
    ];
  }
}
