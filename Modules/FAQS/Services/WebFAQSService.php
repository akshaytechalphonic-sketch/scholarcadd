<?php

namespace Modules\FAQS\Services;

use Modules\FAQS\Repositories\WebFAQRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class WebFAQSService extends Controller
{
    /**
     * The repository instance.
     *
     * @var WebFAQRepository
     */
    protected WebFAQRepository $repository;

    /**
     * Create a new service instance.
     *
     * @param WebFAQRepository $repository
     */
    public function __construct(WebFAQRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display Course FAQs list or DataTable JSON.
     */
    public function Faqscourselist_index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->FaqsindexList();
                $query = $this->repository->FaqsindexFilters($query, $request)
                    ->orderBy('id', 'desc');

                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%$search%");
                        $q->orWhere('description', 'like', "%$search%");
                    });
                }

                return DataTables::of($query)
                    ->addColumn('coursedata', function ($row) {
                        $view_url = route('faqs-general-view', ['id' => encrypt($row->id)]);
                        $categoryName = $row?->Coursedate?->course_title ?? 'N/A';
                        return '<a class="text-blue-500 hover:underline" href="' . e($view_url) . '">' . e($categoryName) . '</a>';
                    })
                    ->addColumn('description', function ($row) {
                        $plainText = strip_tags($row->description); // remove summernote HTML
                        return e(Str::limit($plainText ?: 'N/A', 30));
                    })
                    ->addColumn('title', fn($row) => e(Str::limit($row->title ?? 'N/A', 70)))
                    ->addColumn('created_at', fn($row) => date_time_formate($row?->created_at))
                    ->addColumn('created_by', fn($row) => e($row?->creator?->name ?? 'N/A'))
                    ->addColumn('actions', function ($row) {
                        $id = encrypt($row->id);
                        $view_url = route('faqs-general-view', ['id' => $id]);
                        $edit_url = route('faqs-general-edit', ['id' => $id]);
                        $delete_url = route('faqs-general-destroy', ['id' => $id]);
                        return '
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                    <li><a href="' . e($view_url) . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-eye-line mr-2"></i> View</a></li>
                                    <li><a href="' . e($edit_url) . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i> Edit</a></li>
                                    <li>
                                        <a href="javascript:void(0);"
                                        onclick="deleteRecord(\''.$delete_url.'\')"
                                        class="block px-6 py-2 hover:bg-gray-100 text-red-600">
                                            <i class="ri-delete-bin-line mr-2"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                    })
                    ->rawColumns(['actions', 'coursedata'])
                    ->make(true);
            }

            return view('faqs::Faqs.index');
        } catch (\Throwable $e) {
            // Safer and Laravel-friendly error handling
            Log::error('Error fetching Course FAQs list: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function faqsGeneralCreate($id, $view = null)
    {
        try {
            $data = $this->repository->faqsGeneralCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Add FAQS Category ");
            return back()->with('error', $e->getMessage());
        }
    }

    public function FaqsGeneralStore(Request $request)
    {
        try {
            $data = $this->repository->faqsGeneralStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('faqs-list')->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Course Course");
            return back()->with('error', $e->getMessage());
        }
    }


    // this mathod for FAQS General Destroy methods create by Lal Singh
public function FaqsGeneralDestroy($id)
{
    try {

        $data = $this->repository->FaqsGeneralDestroy($id);

        if (request()->ajax()) {
            return response()->json($data);
        }

        if (!$data['status']) {
            return back()->with('error', $data['message']);
        }

        return redirect()->route('faqs-list')
                         ->with('success', $data['message']);

    } catch (\Exception $e) {

        catch_log($e, "Deleting Course Course");

        if (request()->ajax()) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return back()->with('error', $e->getMessage());
    }
}



    // FAQS Category methods create by Lal Singh

    public function FaqsCategorylist_index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = $this->repository->FaqsCategoryindexList();
                $query = $this->repository->FaqsCategoryindexFilters($query, $request)
                    ->orderBy('id', 'desc');
                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('category_name', 'like', "%$search%");
                    });
                }
                return DataTables::of($query)
                    ->addColumn('coursedata', function ($row) {
                        return $row?->category_name ?? 'N/A';
                    })
                    ->addColumn('created_at', fn($row) => date_time_formate($row?->created_at))
                    ->addColumn('created_by', fn($row) => e($row?->creator?->name ?? 'N/A'))
                    ->addColumn('actions', function ($row) {
                        $id = encrypt($row->id);

                        $edit_url = route('faqs-category-edit', ['id' => $id]);

                        return '
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">

                                    <li><a href="' . e($edit_url) . '" class="block px-6 py-2 hover:bg-gray-100"><i class="ri-pencil-line mr-2"></i> Edit</a></li>

                                </ul>
                            </div>
                        </div>';
                    })
                    ->rawColumns(['actions', 'coursedata'])
                    ->make(true);
            }

            return view('faqs::FaqsCategory.index');
        } catch (\Throwable $e) {
            // Safer and Laravel-friendly error handling
            Log::error('Error fetching Course FAQs list: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function FaqsCategory_create($id, $view = null)
    {
        try {
            $data = $this->repository->faqsCetegoryCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Add FAQS Category ");
            return back()->with('error', $e->getMessage());
        }
    }
    public function FaqsCategoryStore(Request $request)
    {
        try {
            $data = $this->repository->faqs_categoryStore($request);
            if (!$data['status']) {
                return back()->with('error', $data['message'])->withInput();
            }
            return redirect()->route('faqs.category.list')->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Creating Course Course");
            return back()->with('error', $e->getMessage());
        }
    }

    public function FaqsCetegoryDestroy($id)
    {
        try {
            $data = $this->repository->FaqsCetegoryDestroy($id);
            if (!$data['status']) {
                return response()->json(['status' => false, 'error' => $data['message']]);
            }
            return redirect()->route('faqs.category.list')->with('success', $data['message']);
        } catch (\Exception $e) {
            catch_log($e, "Deleting Course Course");
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
