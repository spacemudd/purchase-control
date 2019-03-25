<?php

namespace App\Services;

use App\Models\Location;
use App\Models\MaterialRequest;
use Carbon\Carbon;

class MaterialRequestService
{
    public function save(array $request)
    {
        $date = Carbon::createFromFormat('Y-d-m', $request['date']);
        $location = Location::where('id', $request['location_id'])->first();

        $request['number'] = $date->format('d-m-Y').' - '.$location->name;
        $request['date'] = $date;
        $request['status'] = MaterialRequest::PENDING; // Default status.
        $request['created_by_id'] = auth()->user()->id;

        if ($mRequestExists = MaterialRequest::where('number', $request['number'])->first()) {
            throw new \Exception('Material request number: "'.$mRequestExists->number.'" already exists!');
        }

        $mRequest =  MaterialRequest::create($request);

        return $mRequest;
    }

    /**
     *
     * @param string $date
     * @param int $locatio_id
     * @return bool|string
     */
    public function checkNumberExists(string $date, int $locatio_id)
    {
        $date = Carbon::createFromFormat('Y-d-m', $date);
        $location = Location::where('id', $locatio_id)->first();
        $request['number'] = $date->format('d-m-Y').' - '.$location->name;

        return MaterialRequest::where('number', $request['number'])->exists();
    }
}
