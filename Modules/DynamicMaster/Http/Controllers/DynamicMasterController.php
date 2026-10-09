<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\DynamicMasterService;
use Modules\DynamicMaster\Http\Requests\DynamicMasterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnArgument;

class DynamicMasterController extends Controller
{
    protected DynamicMasterService $service;

    public function __construct(DynamicMasterService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function get_parent_list(Request $request)
    {
        return $this->service->get_parent_list($request);
    }

    protected function edit(Request $request)
    {
        return $this->service->edit($request);
    }

    protected function store(DynamicMasterRequest $request)
    {
        return $this->service->store($request);
    }

    protected function update(Request $request)
    {
        return $this->service->update($request);
    }

    protected function delete(DynamicMasterRequest $request)
    {
        return $this->service->delete($request);
    }


    protected function child_index(Request $request)
    {
        return $this->service->child_all($request);
    }
    protected function switch_company(Request $request)
    {
        return $this->service->switch_company($request);
    }

    protected function secure_file(Request $request)
    {
        $filePath = urldecode($request->path);
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return response()->file(storage_path("app/public/{$filePath}"));
        }
        abort(404, 'File not found');
    }
    protected function get_child(Request $request)
    {
        return $this->service->get_child($request);
    }

    protected function sql_create(Request $request)
    {
        return view('dynamicmaster::sql');
    }
    protected function sql_store(Request $request)
    {
        try {
            DB::statement($request->sql_command);

            return back()->with('success', "Done");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function planCreate()
    {
        return view('');
    }





}
