<?php

namespace App\Models;

use App\Enums\ChapterPinEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'pin',
        'name',
        'content',
        'story_id',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function getPinNameAttribute()
    {
        return ChapterPinEnum::getNameByValue($this->pin);
    }
}
