<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
      'name' => 'required|min:5',

      'description' => 'required|min:15',

      'documents.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',

      'member_id' => 'nullable|integer',
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => 'Please provide a name for the project',
      'name.min' => 'Probably expand on the name a little bit',

      'description.required' => 'Provide a description for your project',
      'description.min' => 'It\'s hard to learn anything from such a less than :min characters description',

      'documents.mimes' => 'The files must be of JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, XLS, or XLSX type.',

      'member_id.integer' => 'Please pick a user to add to the project team',
    ];
  }
}
