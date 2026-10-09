<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\DynamicMaster\Models\TeamApplication;
use Modules\DynamicMaster\Services\TeamApplicationService;



class TeamApplicationController extends Controller
{
    protected TeamApplicationService $service;

    public function __construct(TeamApplicationService $service)
    {
        $this->service = $service;
    }

    protected function ApplicationList(Request $request)
    {

        return $this->service->ApplicationList($request);

    }
     protected function Applicationview(Request $request)
    {
        return $this->service->Applicationview($request);
    }
}

