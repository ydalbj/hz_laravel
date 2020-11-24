<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, DateTrait;

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
