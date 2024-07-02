<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\MyClass;


/** @deprecated */
class Authenticate extends BaseMiddleware
{
    use MyClass;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function handle($request, Closure $next)
    {
        $this->checkForToken($request);

        try {
            if (! $this->auth->parseToken()->authenticate()) {
                return $this->returnError('User not found',404);
            }
        } catch (JWTException $e) {
            return $this->returnError($e->getMessage(),404);
        }

        return $next($request);
    }
    /**
     * Attempt to authenticate a user via the token in the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function authenticate(Request $request)
    {
       
        $this->checkForToken($request);

        try {
            if (! $this->auth->parseToken()->authenticate()) {
                $error = ['errer'=>false,'message'=>'User not found'];
            }
        } catch (JWTException $e) {
            $message = $e->getMessage();
            $error = true;
        }
    }
}
