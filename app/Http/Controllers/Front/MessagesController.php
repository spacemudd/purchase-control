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

namespace App\Http\Controllers\Front;

use App\Models\InboxMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    public function show($id)
    {
        $message = InboxMessage::owned()->where('id', $id)->firstOrFail();
        $message->seen_at = Carbon::now();
        $message->save();

        return view('messages.show', compact('message'));
    }
}
