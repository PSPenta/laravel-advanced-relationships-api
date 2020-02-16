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
    Route::middleware('auth:api')->group(function () {
        Route::prefix('mongo')->group(function () {
            Route::get('/users', 'MongoDBController@getUsers');
        });
    });
    // public routes
    Route::post('/login', 'AuthController@login')->name('login.api');
    Route::post('/register', 'AuthController@register')->name('register.api');

    Route::get('/unauthenticated', function () {
        return response()->json(["error" => "Unauthenticated user!"], 401);
    })->name('unauthenticated');

    Route::get('/unauthorized', function () {
        return response()->json(["error" => "Unauthorized user!"], 403);
    })->name('unauthorized');

    // private routes
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', 'AuthController@logout')->name('logout');
        Route::post('/change-password', 'AuthController@changePassword')->name('change.password');

        Route::prefix('users')->group(function () {
            Route::get('/', 'UserController@getUsers');
            Route::get('/paginate', 'UserController@getUsersPaginate');
            Route::get('/{id}', 'UserController@getUser');
            Route::post('/', 'UserController@addUser');
            Route::put('/{id}', 'UserController@updateUser');
            Route::delete('/{id}', 'UserController@deleteUser');
        });

        Route::prefix('students')->group(function () {
            Route::get('/', 'StudentController@getStudents');
            Route::get('/{id}', 'StudentController@getStudent');
            Route::post('/', 'StudentController@addStudent');
            Route::put('/{id}', 'StudentController@updateStudent');
            Route::delete('/{id}', 'StudentController@deleteStudent');
            Route::post('/f', 'StudentController@addStudentFillable');
            Route::put('/{id}/f', 'StudentController@updateStudentFillable');

            Route::delete('/{id}/multiple', 'StudentController@deleteMultipleStudents');
            Route::delete('/', 'StudentController@deleteAllStudents');
            Route::get('/{id}/s', 'StudentController@getSoftDeletedStudent');
            Route::put('/{id}/sr', 'StudentController@restoreSoftDeletedStudent');
            Route::delete('/{id}/force', 'StudentController@forceDeleteSoftDeletedStudent');
        });

        Route::get('/all-students', 'EloquentRelationshipsController@getStudentsWithAllData');
        Route::get('/students/{id}/oto', 'EloquentRelationshipsController@getStudentOneToOne');
        Route::get('/subjects/{id}/oto', 'EloquentRelationshipsController@getSubjectOneToOne');
        Route::post('/subjects/{id}/oto', 'EloquentRelationshipsController@setSubjectOneToOne');
        Route::put('/subjects/{id}/oto', 'EloquentRelationshipsController@updateSubjectOneToOne');
        Route::delete('/subjects/{id}/oto', 'EloquentRelationshipsController@deleteSubjectOneToOne');
        Route::get('/subjects/{id}/otm', 'EloquentRelationshipsController@getSubjectOneToMany');
        Route::post('/subjects/{id}/otm', 'EloquentRelationshipsController@setSubjectOneToMany');
        Route::put('/subjects/{id}/otm', 'EloquentRelationshipsController@updateSubjectOneToMany');
        Route::delete('/subjects/{id}/otm', 'EloquentRelationshipsController@deleteSubjectOneToMany');
        Route::get('/users/{id}/mtm', 'EloquentRelationshipsController@getRolesManyToMany');
        Route::post('/users/mtm/{rid}/{uid}', 'EloquentRelationshipsController@setRolesManyToMany');
        Route::delete('/users/{id}/mtm', 'EloquentRelationshipsController@deleteRolesManyToMany');
        Route::get('/roles/{id}/mtm', 'EloquentRelationshipsController@getUsersManyToMany');
        Route::get('/roles/{id}/hmt', 'EloquentRelationshipsController@getRolesHasManyThrough');
        Route::get('/photos/{id}/poly', 'EloquentRelationshipsController@getPhotosPolymorphic');
        Route::get('/photos/{id}/owner/poly', 'EloquentRelationshipsController@getPhotosOwnerPolymorphic');
        Route::get('/tags/{id}/polym2m', 'EloquentRelationshipsController@getTagsPolymorphic');
        Route::get('/tags/{id}/owner/polym2m', 'EloquentRelationshipsController@getTagsOwnerPolymorphic');
    });

    Route::any('/{any}', function () {
        return response()->json(["error" => "Not found!"], 404);
    })->where('any', '.*')->name('not.found');
});
