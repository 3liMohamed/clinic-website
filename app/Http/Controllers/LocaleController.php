<?php

namespace App\Http\Controllers;

use Filament\Notifications\Notification;

class LocaleController extends Controller
{
    public function __invoke(string $locale)
    {
        Notification::make()
            ->success()
            ->title(__('notifications.locale_changed_to_:locale', ['locale' => $locale], locale: $locale))
            ->send();


        return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}
