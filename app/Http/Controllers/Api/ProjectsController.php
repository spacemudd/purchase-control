<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{
    /**
     * @return mixed
     */
    public function search()
    {
        $search = request()->input('q');

        $results = Project::where('location', 'LIKE', '%' . $search . '%')
            ->orWhereHas('cost_center', function($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%');
            })
            ->paginate(10);

        return $results;
    }
}
