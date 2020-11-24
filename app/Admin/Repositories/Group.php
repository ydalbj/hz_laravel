<?php

namespace App\Admin\Repositories;

use App\Models\Group as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Group extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;

    public function getGroupsBySubjectId(int $subject_id)
    {
        $groups = Model::where('subject_id', $subject_id)->pluck('title', 'id')->toArray();
        $groups = array_merge([0 => '无分组'], $groups);
        return $groups;
    }
}
