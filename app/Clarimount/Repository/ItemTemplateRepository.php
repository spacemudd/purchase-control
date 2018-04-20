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

namespace App\Clarimount\Repository;

use App\Models\ItemTemplate;

class ItemTemplateRepository
{
    public function store(array $request)
    {
        $assetTemplate = new ItemTemplate();

        $assetTemplate->code = $request['code'];
        $assetTemplate->manufacturer_id = $request['manufacturer_id'];
        $assetTemplate->type_id = $request['type_id'];
        $assetTemplate->category_id = $request['category_id'];
        $assetTemplate->unit_price = $request['unit_price'];
        $assetTemplate->description = $request['description'];
        $assetTemplate->details = $request['details'];

        $assetTemplate->save();

        return $assetTemplate;
    }
}
