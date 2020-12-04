<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	use HasDateTimeFormatter;

	public function subject()
	{
		return $this->belongsTo(Subject::class);
	}

	public function questions()
	{
		return $this->hasMany(Qeustion::class);
	}

	public function levels()
	{
		return $this->hasMany(GroupLevel::class);
	}
}
