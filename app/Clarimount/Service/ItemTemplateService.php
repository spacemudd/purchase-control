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
use App\Models\ItemTemplate;
use Brick\Money\Money;
use Illuminate\Support\Facades\Validator;

class ItemTemplateService
{
    protected $repository;

    public function __construct(ItemTemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generateNumber(string $code)
    {
        $strippedName = preg_replace('/\s+/', '', $data['name']);
        $nameCode = mb_substr($strippedName, 0, 3);

        // Add to the maxnumber string.
        $maxNumberName = $nameCode;

        $category = Category::where('id', $data['category_id'])->first();
        if($category) {
            $strippedCategoryName = preg_replace('/\s+/', '', $category->name);
            $categoryCode = mb_substr($strippedCategoryName, 0, 3);

            // Add to the maxnumber string.
            $maxNumberName .= '-' . $categoryCode;
        }

        $maxNumber = MaxNumber::firstOrCreate([
            'name' => $maxNumberName,
        ], [
            'name' => $maxNumberName,
            'value' => '1001',
        ]);

        return $maxNumber;
    }

    public function store(array $data)
    {
        $this->validate($data)->validate();

        if($data['unit_price']) {
            $data['unit_price'] = Money::of($data['unit_price'], 'SAR')
                ->getMinorAmount()
                ->toInt();
        }

        return ItemTemplate::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'model_number' => $data['model_number'],
            'manufacturer_id' => $data['manufacturer_id'],
            'eol' => $data['eol'],
            'default_unit_price_minor' => $data['unit_price'],
        ]);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:item_templates',
            'model_number' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'eol' => 'nullable|numeric',
            'unit_price' => 'nullable|numeric|min:0',
        ]);
    }
}
