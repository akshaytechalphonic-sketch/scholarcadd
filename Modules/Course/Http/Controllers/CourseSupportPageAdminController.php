<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseSupportPage;
use Yajra\DataTables\DataTables;

class CourseSupportPageAdminController extends Controller
{
    /**
     * List all Course Support Pages (DataTable + Blade View)
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = CourseSupportPage::with('course')->orderBy('id', 'desc');

                if ($request->filled('search.value')) {
                    $search = $request->input('search.value');
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('course', function ($cq) use ($search) {
                                $cq->where('course_title', 'like', "%{$search}%")
                                   ->orWhere('url_title', 'like', "%{$search}%");
                            });
                    });
                }

                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                return DataTables::of($query)
                    ->addColumn('course_name', function ($row) {
                        return $row->course?->course_title ?? 'N/A';
                    })
                    ->addColumn('type_badge', function ($row) {
                        $labels = [
                            'exam-format' => ['bg' => 'bg-indigo-100 text-indigo-800 border-indigo-200', 'label' => 'Exam Format'],
                            'exam' => ['bg' => 'bg-indigo-100 text-indigo-800 border-indigo-200', 'label' => 'Exam'],
                            'syllabus' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Syllabus'],
                            'notes' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Study Notes'],
                        ];
                        $conf = $labels[$row->type] ?? ['bg' => 'bg-gray-100 text-gray-800 border-gray-200', 'label' => ucfirst($row->type)];
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $conf['bg'] . '">' . $conf['label'] . '</span>';
                    })
                    ->addColumn('page_title', function ($row) {
                        return e($row->title ?? 'Untitled Page');
                    })
                    ->addColumn('status_badge', function ($row) {
                        $encId = encrypt($row->id);
                        $isPub = ($row->status === 'published');
                        $btnClass = $isPub ? 'bg-green-100 text-green-700 hover:bg-green-200 border-green-300' : 'bg-rose-100 text-rose-700 hover:bg-rose-200 border-rose-300';
                        $label = $isPub ? '● Published (Enabled)' : '○ Draft (Disabled)';
                        $title = $isPub ? 'Click to Disable (Set to Draft)' : 'Click to Enable (Set to Published)';

                        return '<button type="button" title="' . $title . '" onclick="togglePageStatus(\'' . $encId . '\')" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border cursor-pointer transition ' . $btnClass . '">' . $label . '</button>';
                    })
                    ->addColumn('updated_at_formatted', function ($row) {
                        return $row->updated_at ? $row->updated_at->format('d M Y, h:i A') : 'N/A';
                    })
                    ->addColumn('actions', function ($row) {
                        $encId = encrypt($row->id);
                        $editUrl = route('course-support-page-edit', ['id' => $encId]);
                        $courseSlug = $row->course?->url_title ?? '';
                        $previewUrl = $courseSlug ? 'http://localhost:3000/' . $courseSlug . '/' . ($row->type === 'exam-format' ? 'exam' : $row->type) : '#';

                        $html = '<div class="relative inline-block text-left dropdown">
                            <button type="button" class="dropdown-toggle text-gray-500 hover:text-gray-700 p-1 rounded hover:bg-gray-100">
                                <i class="ri-more-2-fill text-lg"></i>
                            </button>
                            <div class="dropdown-menu hidden absolute right-0 z-50 mt-1 w-44 bg-white border border-gray-200 rounded-md shadow-lg py-1">
                                <a href="' . $editUrl . '" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="ri-pencil-line mr-2 text-blue-500"></i> Edit Page
                                </a>
                                <a href="' . $previewUrl . '" target="_blank" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="ri-external-link-line mr-2 text-emerald-500"></i> View on Frontend
                                </a>
                                <button type="button" onclick="confirmDeletePage(\'' . $encId . '\')" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                    <i class="ri-delete-bin-line mr-2"></i> Delete
                                </button>
                            </div>
                        </div>';

                        return $html;
                    })
                    ->rawColumns(['type_badge', 'status_badge', 'actions'])
                    ->make(true);
            }

            return view('Course::support-pages.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show form to create a new Support Page
     */
    public function create()
    {
        $courses = Course::where('status', 'Active')
            ->whereNull('deleted_at')
            ->orderBy('course_title', 'asc')
            ->get(['id', 'course_title', 'url_title']);

        $data = null;
        return view('Course::support-pages.create', compact('courses', 'data'));
    }

    /**
     * Show form to edit an existing Support Page
     */
    public function edit($id)
    {
        try {
            $decId = decrypt($id);
            $data = CourseSupportPage::with('course')->findOrFail($decId);

            $courses = Course::where('status', 'Active')
                ->whereNull('deleted_at')
                ->orderBy('course_title', 'asc')
                ->get(['id', 'course_title', 'url_title']);

            return view('Course::support-pages.create', compact('courses', 'data'));
        } catch (\Exception $e) {
            return redirect()->route('course-support-page-list')->with('error', 'Invalid Support Page ID.');
        }
    }

    /**
     * Store or Update Support Page
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:tbl_courses,id',
            'type' => 'required|string|in:syllabus,exam-format,exam,eligibility,study-plan,corporate-training,practice-test,faqs,certification-benefits,certification-guide,notes',
            'slug' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'status' => 'required|string|in:published,draft',
            'content' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
        ]);

        try {
            [$userId, $companyId] = Controller::loginandcompany();

            $id = $request->filled('id') ? decrypt($request->input('id')) : null;
            $slug = $request->filled('slug') ? Str::slug($request->input('slug')) : null;

            $attributes = [
                'course_id' => $request->course_id,
                'type' => $request->type,
            ];

            // If updating an existing record by ID
            if ($id) {
                $page = CourseSupportPage::findOrFail($id);
                $page->update([
                    'course_id' => $request->course_id,
                    'type' => $request->type,
                    'slug' => $slug,
                    'title' => $request->title,
                    'content' => $request->content,
                    'status' => $request->status,
                    'seo_title' => $request->seo_title,
                    'seo_description' => $request->seo_description,
                    'canonical_url' => $request->canonical_url,
                    'is_indexable' => $request->boolean('is_indexable', true),
                    'updated_by' => $userId,
                ]);

                return redirect()->route('course-support-page-list')->with('success', 'Support page updated successfully.');
            }

            // Create or update unique [course_id, type]
            $page = CourseSupportPage::updateOrCreate(
                $attributes,
                [
                    'slug' => $slug,
                    'title' => $request->title,
                    'content' => $request->content,
                    'status' => $request->status,
                    'seo_title' => $request->seo_title,
                    'seo_description' => $request->seo_description,
                    'canonical_url' => $request->canonical_url,
                    'is_indexable' => $request->boolean('is_indexable', true),
                    'company_id' => $companyId,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );

            return redirect()->route('course-support-page-list')->with('success', 'Support page saved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving support page: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Soft delete Support Page
     */
    public function destroy($id)
    {
        try {
            $decId = decrypt($id);
            $page = CourseSupportPage::findOrFail($decId);
            $page->delete();

            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => 'Support page deleted successfully.']);
            }

            return redirect()->route('course-support-page-list')->with('success', 'Support page deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Quick Toggle Status (published <-> draft)
     */
    public function toggleStatus(Request $request)
    {
        try {
            $decId = decrypt($request->input('id'));
            $page = CourseSupportPage::findOrFail($decId);

            $page->status = ($page->status === 'published') ? 'draft' : 'published';
            $page->save();

            return response()->json([
                'status' => true,
                'message' => 'Status changed to ' . ucfirst($page->status) . ' successfully.',
                'new_status' => $page->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
