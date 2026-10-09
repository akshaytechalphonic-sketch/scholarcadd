<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\PromoCodeService;

class PromoCodeController extends Controller
{
    protected PromoCodeService $service;

    public function __construct(PromoCodeService $service)
    {
        $this->service = $service;
    }

    protected function PromoCodeList(Request $request)
    {
        return $this->service->PromoCodeList($request);

    }
     protected function PromoCodeCreate($id = null)
    {
        return $this->service->PromoCodeCreate($id);
    }

    public function PromoCodeStore(Request $request)
    {
        $result = $this->service->PromoCodeStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('promo-code-list')->with('success', $result['message']);
    }

        protected function PromoCodeEdit(Request $request)
    {
        return $this->service->PromoCodeCreate($request->id);
    }

    protected function PromoCodeUpdate(Request $request)
    {
       $result = $this->service->PromoCodeStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('promo-code-list')->with('success', $result['message']);
    }
}
