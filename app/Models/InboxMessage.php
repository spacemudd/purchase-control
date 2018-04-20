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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxMessage extends Model
{
    protected $appends = ['link'];

    protected $fillable = ['recipient_id', 'type', 'content'];

    public function scopeOwned($q)
    {
        return $q->where('recipient_id', auth()->user()->id);
    }

    public function scopeUnread($q)
    {
        return $q->where('seen_at', null);
    }

    public function getLinkAttribute()
    {
        return route('profile.inbox.messages.show', ['id' => $this->id]);
    }
}
