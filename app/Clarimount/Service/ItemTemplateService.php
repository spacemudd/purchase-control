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

    public function store()
    {
        $request = request()->except('_token');

        $this->validate($request)->validate();

        return $this->repository->store($request);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'code' => 'required|unique:asset_templates|string|min:1|max:255',
            'active' => 'required|boolean',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'category_id' => 'required|exists:asset_categories,id',
            'description' => 'required|string|min:1|max:255',
            'details' => 'required|string|max:500',
            'type_id' => 'required|exists:asset_types,id',
            'unit_price' => 'required|numeric',
        ]);

    }
}
