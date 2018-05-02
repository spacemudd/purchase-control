<?php

namespace App\Clarimount\Service;

use App\Models\Employee;

class ApproversService
{
    protected $model;

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->approvers()->get();
    }
}
