<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{

    /**
     * @var Model
     */
    protected Model $model;

    public function create(array $data)
    {
        $this->model::create($data);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        return $this->model::destroy($id);
    }
}