<?php

namespace App\Console\Commands\Copying;

use App\Models\Department;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyDepartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clarimount:copy-departments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy departments from Clarimount ITAM system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::connection('sqlsrv_itam')
            ->table('departments')
            ->orderBy('id')
            ->chunk(1000, function($departments) {
                $toInsert = [];
                foreach($departments as $department) {
                    $toInsert[] = [
                        'code' => $department->code,
                        'description' => $department->description,
                        'head_department' => $department->head_department,
                    ];
                }

                Department::insert($toInsert);
            });
    }
}
