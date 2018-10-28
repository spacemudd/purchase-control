<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            ->with('cost_center')
            ->paginate(10);

        return $results;
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'cost_center_id' => 'nullable|exists:departments,id',
        ]);

        $newProject = request()->except('_token');

        return Project::create($newProject);
    }
}
