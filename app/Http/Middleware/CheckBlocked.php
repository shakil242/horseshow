<?php

namespace App\Http\Middleware;

use Closure;
use App\Participant;

class CheckBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $invite = nxb_decode($request->inviteid);
        $participant = Participant::find($invite);
        if (intval($participant->block) == 1) {
            \Session::flash('message', 'You are not allowed to access this url.');
            return redirect('/user/dashboard');
        }
        return $next($request);
    }
}
