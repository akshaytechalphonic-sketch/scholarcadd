<?php

namespace Modules\Website\Services;

use Modules\Website\Repositories\WebsiteRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class WebsiteService extends Controller
{
    protected WebsiteRepository $repository;

    public function __construct(WebsiteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        // Add business logic here
        return "Index from service for Website";
    }
    //Blog Code

    public function blogsList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->blogsList();
                $query = $this->repository->blogsFilters($query, $request)->orderBy('id', 'desc');
                if (!$request->custom_filter || !in_array('tab', $request->custom_filter)) {
                    // dd($request);
                    $count_data = $this->repository->getAllCount($request);
                } else {
                    $count_data = null;
                }
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('resource_title', 'like', "%$search%");
                        $q->orWhere('short_description', 'like', "%$search%");
                        $q->orWhere('resource_auther', 'like', "%$search%");
                    });
                }
                return DataTables::of($query)
                    ->addColumn('resource_title', function ($row) {
                        return $row?->resource_title;
                    })
                     ->addColumn('category', function ($row) {
                        return $row?->resource_title;
                    })

                    ->addColumn('status', function ($row) {
                        return status_check($row->status);
                    })
                    ->addColumn('categoryname', function ($row) {
                            return $row->category?->name ?? '';
                        })
                    ->addColumn('meta_description', function ($row) {
                        return  Str::limit($row->short_description ?? 'N/A', 50);
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->resource_auther ?? 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);

                        $edit_url = route('knowledge-center-edit', ['id' => $incr]);
                        $delete_url = route('knowledge-center-destroy', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        $dropdown .= '<li>
                                <a href="javascript:void(0);"
                                onclick="deleteRecord(\''.$delete_url.'\')"
                                class="block px-6 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </a>
                            </li>';


                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'categoryname' , 'resource_title', 'status', 'meta_description', 'created_by'])
                    ->with([
                        'count_data' => $count_data,
                    ])
                    ->make(true);
            }
            return view('website::KnowledgeCenter.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Inventory Location data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function blogsCreate($id, $view = null)
    {
        try {
            $data = $this->repository->blogsCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Blog");
            return back()->with('error', $e->getMessage());
        }
    }

    public function knowledgecenterSubCategory($request)
    {
        return Controller::model('CourseCategory', 'Course')::where('sub_category_id', $request)->get();
    }

    public function blogsStore(Request $request)
    {
        try {
            $data = $this->repository->blogsStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('knowledge-center-list', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Blog");
            return back()->with('error', $e->getMessage());
        }
    }

    public function blogsDestroy($id)
    {
        try {
            $data = $this->repository->blogsDestroy($id);

            if (!$data['status']) {
                if (request()->ajax()) {
                    return response()->json(['status' => false, 'error' => $data['message']]);
                }
                return ['status' => false, 'message' => $data['message']];
            }

            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => $data['message']]);
            }

            return ['status' => true, 'message' => $data['message']];

        } catch (\Exception $e) {
            catch_log($e, "Deleting blogd");
            if (request()->ajax()) {
                return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
            }
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // end pages data ----------------

    public function pageList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->pageList();
                $query = $this->repository->pageListFilters($query, $request)->orderBy('id', 'desc');
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%$search%");
                        $q->orWhere('meta_description', 'like', "%$search%");
                        $q->orWhere('status', 'like', "%$search%");
                    });
                }
                return DataTables::of($query)
                    ->addColumn('title', function ($row) {
                        return $row?->title;
                    })
                    ->addColumn('status', function ($row) {
                        return status_check($row->status);
                    })

                    ->addColumn('meta_description', function ($row) {
                        return Str::words($row?->meta_description, 15, '...');
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator?->name ?? 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);

                        $edit_url = route('page-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';


                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';


                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'title', 'status', 'meta_description', 'created_by'])
                    ->make(true);
            }
            return view('website::pages.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Pages data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function pageCreate($id, $view = null)
    {
        try {
            $data = $this->repository->pageCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Page");
            return back()->with('error', $e->getMessage());
        }
    }

    public function pageStore(Request $request)
    {
        try {
            $data = $this->repository->pageStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('page-view', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Page");
            return back()->with('error', $e->getMessage());
        }
    }



    // end contact data ----------------

    public function contactList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->contactList();
                $query = $this->repository->contactListFilters($query, $request)->orderBy('id', 'desc');

                return DataTables::of($query)
                    ->addColumn('phone', function ($row) {
                        $row?->title;
                    })
                    ->addColumn('status', function ($row) {
                        return status_check($row->status);
                    })
                    ->addColumn('address', function ($row) {
                        return ($row?->meta_title);
                    })
                    ->addColumn('meta_description', function ($row) {
                        return $row?->meta_description;
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator?->name ?? 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);

                        $edit_url = route('contact-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';


                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';


                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'title', 'status', 'meta_title', 'meta_description', 'created_by'])
                    ->make(true);
            }
            return view('website::contact.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Pages data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function contactCreate($id, $view = null)
    {
        try {
            $data = $this->repository->contactCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Contact");
            return back()->with('error', $e->getMessage());
        }
    }

    public function contactStore(Request $request)
    {
        try {
            $data = $this->repository->contactStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('contact-view', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Page");
            return back()->with('error', $e->getMessage());
        }
    }
    // Tags

    public function tagsList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->tagsList();
                $query = $this->repository->tagsFilters($query, $request)->orderBy('id', 'desc');

                return DataTables::of($query)
                    ->addColumn('title', function ($row) {
                        return  $row?->title;
                    })
                    ->addColumn('status', function ($row) {
                        return status_check($row->status);
                    })
                    ->addColumn('meta_title', function ($row) {
                        return ($row?->meta_title);
                    })
                    ->addColumn('meta_description', function ($row) {
                        return $row?->meta_description;
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row?->creator?->name ?? 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        // $view_url = route('tags-view', ['id' => $incr]);
                        $edit_url = route('tags-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';


                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';


                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'title', 'status', 'meta_title', 'meta_description', 'created_by'])
                    ->make(true);
            }
            return view('website::tags.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Pages data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function tagsCreate($id, $view = null)
    {
        try {
            $data = $this->repository->tagsCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Page");
            return back()->with('error', $e->getMessage());
        }
    }

    public function tagsStore(Request $request)
    {
        try {
            $data = $this->repository->tagsStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('tags-view', ['id' => encrypt($data['data']->id)])->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Page");
            return back()->with('error', $e->getMessage());
        }
    }

    // ============= slider ============

    public function sliderList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->sliderList();
                $query = $this->repository->sliderFilters($query, $request)->orderBy('id', 'desc');

                return DataTables::of($query)
                    ->addColumn('heading', function ($row) {
                        return ($row->heading);
                    })
                    ->addColumn('main_content', function ($row) {
                        return ($row?->main_content);
                    })
                    ->addColumn('created_at', function ($row) {
                        return $row?->created_at;
                    })
                    ->addColumn('updated_at', function ($row) {
                        return $row?->updated_at;
                    })
                    ->addColumn('actions', function ($row) {
                        $incr = encrypt($row?->id);
                        // $view_url = route('slider-list', ['id' => $incr]);
                        $edit_url = route('slider-edit', ['id' => $incr]);

                        $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';

                        // $dropdown .= '<li><a href="' . $view_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i>View</a></li>';
                        $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i>Edit</a></li>';
                        $dropdown .= '<li><button onclick="confirmDeleteHeader(\'' . $incr . '\')" class="block w-full text-left px-6 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="ri-delete-bin-line mr-2"></i>Delete</button></li>';

                        $dropdown .= '</ul></div></div>';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'heading', 'section', 'created_at', 'created_by' , 'updated_at'])
                    ->make(true);
            }
            return view('website::slider.index');
        } catch (\Exception $e) {
            catch_log($e, "Getting list of Pages data");
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'error' => $e->getMessage(),
                    ],
                    500
                );
            }
            return back()->with('error', $e->getMessage());
        }
    }

     public function sliderCreate($id, $view = null)
    {
        try {
            $data = $this->repository->sliderCreate($id, $view);

            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing Page");
            return back()->with('error', $e->getMessage());
        }
    }

    public function sliderStore(Request $request)
    {
        $cmp_and_user = Controller::loginandcompany();
        $data = $request->all();
        $data['created_by'] = $cmp_and_user[0] ;
        $data['company_id'] = $cmp_and_user[1];
        try {

            if (isset($data['id'])) {
                $id = decrypt($data['id']);
            $result = $this->repository->sliderStore($id, $data);
            } else {

                $result = $this->repository->sliderStore(null , $data);
            }
            return $result;
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

}
