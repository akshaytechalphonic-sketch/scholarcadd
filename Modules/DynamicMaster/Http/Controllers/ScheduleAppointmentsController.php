<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\DynamicMaster\Models\ScheduleAppointment;
use Modules\DynamicMaster\Services\ScheduleAppointmentsService;

class ScheduleAppointmentsController extends Controller
{
    protected ScheduleAppointmentsService $service;

    public function __construct(ScheduleAppointmentsService $service)
    {
        $this->service = $service;
    }

    protected function AppointmentsList(Request $request)
    {

        return $this->service->AppointmentsList($request);

    }
    protected function Appointmentview(Request $request)
    {
        return $this->service->Appointmentview($request);
    }
    protected function AppointmentContactStatus (Request $request)
    {
        return $this->service->AppointmentContactStatus($request);
    }
}

