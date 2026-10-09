<?php

namespace Modules\DynamicMaster\Services;

use Modules\DynamicMaster\Repositories\LogActivityRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LogActivityService extends Controller
{
    protected LogActivityRepository $repository;

    public function __construct(LogActivityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(Request $request)
    {
        try {
            $list = $this->repository->all($request);
            if($list && !empty($list['data']))
            {
                return response()->json(['data' => $list['data'], 'message' => __($list['message'])]);
            }
            else{
                return response()->json(['data' => null, 'message' => __($list['message'])]);
            }
        } catch (\Exception $e) {
            catch_log($e,"Log Listing");
            return response()->json(['data' => null, 'message' => $e->getMessage()], 500);
        }
    }

    public function comment_list(Request $request)
    {
        try {
            $list = $this->repository->comment_list($request);
           if($list && !empty($list['data']))
            {
                return response()->json(['data' => $list['data'], 'message' => __($list['message'])]);
            }
            else{
                return response()->json(['data' => null, 'message' => __($list['message'])]);
            }
        } catch (\Exception $e) {
            catch_log($e,"Log Listing");
            return response()->json(['data' => null, 'message' => $e->getMessage()], 500);
        }
    }

    public function comment_store(Request $request)
    {
        try {
            $store = $this->repository->comment_store($request);
            if($store && !empty($store['data']))
            {
                return response()->json(['data' => $store['data'], 'message' => __($store['message'])]);
            }
            else{
                return response()->json(['data' => null, 'message' => __($store['message'])]);
            }
        } catch (\Exception $e) {
            catch_log($e,"Comment Listing");
            return response()->json(['data' => null, 'message' => $e->getMessage()], 500);
        }
    }

}
