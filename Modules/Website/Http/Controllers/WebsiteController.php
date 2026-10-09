<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Website\Services\WebsiteService;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    protected WebsiteService $service;

    public function __construct(WebsiteService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->index($request);
    }
    protected function createPage(Request $request)
    {
        return $this->service->createPage($request);
    }

    //
     protected function blogsList(Request $request)
    {
        return $this->service->blogsList($request);
    }
    protected function blogsCreate($id = null)
    {
        return $this->service->blogsCreate($id);
    }
    protected function blogsStore(Request $request)
    {
        return $this->service->blogsStore($request);
    }
    protected function blogsView(Request $request)
    {
        return $this->service->blogsCreate($request->id, 'view');
    }
    protected function blogsEdit(Request $request)
    {
        return $this->service->blogsCreate($request->id);
    }
    protected function blogsUpdate(Request $request)
    {
        return $this->service->blogsStore($request);
    }

    protected function blogsDestroy(Request $request)
    {
        $result = $this->service->blogsDestroy($request->id);

        if (request()->ajax()) {
            return response()->json($result);
        }

        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('knowledge-center-list')->with('success', $result['message']);
    }

    protected function get_child(Request $request)
    {
        $data = $this->service->knowledgecenterSubCategory($request->parent_id);
        return response()->json($data);
    }

    //blog Code

    protected function pageList(Request $request)
    {
        return $this->service->pageList($request);
    }
    protected function pageCreate($id = null)
    {
        return $this->service->pageCreate($id);
    }
    protected function pageStore(Request $request)
    {
        return $this->service->pageStore($request);
    }
    protected function pageView(Request $request)
    {
        return $this->service->pageCreate($request->id, 'view');
    }
    protected function pageEdit(Request $request)
    {
        return $this->service->pageCreate($request->id);
    }
    protected function pageUpdate(Request $request)
    {
        return $this->service->pageStore($request);
    }


    //Contact page Code

    protected function contactList(Request $request)
    {

        return $this->service->contactList($request);
    }
    protected function contactCreate($id = null)
    {
        return $this->service->contactCreate($id);
    }
    protected function contactStore(Request $request)
    {
        return $this->service->contactStore($request);
    }
    protected function contactView(Request $request)
    {
        return $this->service->contactCreate($request->id, 'view');
    }
    protected function contactEdit(Request $request)
    {
        return $this->service->contactCreate($request->id);
    }
    protected function contactUpdate(Request $request)
    {
        return $this->service->contactStore($request);
    }


    // Tags
    protected function tagsList(Request $request)
    {
        return $this->service->tagsList($request);
    }
    protected function tagsCreate($id = null)
    {
        return $this->service->tagsCreate($id);
    }
    protected function tagsStore(Request $request)
    {
        return $this->service->tagsStore($request);
    }
    protected function tagsView(Request $request)
    {
        return $this->service->tagsCreate($request->id, 'view');
    }
    protected function tagsEdit(Request $request)
    {
        return $this->service->tagsCreate($request->id);
    }
    protected function tagsUpdate(Request $request)
    {
        return $this->service->tagsStore($request);
    }

    // ============= slider ==================

    protected function sliderList(Request $request)
    {
        return $this->service->sliderList($request);
    }
     protected function sliderCreate($id = null)
    {

        return $this->service->sliderCreate($id);
    }
    protected function sliderStore(Request $request)
    {
       $result = $this->service->sliderStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('slider-list')->with('success', $result['message']);
    }
    protected function sliderEdit(Request $request )
    {
        $result = $this->service->sliderCreate($request->id);
        return $result;
    }
    protected function sliderUpdate(Request $request)
   {
       $result = $this->service->sliderStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('slider-list')->with('success', $result['message']);
    }

}
