<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Get all students data.
     *
     * @param 
     *
     * @return response
     */
    public function getStudents()
    {
        return response(Student::all(), 200);
    }

    /**
     * Finds a student using entered id.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function getStudent($id)
    {
        return response(Student::find($id), 200);
    }

    /**
     * Creates a new Student.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function addStudent(Request $request)
    {
        $data = $request->json()->all();
        $students = new Student();
        $students->fname = $data['fname'];
        $students->mname = $data['mname'];
        $students->lname = $data['lname'];
        $students->class = $data['class'];
        $students->college = $data['college'];
        $students->save();
        if ($students->save()) {
            return response("Student created successfully!", 201);
        } else {
            return response("Could not create student!", 404);
        }
    }

    /**
     * Creates new Student using Fillable.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return response
     */
    public function addStudentFillable(Request $request)
    {
        $data = $request->json()->all();
        Student::create([
            'fname' => $data['fname'],
            'mname' => $data['fname'],
            'lname' => $data['fname'],
            'class' => $data['class'],
            'college' => $data['college']
        ]);
        return response("Student created successfully!", 201);
    }

    /**
     * Updates a Student.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function updateStudent(Request $request, $id)
    {
        $data = $request->json()->all();
        $students = Student::find($id);
        if ($students) {
            $students->fname = $data['fname'];
            $students->mname = $data['mname'];
            $students->lname = $data['lname'];
            $students->class = $data['class'];
            $students->college = $data['college'];
            if ($students->save()) {
                return response("Student updated successfully!", 201);
            } else {
                return response("Could not update student!", 404);
            }
        }
    }

    /**
     * Updates a Student using Fillable.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function updateStudentFillable(Request $request, $id)
    {
        $data = $request->json()->all();
        if (Student::find($id)) {
            Student::find($id)->update([
                'fname' => $data['fname'],
                'mname' => $data['fname'],
                'lname' => $data['fname'],
                'class' => $data['class'],
                'college' => $data['college']
            ]);
            return response("Student updated successfully!", 201);
        } else {
            return response("Could not update student!", 404);
        }
    }

    /**
     * Deletes a student.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function deleteStudent($id)
    {
        if (Student::find($id)) {
            Student::find($id)->delete();
            return response("Student deleted successfully!", 200);
        } else {
            return response("Could not delete student!", 404);
        }
    }

    /**
     * Deletes a student using destroy method.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function deleteMultipleStudents($id)
    {
        if (Student::find($id)) {
            Student::destroy($id);
            // Student::destroy([$id, 1, 2, 3, 4]);
            return response("Student deleted successfully!", 200);
        } else {
            return response("Could not delete student!", 404);
        }
    }

    /**
     * Deletes all students.
     *
     * @param 
     *
     * @return response
     */
    public function deleteAllStudents()
    {
        foreach (Student::get(['id']) as $id)
            $ids[] = $id['id'];
        
        Student::destroy($ids);
        return response("All students deleted successfully!", 200);
    }

    /**
     * Finds all students which are soft deleted.
     *
     * @param $id  App\Models\Student id
     *
     * @return response
     */
    public function getSoftDeletedStudent($id)
    {
        // $students = Student::onlyTrashed()->find($id);
        return response(Student::onlyTrashed()->get(), 200);
    }

    /**
     * Restores all students which are soft deleted.
     *
     * @param $id  App\Models\Student id
     *
     * @return response
     */
    public function restoreSoftDeletedStudent($id)
    {
        if (Student::onlyTrashed()->restore()) {
            return response("Soft deleted Students Restored!", 200);
        } else {
            return response("Nothing to restore!", 404);
        }
    }

    /**
     * Deletes all students permanently which are soft deleted.
     *
     * @param $id  App\Models\Student id
     *
     * @return response
     */
    public function forceDeleteSoftDeletedStudent($id)
    {
        if (Student::onlyTrashed()->forceDelete()) {
            return response("Soft deleted Students permanently removed!", 200);
        } else {
            return response("Nothing to remove!", 404);
        }
    }
}
