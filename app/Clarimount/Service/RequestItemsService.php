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

use App\Models\Item;
use App\Models\ItemTemplate;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Support\Facades\Validator;
use App\Clarimount\Repository\RequestItemRepository;

class RequestItemsService
{
    protected $repo;

    public function __construct(RequestItemRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store($data)
    {
        $this->validate($data)->validate();

        $data['currency'] = 'SAR';

        $unitPrice = Money::of($data['unit_price'], $data['currency']);
        $total = $unitPrice->multipliedBy($data['qty'], RoundingMode::HALF_UP);
        $data['unit_price_minor'] = $unitPrice->getMinorAmount()->toInt();
        $data['total_minor'] = $total->getMinorAmount()->toInt();

        // Create an item template if it doesnt exist.
        if( ! $data['item_id'] ) {
            $newItem = $data;
            $newItem['code'] = $data['name'];
            $newItem['default_unit_price'] = $data['unit_price_minor'];

            $data['item_id'] = $this->createItemTemplateOnFly($newItem)
                                    ->id;
        }

        return $this->repo->store($data);
    }

    /**
     *
     * @param array $data
     * @return \App\Models\Item;
     */
    public function createItemTemplateOnFly(array $data)
    {
        return Item::create($data);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'item_id' => 'nullable|exists:items,id',
            'name' => 'required|string|max:255,',
            'qty' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:1',
        ]);
    }

    /**
     * Deletes a request item.
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        // TODO: Check if the request is in draft mode... etc.

        return $this->repo->delete($id);
    }

}
