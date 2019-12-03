<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository extends Repository
{
    // Constructor to bind model to repo
    public function __construct(Student $student)
    {
        $this->model = $student;
    }

    public function getOnlyTrashed()
    {
        return $this->model->onlyTrashed()->get();
    }
}
