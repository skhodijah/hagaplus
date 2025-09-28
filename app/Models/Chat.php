<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chat extends Model
{
    protected $fillable = ['type', 'name'];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Core\User::class, 'chat_participants')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    public function getOtherParticipantAttribute()
    {
        return $this->participants()->where('user_id', '!=', auth()->id())->first();
    }
}
