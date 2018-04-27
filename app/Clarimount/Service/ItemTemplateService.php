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

namespace App\Clarimount\Service;

use App\Clarimount\Repository\ItemTemplateRepository;
use Illuminate\Support\Facades\Validator;

class ItemTemplateService
{
    protected $repository;

    public function __construct(ItemTemplateRepository $repository)
    {
        $this->repository = $repository;
    }
}
