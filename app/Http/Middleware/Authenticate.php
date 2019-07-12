<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();

        if (!$token) {
            throw new UnauthorizedHttpException('Challenge', json_encode('Missing authentication token.'));
        }

        try {
            $token = Crypt::decrypt($token);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Challenge', json_encode('Token is malformed or corrupted.'));
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Challenge', json_encode($e->getMessage()));
        }

        // After authentication, include the user object in the request so it can be easily accessed by other parts of the app
        $user          = User::find($credentials->sub);
        $request->auth = $user;

        return $next($request);
    }
}
