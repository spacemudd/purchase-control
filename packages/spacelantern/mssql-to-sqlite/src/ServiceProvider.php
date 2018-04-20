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

namespace Spacelantern\MssqlToSqlite;

use Spacelantern\MssqlToSqlite\Commands\Copying\CopyEverything;
use Spacelantern\MssqlToSqlite\Commands\ProcessAssetIssuances;
use Spacelantern\MssqlToSqlite\Commands\ProcessAssetMovement;
use Spacelantern\MssqlToSqlite\Commands\ProcessAssets;
use Spacelantern\MssqlToSqlite\Commands\ProcessAssetTemplates;
use Spacelantern\MssqlToSqlite\Commands\ProcessDepartmentLocations;
use Spacelantern\MssqlToSqlite\Commands\ProcessDepartments;
use Spacelantern\MssqlToSqlite\Commands\ProcessEmployees;
use Spacelantern\MssqlToSqlite\Commands\ProcessEverything;
use Spacelantern\MssqlToSqlite\Commands\ProcessManufacturers;
use Spacelantern\MssqlToSqlite\Commands\ProcessPurchaseOrdersItems;
use Spacelantern\MssqlToSqlite\Commands\ProcessRegions;
use Spacelantern\MssqlToSqlite\Commands\ProcessStaffTypes;
use Spacelantern\MssqlToSqlite\Commands\ProcessVendors;
use Spacelantern\MssqlToSqlite\Commands\ProcessPurchaseOrders;
use Spacelantern\MssqlToSqlite\Commands\ProcessCustody;
use Symfony\Component\Process\Process;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/mssqltosqlite.php';

        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            ProcessEverything::class,
            ProcessStaffTypes::class,
            ProcessRegions::class,
            ProcessDepartmentLocations::class,
            ProcessDepartments::class,
            ProcessEmployees::class,
            ProcessVendors::class,
            ProcessManufacturers::class,
            ProcessAssetTemplates::class,
            ProcessPurchaseOrders::class,
            ProcessAssets::class,
            ProcessAssetIssuances::class,
            ProcessAssetMovement::class,
            CopyEverything::class,
            ProcessCustody::class,
        ]);
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('mssqltosqlite.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('mssqltosqlite.php')], 'config');
    }
}
