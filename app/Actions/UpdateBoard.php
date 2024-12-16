<?php

namespace App\Actions;

use App\Models\Board;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;

class UpdateBoard
{

  public function __invoke(Board $board): RedirectResponse
  {
    $user = auth()->user();

    if ($user->id === $board->user_id && request()->name) { // only owners of the boards can update boards

      $validatedData = request()->validate(Board::validationRules($board->project_id, $board->id));

      try {
        // Update board
        $board->name = $validatedData['name'];
        $board->save();

        return redirect()->back()->with('toast', [
          'type' => 'success',
          'title' => 'Success',
          'message' => 'Board was updated.'
        ]);

      } catch (QueryException $e) {

        if ($e->errorInfo[1] === 1062) {
          return redirect()->back()->withErrors(['name' => 'A board with this name already exists in this project.']);
        }

        throw $e;

      }
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'title' => request()->name ? 'Unauthorised action' : 'Required parameter',
      'message' => request()->name ? 'You are not allowed to edit this board!' : 'Please name your board'
    ]);
  }
}
