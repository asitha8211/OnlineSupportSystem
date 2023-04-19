<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsSupportAgent
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $supportAgentRoleId = Role::where('name', Role::SUPPORT_AGENT)->value('id');
        if($user->role_id === $supportAgentRoleId){
            return $next($request);
        } else {
            return response()->json([
                'error' => 'Unauthorised',
                'message' => 'Should be a support agent to access tickets.'], 401);
        }
    }
}
