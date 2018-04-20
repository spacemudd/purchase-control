<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Clarimount\Service\PermissionService;

class PermissionsController extends Controller
{

	protected $service;

	public function __construct(PermissionService $service)
	{
		$this->service = $service;
	}

	/**
	 * Retrieves a list of the resources' name and their actions.
	 * 
	 * @return array
	 */
    public function list()
    {
    	return $this->service->allGroupByResource();
    }
}
