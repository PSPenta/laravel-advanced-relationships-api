<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api.cors')->group(function () {
    // public routes
    Route::post('/login', 'Api\AuthController@login')->name('login.api');
    Route::post('/register', 'Api\AuthController@register')->name('register.api');

    Route::get('/unauthenticated', function () {
        return response()->json(["error" => "Unauthenticated user!"], 401);
    })->name('login.error');

    Route::get('/unauthorized', function () {
        return response()->json(["error" => "Unauthorized user!"], 403);
    })->name('unauthorized');

    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'Api\AuthController@logout')->name('logout');
        Route::post('/change-password', 'Api\AuthController@changePassword')->name('change.password');

        Route::prefix('users')->group(function () {
            Route::get('/', 'Api\UserController@getUsers');
            Route::get('/paginate', 'Api\UserController@getUsersPaginate');
            Route::get('/{id}', 'Api\UserController@getUser');
            Route::post('/', 'Api\UserController@addUser');
            Route::put('/{id}', 'Api\UserController@updateUser');
            Route::delete('/{id}', 'Api\UserController@deleteUser');
        });

        Route::prefix('students')->group(function () {
            Route::get('/', 'Api\StudentController@getStudents');
            Route::get('/{id}', 'Api\StudentController@getStudent');
            Route::post('/', 'Api\StudentController@addStudent');
            Route::put('/{id}', 'Api\StudentController@updateStudent');
            Route::delete('/{id}', 'Api\StudentController@deleteStudent');
            Route::post('/f', 'Api\StudentController@addStudentFillable');
            Route::put('/{id}/f', 'Api\StudentController@updateStudentFillable');

            Route::delete('/{id}/multiple', 'Api\StudentController@deleteMultipleStudents');
            Route::delete('/', 'Api\StudentController@deleteAllStudents');
            Route::get('/{id}/s', 'Api\StudentController@getSoftDeletedStudent');
            Route::put('/{id}/sr', 'Api\StudentController@restoreSoftDeletedStudent');
            Route::delete('/{id}/force', 'Api\StudentController@forceDeleteSoftDeletedStudent');
        });

        Route::get('/all-students', 'Api\EloquentRelationshipsController@getStudentsWithAllData');
        Route::get('/students/{id}/oto', 'Api\EloquentRelationshipsController@getStudentOneToOne');
        Route::get('/subjects/{id}/oto', 'Api\EloquentRelationshipsController@getSubjectOneToOne');
        Route::post('/subjects/{id}/oto', 'Api\EloquentRelationshipsController@setSubjectOneToOne');
        Route::put('/subjects/{id}/oto', 'Api\EloquentRelationshipsController@updateSubjectOneToOne');
        Route::delete('/subjects/{id}/oto', 'Api\EloquentRelationshipsController@deleteSubjectOneToOne');
        Route::get('/subjects/{id}/otm', 'Api\EloquentRelationshipsController@getSubjectOneToMany');
        Route::post('/subjects/{id}/otm', 'Api\EloquentRelationshipsController@setSubjectOneToMany');
        Route::put('/subjects/{id}/otm', 'Api\EloquentRelationshipsController@updateSubjectOneToMany');
        Route::delete('/subjects/{id}/otm', 'Api\EloquentRelationshipsController@deleteSubjectOneToMany');
        Route::get('/users/{id}/mtm', 'Api\EloquentRelationshipsController@getRolesManyToMany');
        Route::post('/users/mtm/{rid}/{uid}', 'Api\EloquentRelationshipsController@setRolesManyToMany');
        Route::delete('/users/{id}/mtm', 'Api\EloquentRelationshipsController@deleteRolesManyToMany');
        Route::get('/roles/{id}/mtm', 'Api\EloquentRelationshipsController@getUsersManyToMany');
        Route::get('/roles/{id}/hmt', 'Api\EloquentRelationshipsController@getRolesHasManyThrough');
        Route::get('/photos/{id}/poly', 'Api\EloquentRelationshipsController@getPhotosPolymorphic');
        Route::get('/photos/{id}/owner/poly', 'Api\EloquentRelationshipsController@getPhotosOwnerPolymorphic');
        Route::get('/tags/{id}/polym2m', 'Api\EloquentRelationshipsController@getTagsPolymorphic');
        Route::get('/tags/{id}/owner/polym2m', 'Api\EloquentRelationshipsController@getTagsOwnerPolymorphic');
    });
});
