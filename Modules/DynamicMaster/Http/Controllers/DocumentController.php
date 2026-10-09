<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\DocumentService;
use Illuminate\Support\Facades\Auth;
use Modules\DynamicMaster\Http\Requests\DocumentRequest;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    protected DocumentService $service;

    public function __construct(DocumentService $service)
    {
        $this->service = $service;
    }

    protected function attachementList(Request $request)
    {
        return $this->service->attachementList($request);
    }

    protected function attachementUpload(DocumentRequest $request)
    {
        return $this->service->attachementUpload($request);
    }

    protected function attachementSubmitList(Request $request)
    {
        return $this->service->attachementSubmitList($request);
    }

    protected function attachementDelete(Request $request)
    {
        return $this->service->attachementDelete($request);
    }

    protected function attachementSubmit(Request $request)
    {
        return $this->service->attachementSubmit($request);
    }

}
