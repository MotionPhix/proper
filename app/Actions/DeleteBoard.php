<?php

namespace App\Actions;

use App\Models\Board;
use Illuminate\Http\RedirectResponse;

class DeleteBoard
{

  public function __invoke(Board $board): RedirectResponse
  {
    $user = auth()->user();

    if ($user->hasRole('admin') || $board->user_id === $user->id) {
      if ($board->tasks) {
        // Delete all tasks associated with the board
        \App\Models\Task::where('board_id', $board->id)->delete();
      }

      $board->delete();

      $toastTitles = collect([
        "Board Deleted",
        "Mission Accomplished",
        "Goodbye Board",
        "Board Annihilated",
        "Board Eliminated",
        "Board Obliterated",
        "Farewell Board",
        "Board Terminated",
        "Board Expunged",
        "Board Eradicated",
        "Board Removed",
        "Board Disintegrated",
        "Board Exterminated",
        "Board Vanished",
        "Board Liquidated"
      ]);

      return redirect()->back()->with('toast', [
        'type' => 'success',
        'title' => $toastTitles->random(),
        'message' => 'Board was permanently deleted.'
      ]);
    }

    return redirect()->back()->with('toast', [
      'type' => 'warning',
      'message' => 'You are not allowed to delete this board!',
      'title' => 'Unauthorised action'
    ]);
  }
}
