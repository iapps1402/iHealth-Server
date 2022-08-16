<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use League\OAuth2\Server\Exception\OAuthServerException;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return null|mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = Auth::guard('api')->user();
        } catch (Exception $exception) {
            return response('Unauthorized.', 401);
        }
        if ($user != null) {
            Lang::setLocale($user->language);
            $user->update([
                'online_at' => now()
            ]);

            return $next($request);
        }

        return response('Unauthorized.', 401);
        //return redirect(Route('login'));
    }
}
