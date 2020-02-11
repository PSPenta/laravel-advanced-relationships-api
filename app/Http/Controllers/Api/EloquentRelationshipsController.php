<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Photo, Role, Student, Subject, Tag, User, Video};
use Illuminate\Http\Request;

class EloquentRelationshipsController extends Controller
{
    /**
     * Get all students with their subjects, photos and tags included.
     *
     * @param 
     *
     * @return response
     */
    public function getStudentsWithAllData()
    {
        return response()->json(Student::with('subjects')->with('photos')->with('tags')->paginate(10), 200);
    }

    /**
     * Finds particular student using entered id.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function getSubjectOneToOne($id)
    {
        return (Student::find($id)) 
            ? response()->json(Student::find($id)->subject, 200)
            : response()->json(["error" => "No student found!"], 404);
    }

    /**
     * Finds particular student, creates new subject and assigns it to that user.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function setSubjectOneToOne(Request $request, $id)
    {
        $student = Student::find($id);
        if ($student) {
            $subject_data = $request->json()->all();
            $subject = new Subject();
            $subject->sub_name = $subject_data['sub_name'];
            $subject->total_marks = $subject_data['total_marks'];
            $subject->obtained_marks = $subject_data['obtained_marks'];
            if ($subject->save()) {
                return ($student->subject()->save($subject)) 
                    ? response()->json(["success" => "Subject assigned to user successfully!"], 200)
                    : response()->json(["error" => "Unable to assign subject to user!"], 404);
            } else {
                return response()->json(["error" => "Unable to save subject!"], 404);
            }
        } else {
            return response()->json(["error" => "No student found!"], 404);
        }
    }

    /**
     * Finds subject assigned to particular student and updates it.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function updateSubjectOneToOne(Request $request, $id)
    {
        $subject = Subject::whereStudentId($id)->first();
        if ($subject) {
            $subject_data = $request->json()->all();
            $subject->sub_name = $subject_data['sub_name'];
            $subject->total_marks = $subject_data['total_marks'];
            $subject->obtained_marks = $subject_data['obtained_marks'];
            return ($subject->save()) 
                ? response()->json(["success" => "Subject of user updated successfully!"], 200)
                : response()->json(["error" => "Unable to update subject!"], 404);
        } else {
            return response()->json(["error" => "No subject found!"], 404);
        }
    }

    /**
     * Finds subject assigned to particular student and deletes it.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function deleteSubjectOneToOne($id)
    {
        $student = Student::find($id);
        if ($student) {
            if ($student->subject) {
                return ($student->subject->delete()) 
                    ? response()->json(["success" => "Subject of user delete successfully!"], 200)
                    : response()->json(["error" => "Unable to delete subject!"], 404);
            } else {
                return response()->json(["error" => "No subject found!"], 404);
            }
        } else {
            return response()->json(["error" => "No student found!"], 404);
        }
    }

    /**
     * Returns student which has a particular subject assigned.
     *
     * @param int $id  App\Models\Subject id
     *
     * @return response
     */
    public function getStudentOneToOne($id)
    {
        return (Subject::find($id)) 
            ? response()->json(Subject::with('student')->find($id), 200)
            : response()->json(["error" => "No subject found!"], 404);
    }

    /**
     * Returns subjects assigned to particular student.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function getSubjectOneToMany($id)
    {
        return (Student::find($id)) 
            ? (count(Student::find($id)->subjects) 
                ? response()->json(Student::find($id)->subjects, 200)
                : response()->json(["error" => "No subjects found!"], 404)
            ) 
            : response()->json(["error" => "No student found!"], 404);
    }

    /**
     * Finds particular student, creates new subject and assigns it to that user.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function setSubjectOneToMany(Request $request, $id)
    {
        $student = Student::find($id);
        if ($student) {
            $subject_data = $request->json()->all();
            $subject = new Subject();
            $subject->sub_name = $subject_data['sub_name'];
            $subject->total_marks = $subject_data['total_marks'];
            $subject->obtained_marks = $subject_data['obtained_marks'];
            if ($subject->save()) {
                return ($student->subjects()->save($subject)) 
                    ? response()->json(["success" => "Subject assigned to user successfully!"], 200)
                    : response()->json(["error" => "Unable to assign subject to user!"], 404);
            } else {
                return response()->json(["error" => "Unable to save subject!"], 404);
            }
        } else {
            return response()->json(["error" => "No student found!"], 404);
        }
    }

    /**
     * Finds particular student and updates the first subject assigned to that user.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function updateSubjectOneToMany(Request $request, $id)
    {
        $student = Student::find($id);
        if ($student && $student->subjects) {
            $subject = $student->subjects->first();
            $subject_data = $request->json()->all();
            $subject->sub_name = $subject_data['sub_name'];
            $subject->total_marks = $subject_data['total_marks'];
            $subject->obtained_marks = $subject_data['obtained_marks'];
            return ($subject->save()) 
                ? response()->json(["success" => "Subject of user updated successfully!"], 200)
                : response()->json(["error" => "Unable to update subject!"], 404);
        } else {
            return response()->json(["error" => "No student or subjects found!"], 404);
        }
    }

    /**
     * Finds particular student and deletes all subjects assigned to that user.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function deleteSubjectOneToMany($id)
    {
        $student = Student::find($id);
        if ($student) {
            if (count($student->subjects)) {
                $count = 0;
                foreach ($student->subjects as $subject)
                    if ($subject->delete())
                        $count++;

                return ($count > 0) 
                    ? response()->json(["success" => "All subjects of current user deleted successfully!"], 200)
                    : response()->json(["error" => "Unable to delete subjects!"], 404);
            } else {
                return response()->json(["error" => "No subject found!"], 404);
            }
        } else {
            return response()->json(["error" => "No student found!"], 404);
        }
    }

    /**
     * Finds particular student and deletes all subjects assigned to that user.
     *
     * @param int $id  App\Models\User id
     *
     * @return response
     */
    public function getRolesManyToMany($id)
    {
        if (User::find($id)) {
            if (User::find($id)->has('roles')) {
                return response()->json(User::with(['roles' => function($q) {
                    $q->select('roles.id', 'type');
                }])->find($id)->roles, 200);
            } else {
                return response()->json(["error" => "No roles assigned to this user!"], 404);
            }
        } else {
            return response()->json(["error" => "No user found!"], 404);
        }
    }

    /**
     * Finds particular user and role, then associates them.
     *
     * @param int $rid  App\Models\Role id
     * @param int $uid  App\Models\User id
     *
     * @return response
     */
    public function setRolesManyToMany($rid, $uid)
    {
        $user = User::find($uid);
        if ($user) {
            $role = Role::find($rid);
            if ($role) {
                // return ($user->roles()->attach($rid)) ? response()->json(["success" => "Role attached to user successfully!"], 200) : response()->json(["error" => "Unable to attach role to user!"], 404);
                // return ($user->roles()->sync([$rids])) ? response()->json(["success" => "Role synced to user successfully!"], 200) : response()->json(["error" => "Unable to sync role to user!"], 404);
                return ($user->roles()->save($role)) 
                    ? response()->json(["success" => "Role assigned to user successfully!"], 200)
                    : response()->json(["error" => "Unable to assign role to user!"], 404);
            } else {
                return response()->json(["error" => "No role found!"], 404);
            }
        } else {
            return response()->json(["error" => "No user found!"], 404);
        }
    }

    /**
     * Finds roles assigned to particular user and deletes them.
     *
     * @param int $id  App\Models\User id
     *
     * @return response
     */
    public function deleteRolesManyToMany($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->has('roles')) {
                // return ($user->roles()->detach($rid)) ? response()->json(["success" => "User role detached successfully!"], 200) : response()->json(["error" => "Unable to detach roles of user!"], 404);
                return ($user->roles()->delete()) 
                    ? response()->json(["success" => "User roles deleted successfully!"], 200)
                    : response()->json(["error" => "Unable to delete roles of user!"], 404);
            } else {
                return response()->json(["error" => "No roles assigned to this user!"], 404);
            }
        } else {
            return response()->json(["error" => "No user found!"], 404);
        }
    }

    /**
     * Returns users having the particular role.
     *
     * @param int $id  App\Models\Role id
     *
     * @return response
     */
    public function getUsersManyToMany($id)
    {
        if (Role::find($id)) {
            $users = [];
            foreach (Role::find($id)->users as $user) {
                array_push($users, [
                    'created_at' => $user->pivot->created_at,
                    'updated_at' => $user->pivot->updated_at
                ]);
            }
            return response()->json($users, 200);
        } else {
            return response()->json(["error" => "No user found!"], 404);
        }
    }

    /**
     * Returns subjects assigned to particular user.
     *
     * @param int $id  App\Models\User id
     *
     * @return response
     */
    public function getRolesHasManyThrough($id)
    {
        return (User::find($id))
            ? response()->json(User::find($id)->subjects, 200)
            : response()->json(["error" => "No user found!"], 404);
    }

    /**
     * Returns photos of particular student.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function getPhotosPolymorphic($id)
    {
        // return (User::find($id)) ? response()->json(User::find($id)->photos, 200) : response()->json(["error" => "No user found!"], 404);
        // return (Subject::find($id)) ? response()->json(Subject::find($id)->photos, 200) : response()->json(["error" => "No subject found!"], 404);
        return (Student::find($id))
            ? response()->json(Student::find($id)->photos, 200)
            : response()->json(["error" => "No student found!"], 404);
    }

    /**
     * Returns owner of particular photo.
     *
     * @param int $id  App\Models\Photo id
     *
     * @return response
     */
    public function getPhotosOwnerPolymorphic($id)
    {
        return (Photo::find($id))
            ? response()->json(Photo::find($id)->imageable, 200)
            : response()->json(["error" => "No owner found!"], 404);
    }

    /**
     * Returns tags assigned to particular student.
     *
     * @param int $id  App\Models\Student id
     *
     * @return response
     */
    public function getTagsPolymorphic($id)
    {
        return (Student::find($id))
            ? response()->json(Student::find($id)->tags, 200)
            : response()->json(["error" => "No tag found!"], 404);
    }

    /**
     * Returns videos which have a particular tag assigned.
     *
     * @param int $id  App\Models\Tag id
     *
     * @return response
     */
    public function getTagsOwnerPolymorphic($id)
    {
        // return (Tag::find($id)) ? response()->json(Tag::find($id)->students, 200) : response()->json(["error" => "No owner found!"], 404);
        return (Tag::find($id))
            ? response()->json(Tag::find($id)->videos, 200)
            : response()->json(["error" => "No owner found!"], 404);
    }
}
