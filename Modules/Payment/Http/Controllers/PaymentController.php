<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payment\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
     protected PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }
      protected function Invoice(Request $request)
    {
        return $this->service->Invoice($request);

    }

}


