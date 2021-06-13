<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RolesResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExceptionResource;
use App\Http\Resources\RequestErrorResource;


class RoleController extends Controller
{

    /**
     * users
     *
    */
    public function roles()
    {
        return RolesResource::collection(Role::all());
    }

    /**
     * Create any user profile
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|unique:roles',
            'description' => 'required',
            'level' => 'required',
        ]);

        if ($validated->fails()) {
            return new RequestErrorResource($validated);
        }

        try {

            $role = Role::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'level' => $request->get('level'),
            ]);

        }catch (\Exception $exception){

            return new ExceptionResource($exception);
        }

        return new RoleResource($role);

    }


    /**Edit Role*/
    public function edit(Request $request, $id)
    {
        return new RoleResource(Role::find($id));
    }


    /**
     * Update any role
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'description' => 'required',
            'level' => 'required',
        ]);

        if ($validated->fails()) {
            return new RequestErrorResource($validated);
        }

        try {

            $role = Role::findOrFail($id);

            $role->update([
                 'name' => $request->get('name'),
                 'description' => $request->get('description'),
                 'level' => $request->get('level'),
                 'updated_at' => now(),
            ]);


        }catch (\Exception $exception){

            return new ExceptionResource($exception);
        }


        return new RoleResource($role);

    }









}
