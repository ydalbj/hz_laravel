<?php

namespace App\Admin\Repositories;

use App\Models\Result as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Result extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
