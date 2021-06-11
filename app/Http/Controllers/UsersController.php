<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Http\Resources\UserRoleResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExceptionResource;
use App\Http\Resources\RequestErrorResource;
use App\Http\Resources\NotFoundExceptionResource;

class UsersController extends Controller
{

    /**
     * users
     *
    */
    public function users()
    {
        return UsersResource::collection(Profile::all());
    }

    /**
     * profile
     *
    */
    public function profile($id)
    {
        $profile = Profile::findOrFail($id);
        return new UserResource($profile);
    }

    /**
     * Profile Update
     */
    public function profileUpdate(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'phone' => 'required',
            'gender' => 'required',
            'dob' => 'required',
        ]);

        if ($validated->fails()) {
            return new RequestErrorResource($validated);
        }

        DB::beginTransaction();
        try {

            $user = Profile::findOrFail($id);

            $user->update([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'gender' => $request->get('gender'),
                'dob' => $request->get('dob'),
                'email' => $request->get('email'),
                'address' => $request->get('address'),
                'updated_at' => now(),
            ]);

            User::where('profile_id', $id)->update([
                'email' => $request->get('email'),
                'updated_at' => now(),
            ]);

        }catch (\Exception $exception){
            DB::rollBack();
            return new ExceptionResource($exception);
        }

        DB::commit();

        return new UserResource($user);

    }


    /**
     * user roles
     *
     */
    public function userRoles()
    {
        $usersRoles = User::with('role')->whereHas('role')->get();
        return UserRoleResource::collection($usersRoles);
    }

    /**
     * user role edit
     *
     */
    public function userRole($user_id)
    {
        $user = User::findorFail($user_id);
        if (! $user->role){
            return new NotFoundExceptionResource();
        }
        return new RoleResource($user->role);
    }

    /**
     * Update user role
     */
    public function userRoleAssign(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'user_id' => 'required',
            'role_id' => 'required',
        ]);

        if ($validated->fails()) {
            return new RequestErrorResource($validated);
        }

        try {

            User::where('id', $request->get('user_id'))->update([
                'role_id' => $request->get('role_id'),
                'updated_at' => now(),
            ]);

        }catch (\Exception $exception){

            return new ExceptionResource($exception);
        }

        return \response()->json(['status' => 1, 'msg' => 'Role assigned to user successfully']);

    }


    /**
     * Delete Assigned role from user user
     */
    public function userRoleDelete(Request $request, $user_id)
    {
        try {

            User::where('id', $user_id)->update([
                'role_id' => null,
                'updated_at' => now(),
            ]);

        }catch (\Exception $exception){

            return new ExceptionResource($exception);
        }

        return \response()->json(['status' => 1, 'msg' => 'Deleted role from user successfully']);

    }


}
