<?php

namespace Modules\PaymentHistory\Services;

use Modules\PaymentHistory\Repositories\PaymentHistoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentHistoryService extends Controller
{
    protected PaymentHistoryRepository $repository;

    public function __construct(PaymentHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        // Add business logic here
        return "Index from service for PaymentHistory";
    }
}