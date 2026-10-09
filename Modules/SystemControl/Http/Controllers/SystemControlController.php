<?php

namespace Modules\SystemControl\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\SystemControl\Services\SystemControlService;
use Illuminate\Http\Request;

class SystemControlController extends Controller
{
    protected SystemControlService $service;

    public function __construct(SystemControlService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->index($request);
    }
    protected function sidebar_store(Request $request)
    {
        return $this->service->sidebar_store($request);
    }
    
}