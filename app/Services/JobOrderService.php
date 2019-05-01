<?php

namespace App\Services;

use App\Models\JobOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JobOrderService
{
    /**
     * Create job order
     *
     * @param Request $request
     * @return JobOrder
     */
    public function save(Request $request)
    {
        $jobData = array_merge([
            'date' => date('Y-m-d', strtotime($request->date)),
            'job_order_number' => $this->generateJobNumber()
        ], $request->only(
            'employee_id',
            'cost_center_id',
            'ext',
            'requested_through_type',
            'job_description',
            'status',
            'remark',
            'location_id',
            'time_start',
            'time_end'
        ));

        $jobOrder = JobOrder::create($jobData);

        // $this-
        return $jobOrder;
    }

    
    /**
     * Generate unique job number
     *
     * @return string
     */
    public function generateJobNumber()
    {
        return (1000) + optional(JobOrder::latest()->first())->id;
    }


    /**
     * Sync technicians to job order
     *
     * @param JobOrder $jobOrder
     * @param array $techinians
     * @return void
     */
    public function addTechniciansTo(JobOrder $jobOrder, $techinians)
    {
        foreach ($techinians as &$tech) {
            unset($tech['employee']);

            if($tech['time_start']) $tech['time_start'] = Carbon::parse($tech['time_start']);
            if($tech['time_end']) $tech['time_end'] = Carbon::parse($tech['time_end']);
        }

        return $jobOrder->technicians()->sync($techinians);
    }

    /**
     *
     * @param $id Job order id.
     */
    public function streamPdf($id)
    {

    }
}
