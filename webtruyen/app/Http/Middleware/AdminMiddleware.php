<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->level_id === 3) {
                return $next($request);
            }
        }
        if (url()->previous() !== null)
            return redirect()->back()->with('error', 'Bạn phải đăng nhập với tư cách quản trị viên.');

        return redirect()->route('login')->with('error', 'Bạn phải đăng nhập với tư cách quản trị viên.');
    }
}
