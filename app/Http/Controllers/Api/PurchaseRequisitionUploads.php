<?php

namespace App\Http\Controllers\Api;

use App\Models\Media;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseRequisitionUploads extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:purchase_requisitions',
        ]);

        $uploads = PurchaseRequisition::findOrFail($request->id)->media()->with('user')->get();

        return $uploads;
    }

    /**
     * Saves files to the storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array Files saved
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:purchase_requisitions',
            'files' => 'required',
            'purpose' => 'nullable|string|max:255',
        ]);

        $savedFiles = DB::transaction(function() use ($request) {

            $requisition = PurchaseRequisition::where('id', $request->input('id'))->firstOrFail();

            $files = $request->file('files');

            $savedFiles = [];
            foreach($files as $file) {
                $fileName = $file->getClientOriginalName();

                $storedFile = Storage::disk('local')->putFile('requisitions', $file);

                $upload = Media::make([
                    'file_name' => $fileName,
                    'file_path' => $storedFile,
                    'size' => $file->getSize(),
                    'disk' => 'local',
                    'ext' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getClientMimeType(),
                    'purpose' => $request->purpose,
                    'collection_name' => 'Uploads',
                    'user_id' => auth()->user()->id,
                ]);

                $requisition->media()->save($upload);

                $savedFiles[] = $upload->load('user');
            }

            return $savedFiles;
        }, 2);

        return $savedFiles;
    }

    /**
     * Downloads the resource.
     *
     * @param $id of Media record.
     */
    public function download($id)
    {

    }
}
