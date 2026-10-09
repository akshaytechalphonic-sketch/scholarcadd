<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\EmailService;
use Modules\DynamicMaster\Http\Requests\EmailRequest;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    protected EmailService $service;

    public function __construct(EmailService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function create(Request $request)
    {
        return $this->service->create($request);
    }

    protected function store(EmailRequest $request)
    {
        return $this->service->store($request);
    }

     protected function edit(Request $request)
    {
        return $this->service->edit($request);
    } 
     protected function get_all_email_template(Request $request)
    {
        return $this->service->get_all_email_template($request);
    } 





}
