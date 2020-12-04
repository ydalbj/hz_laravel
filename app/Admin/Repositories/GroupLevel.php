<?php

namespace App\Admin\Repositories;

use App\Models\GroupLevel as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class GroupLevel extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
