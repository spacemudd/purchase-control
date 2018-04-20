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

use App\Clarimount\Repository\MediaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaService
{
    protected $repository;

    public function __construct(MediaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_name' => 'required|string|max:100',
            'resource_id' => 'required|integer',
            'attachment' => 'required',
            //'reason' => 'required|string', TODO.
        ]);

        return $this->repository->create($request);
    }

    public function download(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:media,id',
        ]);

        return $this->repository->download($request->id);
    }
}
