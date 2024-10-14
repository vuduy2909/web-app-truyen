<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class VerifyTokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $authorization = $request->header('Authorization');

            if (is_null($authorization)) {
                return handleResponseAPI('Thiếu authorization trong header');
            }

            $authorization = explode(' ', $authorization)[1];
            $user = User::query()->find(decrypt($authorization));
            if(is_null($user)) return handleResponseAPI("Tài khoản không tồn tại");
            if ($user->deleted_at !== null) return handleResponseAPI("Tài khoản đã bị khóa");
            $request->setUserResolver(function () use ($user) {
               return $user;
            });

            return $next($request);
        } catch (\Throwable $e) {
            return handleResponseAPI('Có lỗi xảy ra! ' . $e->getMessage());
        }
    }
}
