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

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaRepository
{
    protected $model;

    public function __construct(Media $model)
    {
        $this->model = $model;
    }

    public function create($request)
    {
        $file = $request->file('attachment');
        $fileName = $file->getClientOriginalName();
        $path = storage_path('app/public/' . $request->resource_name . '/' . $request->resource_id . '/');

        $file->move($path, $fileName);

        $media = new Media();
        $media->model_id = $request->resource_id;
        $media->model_type = $this->getFullModelName($request);
        $media->collection_name = 'Uploads';
        $media->purpose = 'Uploads';
        $media->file_name = $fileName;
        $media->file_path = $path;
        $media->size = filesize($path . $fileName);
        $media->disk = 'local';
        $media->order = 1;
        $media->save();

        return $media;
    }

    public function getFullModelName(Request $request)
    {
        switch ($request->resource_name) {
            case 'purchase-orders':
                return 'App\Models\PurchaseOrder';
                break;
            case 'asset-issuances':
                return 'App\Models\AssetIssuance';
                break;
            case 'asset-issuances-returns':
                return 'App\Models\AssetIssuanceReturn';
                break;

            default: {
                throw new \Exception('Unknown resource name');
            }
        }
    }

    /**
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Exception
     */
    public function download($id)
    {
        $attachment = Media::where('id', $id)->firstOrFail();

        $exists = Storage::disk('local')->exists($attachment->file_path);

        if(!$exists) {
            throw new \Exception('File not found');
        }

        return Storage::disk('local')->download($attachment->file_path, $attachment->file_name);
    }

    /**
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        // We are specifically not deleting the files on the server in case of audit.
        return Media::where('id', $id)->delete();
    }
}
