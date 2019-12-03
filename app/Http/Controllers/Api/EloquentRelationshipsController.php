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
        return response(Student::with('subjects')->with('photos')->with('tags')->get(), 200);
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
            ? response(Student::find($id)->subject, 200)
            : response("No student found!", 404);
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
                    ? response("Subject assigned to user successfully!", 200)
                    : response("Unable to assign subject to user!", 404);
            } else {
                return response("Unable to save subject!", 404);
            }
        } else {
            return response("No student found!", 404);
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
                ? response("Subject of user updated successfully!", 200)
                : response("Unable to update subject!", 404);
        } else {
            return response("No subject found!", 404);
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
                    ? response("Subject of user delete successfully!", 200)
                    : response("Unable to delete subject!", 404);
            } else {
                return response("No subject found!", 404);
            }
        } else {
            return response("No student found!", 404);
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
            ? response(Subject::with('student')->find($id), 200)
            : response("No subject found!", 404);
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
                ? response(Student::find($id)->subjects, 200)
                : response("No subjects found!", 404)
            ) 
            : response("No student found!", 404);
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
                    ? response("Subject assigned to user successfully!", 200)
                    : response("Unable to assign subject to user!", 404);
            } else {
                return response("Unable to save subject!", 404);
            }
        } else {
            return response("No student found!", 404);
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
                ? response("Subject of user updated successfully!", 200)
                : response("Unable to update subject!", 404);
        } else {
            return response("No student or subjects found!", 404);
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
                    ? response("All subjects of current user delete successfully!", 200)
                    : response("Unable to delete subjects!", 404);
            } else {
                return response("No subject found!", 404);
            }
        } else {
            return response("No student found!", 400);
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
        $user = User::find($id);
        if ($user) {
            if ($user->has('roles')) {
                $roles = [];
                foreach (User::find($id)->roles as $role)
                    array_push($roles, $role->pivot);
                
                return response($roles, 200);
                // return response(User::find($id)->roles, 200);
            } else {
                return response("No roles assigned to this user!", 404);
            }
        } else {
            return response("No user found!", 404);
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
                // return ($user->roles()->attach($rid)) ? response("Role attached to user successfully!", 200) : response("Unable to attach role to user!", 404);
                // return ($user->roles()->sync([$rids])) ? response("Role synced to user successfully!", 200) : response("Unable to sync role to user!", 404);
                return ($user->roles()->save($role)) 
                    ? response("Role assigned to user successfully!", 200)
                    : response("Unable to assign role to user!", 404);
            } else {
                return response("No role found!", 404);
            }
        } else {
            return response("No user found!", 404);
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
                // return ($user->roles()->detach($rid)) ? response("User role detached successfully!", 200) : response("Unable to detach roles of user!", 404);
                return ($user->roles()->delete()) 
                    ? response("User roles deleted successfully!", 200)
                    : response("Unable to delete roles of user!", 404);
            } else {
                return response("No roles assigned to this user!", 404);
            }
        } else {
            return response("No user found!", 404);
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
            return response($users, 200);
        } else {
            return response("No user found!", 404);
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
        return (User::find($id)) ? response(User::find($id)->subjects, 200) : response("No user found!", 404);
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
        // return (User::find($id)) ? response(User::find($id)->photos, 200) : response("No user found!", 404);
        // return (Subject::find($id)) ? response(Subject::find($id)->photos, 200) : response("No subject found!", 404);
        return (Student::find($id)) ? response(Student::find($id)->photos, 200) : response("No student found!", 404);
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
        return (Photo::find($id)) ? response(Photo::find($id)->imageable, 200) : response("No owner found!", 404);
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
        return (Student::find($id)) ? response(Student::find($id)->tags, 200) : response("No tag found!", 404);
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
        // return (Tag::find($id)) ? response(Tag::find($id)->students, 200) : response("No owner found!", 404);
        return (Tag::find($id)) ? response(Tag::find($id)->videos, 200) : response("No owner found!", 404);
    }
}
