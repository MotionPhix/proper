<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response | RedirectResponse
  {
    if (!$request->user() || !$request->user()->hasAnyRole(['admin', 'general-manager', 'sales-member'])) {
      // abort(403, 'Unauthorized action.');
      return redirect()->back()->with('toast', [
        'type' => 'danger',
        'title' => 'Uncalled-for attempt',
        'message' => 'Contacts can only be viewed by authorised users. You are not one of them.'
      ]);
    }

    return $next($request);
  }
}
