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
use App\Models\Manufacturer;
use App\Models\MaxNumber;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        $itemTemplate = DB::transaction(function() use ($data) {

            $code = $data['code'] ?: $this
                ->saveNewCode($data['description'], $data['manufacturer_id'], $data['category_id']);

            $itemTemplate = ItemTemplate::create([
                'code' => $code,
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'model_details' => $data['model_details'],
                'manufacturer_id' => $data['manufacturer_id'],
                'default_unit_price_minor' => $data['unit_price'],
            ]);

            return $itemTemplate;
        });

        return $itemTemplate;
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'description' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:item_templates',
            'model_details' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'unit_price' => 'nullable|numeric|min:0',
        ]);
    }

    public function saveNewCode(string $description, $manufacturer_id, $category_id)
    {
        $maxNumber = MaxNumber::firstOrCreate([
            'name' => Str::words($description, 1),
        ], [
            'name' => Str::words($description, 1),
            'value' => '1001',
        ]);

        $maxNumber->value = ++$maxNumber->value;
        $maxNumber->save();

        return $maxNumber->value;
    }
}
