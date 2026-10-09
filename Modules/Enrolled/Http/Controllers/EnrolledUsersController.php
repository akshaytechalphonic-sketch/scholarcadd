<?php

namespace Modules\Enrolled\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Enrolled\Services\EnrolledUsersService;
use Illuminate\Http\Request;

class EnrolledUsersController extends Controller
{
    protected EnrolledUsersService $service;

    public function __construct(EnrolledUsersService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->index($request);
    }
    protected function enrolledStore(Request $request)
    {
        return $this->service->enrolledStore($request);
    }
}
