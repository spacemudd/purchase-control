<?php
/**
 * FIRSTSOLUTIONS / CLARIMOUNT CONFIDENTIAL
 * __________________
 *
 * 2017 FirstSolutions Association (https://firstsolu.com)
 * All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of FirstSolutions Association and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to FirstSolutions Associations
 * and its suppliers and may be covered by U.S. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from FirstSolutions Association.
 */

namespace Spacelantern\MssqlToSqlite\Commands;

use App\Models\Item;
use App\Models\AssetStatus;
use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Employee;
use App\Models\StaffType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ProcessCustody extends Command
{
    protected $signature = 'spacelantern:process-custody';

    protected $description = 'Process all custody';

    public $counter;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->counter = 0;

        $this->process();

        $this->info('');

        $this->info('Assets in custody: Completed');
    }

    public function process()
    {
        DB::disableQueryLog();

        /**
         * Loop through all the assets marked 'Outside'
         */
        $assetStatus = AssetStatus::where('code', 'out')->get();
        $assetStatusIds = [];
        foreach($assetStatus as $status) {
            $assetStatusIds[] = $status->id;
        }

        Item::whereIn('asset_status_id', $assetStatusIds)
            ->chunk(50, function($assets) {
                foreach($assets as $asset) {
                    $this->info('Processing asset: ' . ++$this->counter);

                    // Find the latest issuance.
                    $employee = $asset->issuance_item->issuance->employee;

                    // Add it to the employee.
                    $employee->assets_in_custody()->attach($asset);
                }
        });
    }
}
