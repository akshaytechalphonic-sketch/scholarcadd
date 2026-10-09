<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends BaseController
{
    public function languageSwitch(Request $request)
    {
        $lang = $request->lang ?? 'en';
        session()->put('locale', $lang);
        App::setLocale($lang);
        return redirect()->back();
    }
}
