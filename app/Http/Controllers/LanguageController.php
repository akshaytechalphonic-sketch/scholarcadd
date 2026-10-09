<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends BaseController
{
    /**
     * Switch the application language.
     */
    public function languageSwitch(Request $request)
    {
        // Get requested language or fallback to 'en'
        $lang = $request->lang ?? 'en';

        // Save language in session
        session()->put('locale', $lang);

        // Optionally, set the locale immediately
        App::setLocale($lang);

        // Redirect back to previous page
        return redirect()->back();
    }
}
