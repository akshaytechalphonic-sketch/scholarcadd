<?php

namespace Modules\NumberSequence\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\NumberSequence\Services\NumberSequenceService;
use Illuminate\Http\Request;

class NumberSequenceController extends Controller
{
    protected NumberSequenceService $service;

    public function __construct(NumberSequenceService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function add(Request $request)
    {
        return $this->service->create($request);
    }

    protected function store(Request $request)
    {
        return $this->service->store($request);
    }

    protected function index_map(Request $request)
    {
        return $this->service->index_map($request);
    }

    protected function store_mapping(Request $request)
    {
        return $this->service->store_mapping($request);
    }

    protected function destroy(Request $request)
    {
         return $this->service->destroy($request);
    }

}
