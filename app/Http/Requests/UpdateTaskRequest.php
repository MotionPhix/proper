<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
      'title' => [
        'required',
        'min:5'
      ],

      'cost' => 'required|numeric',

      'description' => 'required|min:15',

      'start_date' => 'required|date',

      'end_date' => 'required|date|after:start_date',
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => 'Please provide a task title, e.g `T-shirt Branding`',
      'title.min' => 'Probably expand on the title a little bit',

      'description.required' => 'You might want to explain the task a bit more',

      'cost.required' => 'Provide a cost for the task',
      'cost.numeric' => 'Tas cost can only be whole numbers',

      'start_date.required' => 'Provide task\'s start date',
      'start_date.date' => 'Task\'s start date must be of `date` type',

      'end_date.required' => 'Pick task\'s due date',
      'end_date.date' => 'The due date must be of `date` type',
      'end_date.after' => 'The `Due date` cannot be earlier than the `Start date`',
    ];
  }
}
