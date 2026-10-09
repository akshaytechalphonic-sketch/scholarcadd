<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Website\Services\ConsultingService;

class ConsultingController extends Controller
{
    protected ConsultingService $service;

    public function __construct(ConsultingService $service)
    {
        $this->service = $service;
    }

    protected function consultingIndex(Request $request)
    {

        return $this->service->consultingindex($request);
    }

    public function consultingCreate(Request $request)
    {
        return $this->service->consultingCreate($request);
    }
    protected function consultingStore(Request $request)
    {
        return $this->service->consultingStore($request);
    }

    protected function consultingEdit(Request $request)
    {
        return $this->service->consultingCreate($request);
    }

    protected function consultingUpdate(Request $request)
    {
        return $this->service->consultingStore($request);
    }

    protected function consultingDestroy(Request $request)
    {
        return $this->service->consultingDestroy($request);
    }

    
    protected function get_stateName(Request $request)
    {
        $data = $this->service->getthedataofState($request->parent_id);

        return response()->json($data);
    }
}
