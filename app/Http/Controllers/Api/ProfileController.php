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

namespace App\Http\Controllers\Api;

use App\Models\InboxMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Returns the list of messages of the
     * logged in user.
     *
     */
    public function inbox()
    {
        return InboxMessage::owned()->latest()->take(15)->get();
    }

    public function unreadMessagesCounts()
    {
        return InboxMessage::owned()->unread()->count();
    }

    public function clearUnreadMessagesCounts()
    {
        return InboxMessage::owned()->unread()->update([
            'seen_at' => Carbon::now(),
        ]);
    }
}
