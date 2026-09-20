<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Number;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $locale = strip_tags($request->cookie('locale', app(SettingService::class)->get('appearance:language')));

        if (! $request->hasCookie('locale')) {
            Cookie::queue(Cookie::make('locale', $locale, 60 * 24 * 365));
        }
        App::setlocale($locale);

        Number::useLocale($locale);

        return $next($request);
    }
}
