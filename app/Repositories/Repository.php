<?php

namespace App\Repositories;

class Repository
{
    // model property on class instances
    protected $model;

    // Get all instances of model
    public function getAllRecords()
    {
        return $this->model->all();
    }

    // show the record with the given id
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }
}
