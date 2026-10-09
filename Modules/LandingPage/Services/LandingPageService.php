<?php

namespace Modules\LandingPage\Services;

use Modules\LandingPage\Repositories\LandingPageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingPageService extends Controller
{
    protected LandingPageRepository $repository;

    public function __construct(LandingPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        return view('landingpage::index');
    }
}
