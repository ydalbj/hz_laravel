<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Answer extends Model
{
    use HasFactory, DateTrait;

    public function Question()
    {
        return $this->belongsTo(Question::class);
    }
}
