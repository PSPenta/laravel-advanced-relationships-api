<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Repositories\StudentRepository;
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
    public function getStudents(StudentRepository $studentRepository)
    {
        return response()->json($studentRepository->getAllRecords(), 200);
    }

    /**
     * Finds a student using entered id.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function getStudent(StudentRepository $studentRepository, $id)
    {
        return response()->json($studentRepository->findById($id), 200);
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
        try {
            $data = $request->json()->all();
            $user = auth()->user();                 // Get logged in user using auth helper.
            // $user = Auth::user();                // Get logged in user using Auth facade.
            $students = new Student();
            $students->fname = $data['fname'];
            $students->mname = $data['mname'];
            $students->lname = $data['lname'];
            $students->class = $data['class'];
            $students->college = $data['college'];
            $students->user_id = $user->id;
            $students->save();
            if ($students->save()) {
                return response()->json(["success" => "Student created successfully!"], 201);
            } else {
                return response()->json(["error" => "Could not create student!"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["error" => "Internal server error!"], 500);
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
        return response()->json(["success" => "Student created successfully!"], 201);
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
                return response()->json(["success" => "Student updated successfully!"], 200);
            } else {
                return response()->json(["error" => "Could not update student!"], 404);
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
            return response()->json(["success" => "Student updated successfully!"], 200);
        } else {
            return response()->json(["error" => "Could not update student!"], 404);
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
            return response()->json(["success" => "Student deleted successfully!"], 200);
        } else {
            return response()->json(["error" => "Could not delete student!"], 404);
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
            return response()->json(["success" => "Student deleted successfully!"], 200);
        } else {
            return response()->json(["error" => "Could not delete student!"], 404);
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
        return response()->json(["success" => "All students deleted successfully!"], 200);
    }

    /**
     * Finds all students which are soft deleted.
     *
     * @param $id  App\Models\Student id
     *
     * @return response
     */
    public function getSoftDeletedStudent(StudentRepository $studentRepository, $id)
    {
        // $students = Student::onlyTrashed()->find($id);
        return response()->json($studentRepository->getOnlyTrashed(), 200);
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
            return response()->json(["success" => "Soft deleted Students Restored!"], 200);
        } else {
            return response()->json(["error" => "Nothing to restore!"], 404);
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
            return response()->json(["success" => "Soft deleted Students permanently removed!"], 200);
        } else {
            return response()->json(["error" => "Nothing to remove!"], 404);
        }
    }
}
