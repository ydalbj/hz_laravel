<?php

namespace App\Admin\Repositories;

use App\Models\Answer as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Answer extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
