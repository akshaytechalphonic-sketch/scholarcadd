<?php

namespace Modules\PaymentHistory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\PaymentHistory\Services\PaymentHistoryService;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    protected PaymentHistoryService $service;

    public function __construct(PaymentHistoryService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->index($request);
    }
}