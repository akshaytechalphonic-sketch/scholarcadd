<?php

namespace Modules\Course\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Modules\Course\Repositories\PaymentRepository;

class PaymentService extends Controller
{
    protected PaymentRepository $repository;

    public function __construct(PaymentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function invoiceCreate($id, $view = null)
    {
        try {
            $data = $this->repository->invoiceCreate($id, $view);
            return $data;
        } catch (\Exception $e) {
            catch_log($e, "Viewing/creating/Editing invoice");
            return back()->with('error', $e->getMessage());
        }
    }
}
