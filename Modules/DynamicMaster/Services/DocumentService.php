<?php

namespace Modules\DynamicMaster\Services;

use Modules\DynamicMaster\Repositories\DocumentRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DocumentService extends Controller
{
    protected DocumentRepository $repository;

    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function attachementUpload(Request $request)
    {
        return $this->repository->uploadAttachment($request);
    }

    public function attachementList(Request $request)
    {
        return $this->repository->getAttachmentList($request);
    }

    public function attachementSubmitList(Request $request)
    {
        return $this->repository->getSubmittedAttachments($request);
    }

    public function attachementDelete(Request $request)
    {
        return $this->repository->deleteAttachment($request);
    }

    public function attachementSubmit(Request $request)
    {
        return $this->repository->submitAttachment($request);
    }

}
