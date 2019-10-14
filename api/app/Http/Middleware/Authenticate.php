<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Firebase\JWT\JWT; 

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

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

        if(!$token) {
            return response()->json([
                'error' => 'Token is missing!'
            ], 401);
        }

        try {
            $decoded = JWT::decode($token, $_ENV['JWT_secret'], ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Token has expired!'
            ], 401);
        }

        $request->username = $decoded->sub;

        return $next($request);
    }
}
