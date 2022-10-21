<?php

namespace App\Models;

use App\Models\User;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory,UUID;

    protected $fillable = ['title','description'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
