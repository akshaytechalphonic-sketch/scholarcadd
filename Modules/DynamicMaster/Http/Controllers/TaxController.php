<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\TaxService;
use Modules\DynamicMaster\Http\Requests\TaxRequest;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    protected TaxService $service;

    public function __construct(TaxService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function store(TaxRequest $request)
    {
        return $this->service->store($request);
    }

     protected function edit(Request $request)
    {
        return $this->service->edit($request->id);
    }


    //Tax Functions
    protected function tax_index(Request $request)
    {
        return $this->service->tax_all($request);
    }

    protected function tax_store(TaxRequest $request)
    {
        return $this->service->tax_store($request);
    }

     protected function tax_edit(Request $request)
    {
        return $this->service->tax_edit($request);
    }



}
