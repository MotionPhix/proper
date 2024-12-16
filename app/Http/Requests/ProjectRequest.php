<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
          'name' => 'required|min:5',

          'description' => 'nullable|min:20',

          'company_id' => 'required|exists:companies,id',

          'contact_id' => 'required|exists:contacts,id',

          'documents.*' => 'nullable|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx',
        ];
    }

    public function messages()
    {
      return [
        'name.required' => 'Type in project\'s name',
        'name.min' => 'The name may not be less than :min characters',

        'description.min' => 'A project can\'t be described in less than :min characters',

        'company_id.required' => 'Pick a company running the project',
        'company_id.exists' => 'The picked company does not exist in the database',

        'contact_id.required' => 'Pick a contact person for the project',
        'contact_id.exists' => 'The contact does not work for the selected company',

        'documents.mimes' => 'The files must be of JPEG, PNG, GIF, PDF, DOC, DOCX, XLS, or XLSX type.',
      ];
    }
}
