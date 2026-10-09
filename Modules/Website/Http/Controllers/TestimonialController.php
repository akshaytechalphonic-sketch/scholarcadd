<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Website\Services\TestimonialService;

class TestimonialController extends Controller
{
    protected TestimonialService $service;

    public function __construct(TestimonialService $service)
    {
        $this->service = $service;
    }

    protected function TestimonialIndex(Request $request)
    {

        return $this->service->Testimonial_index($request);
    }

    protected function TestimonialCreate(Request $request)
    {
        return $this->service->TestimonialCreate($request);
    }

    protected function TestimonialStore(Request $request)
    {
        return $this->service->TestimonialStore($request);
    }

    protected function TestimonialEdit(Request $request)
    {
        return $this->service->TestimonialCreate($request);
    }

    protected function TestimonialUpdate(Request $request)
    {
        return $this->service->TestimonialStore($request);
    }

    protected function TestimonialDestroy(Request $request)
    {
        return $this->service->TestimonialDestroy($request);
    }

    protected function TestimonialleView(Request $request)
    {
        return $this->service->TestimonialCreate($request, 'view');
    }

    protected function get_stateName(Request $request)
    {
        $data = $this->service->getthedataofState($request->parent_id);

        return response()->json($data);
    }
}
