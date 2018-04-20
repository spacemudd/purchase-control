<?php

return [

    'mssql_db' => env('SPACELANTERN_MSSQL_DB', 'ITAM'),

    'mssql_user' => env('SPACELANTERN_MSSQL_USER', 'sa'),

    'mssql_pass' => env('SPACELANTERN_MSSQL_PASS', 'vagrant'),

    'mssql_port' => env('SPACELANTERN_MSSQL_PORT', 1433),

    /**
     * The folder to save the exported the Sqlite file in.
     * The folder will be located under 'storage'.
     *
     */
    'export_location' => env('SPACELANTERN_EXPORT_folder', 'export_db'),

];
