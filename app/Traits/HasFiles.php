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

namespace App\Traits;

use App\Models\Media;

trait HasFiles
{
    public function files()
    {
        return $this->morphMany(Media::class, 'model');
    }
}
