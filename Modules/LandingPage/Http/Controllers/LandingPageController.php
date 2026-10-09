<?php

namespace Modules\LandingPage\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Models\SectionDetail;
use Modules\LandingPage\Services\LandingPageService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Modules\Website\Models\Pages;

class LandingPageController extends Controller
{
    protected LandingPageService $service;

    public function __construct(LandingPageService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        $sections = SectionDetail::get();
        return view('landingpage::index', compact('sections'));
    }

    protected function create(Request $request)
    {
        $sections = SectionDetail::get();
        $pages = Pages::get();
        return view('landingpage::create', compact('sections', 'pages'));
    }


    /**
     * Store the form data
     */
    public function storeSection(Request $request, $id = null)
    {
        // dd($request->all());
        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'heading' => 'required|string|max:255',
            'description' => 'required',
            'page_id'=>'nullable',
        ]);

        $imageFields = [
            'image_one',
            'image_two',
            'image_three',
            'image_four',
            'image_five'
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $image = $request->file($field);
                $destinationPath = public_path('section');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $fileName = uniqid($field . '_') . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $fileName);
                $validated[$field] = 'section/' . $fileName;
            }
        }
        if ($id) {
            $id = decrypt($id);
            //dd($validated);
            $section = SectionDetail::findOrFail($id);
            $section?->update($validated);
             return redirect()->back()->with('success', 'Section updated successfully!');
            $message = 'Section updated successfully!';
        } else {
            SectionDetail::create($validated);
             return redirect()->back()->with('success', 'Section created successfully!');
        }

        return redirect()->route('landingpage.index');
    }


    public function getSectionData(Request $request)
    {
        $query = SectionDetail::select([
            'id',
            'section',
            'label',
            'heading',
            'description',
            'button_label',
            'image_one',
            'image_two',
            'image_three',
            'image_four',
            'image_five',
            'label_one',
            'label_two',
            'label_three',
            'label_four',
            'label_five',
            'created_at',
            'updated_at'
        ]);

        return DataTables::of($query)

            ->addColumn('images', function ($row) {
                $images = [
                    $row->image_one,
                    $row->image_two,
                    $row->image_three,
                    $row->image_four,
                    $row->image_five
                ];

                $html = '';
                foreach ($images as $img) {
                    if ($img) {
                        $url = asset($img); // Full URL to the image
                        $html .= '<a href="' . $url . '" target="_blank">';
                        $html .= '<img src="' . $url . '" style="width:50px; height:50px; margin-right:5px; object-fit:cover; border:1px solid #ccc; padding:2px;" />';
                        $html .= '</a>';
                    }
                }
                return $html;

            })->editColumn('created_at', function ($row) {
                return date_formate($row->created_at);
            })
            ->addColumn('description', function ($row) {
                        return  Str::limit($row->description ?? 'N/A', 50);
                    })
            ->addColumn('actions', function ($row) {
                $incr = encrypt($row?->id);
                $delete_url = route('section.delete', ['id' => $incr]);
                $edit_url = route('section.edit', ['id' => $incr]);

                $dropdown = '<div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">';


                // $dropdown .= '<li><a href="' . $delete_url . '" class="block px-6 py-2 hover:bg-gray-100 text-danger"><i class="ri-delete-bin-line mr-2"></i>Delete</a></li>';
                $dropdown .= '<li><a href="' . $edit_url . '" class="block px-6 py-2 hover:bg-gray-100 "><i class="ri-edit-line mr-2"></i>Edit</a></li>';


                $dropdown .= '</ul></div></div>';
                return $dropdown;
            })
            ->rawColumns(['images', 'description','actions'])
            ->make(true);
    }
    public function deleteSection($id)
    {
        $id = decrypt($id);
        $section = SectionDetail::findOrFail($id);

        $imageFields = [
            'image_one',
            'image_two',
            'image_three',
            'image_four',
            'image_five'
        ];

        foreach ($imageFields as $field) {
            if (!empty($section->$field)) {
                $imagePath = public_path($section->$field);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        $section->delete();

        return redirect()->back()->with('success', 'Section deleted successfully!');
    }

    public function editSection($id)
    {
        $id = decrypt($id);
        $pages = Pages::get();
        $section = SectionDetail::findOrFail($id);
        return view('landingpage::edit', compact('section','pages'));
    }

}
