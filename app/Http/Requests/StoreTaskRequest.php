<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
        // Rule::unique('tasks')->where('id', '<>', $id),
      ],

      'description' => 'required|min:10',

      'user_id' => 'required',

      'status' => [
        'nullable',
        Rule::in(['new', 'in_progress', 'cancelled', 'done'])
      ],

      'start_date' => 'required',

      'end_date' => 'required|after:start_date',
    ];
  }

  public function messages()
  {
    return [
      'title.required' => 'Please provide a title for the task',
      'title.min' => 'The task title may not be less than :min characters',

      'description.required' => 'Briefly describe what the task is about',
      'description.min' => 'You can\'t describe something in less than :min characters',

      'user_id.required' => 'Please pick a user to assign this task to',
      'user_id.integer' => 'The assigned user ID must be an integer',

      'status.in' => 'The task status must be one of :values',

      'cost.required' => 'For clarity, please provide a cost for the task',
      'cost.numeric' => 'We think that should actually be a number',
      'cost.integer' => 'Multiply the number you entered by 100 then try again',

      'start_date.required' => 'Pick task\'s starting date',

      'end_date.required' => 'Pick task\'s due date',
      'end_date.after' => 'Task\'s due date cannot be behind start date',
    ];
  }
}
