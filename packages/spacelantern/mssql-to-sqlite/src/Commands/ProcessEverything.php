<?php
/**
 * FIRSTSOLUTIONS / CLARIMOUNT CONFIDENTIAL
 * __________________
 *
 * 2018 FirstSolutions Association (https://firstsolu.com)
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

use Illuminate\Console\Command;
use App\Models\StaffType;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class ProcessEverything extends Command
{
    protected $signature = 'spacelantern:process-everything';

    protected $description = 'List the Microsoft SQL database tables';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->call('spacelantern:process-staff-types');
        $this->call('spacelantern:process-regions');
        $this->call('spacelantern:process-department-locations');
        $this->call('spacelantern:process-departments');
        $this->call('spacelantern:process-employees');
        $this->call('spacelantern:process-vendors');
        $this->call('spacelantern:process-manufacturers');
        $this->call('spacelantern:process-asset-templates');
        $this->call('spacelantern:process-purchase-orders');
        $this->call('spacelantern:process-assets');
        $this->call('spacelantern:process-asset-issuances');
        $this->call('spacelantern:process-asset-movement');
    }
}
