<?php

namespace App\Actions;

use App\Http\Requests\StoreBoardRequest;
use App\Models\Board;
use App\Models\Project;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;

class CreateBoard
{
  public function __invoke(StoreBoardRequest $request, Project $project): RedirectResponse
  {
    if ($project->isOwner($request->user())) {
      $validatedData = $request->validate(Board::validationRules($project->id));

      try {

        $board = new Board();

        $board->name = $validatedData['name'];
        $board->user_id = $request->user()->id;
        $board->project_id = $project->id;

        $board->save();

        $toastTitles = collect([
          'Success!',
          'Well done!',
          'Great job!',
          'Awesome!',
          'Congratulations!',
          'Hooray!',
        ]);

        return redirect()->back()->with('toast', [
          'type' => 'success',
          'title' => $toastTitles->random(),
          'message' => 'Board was successfully created!'
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
      'title' => 'Unauthorised action',
      'message' => 'You are not allowed to create boards on this project'
    ]);
  }
}
