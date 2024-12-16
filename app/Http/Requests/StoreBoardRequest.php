<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBoardRequest extends FormRequest
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
      'name' => 'required|min:4', // Rule::unique('boards')->where('id', '<>', $id),
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'Please type a name for the board',
      'name.min' => 'Board name may not be less than :min characters',
    ];
  }
}
