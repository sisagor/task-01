<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LoginResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExceptionResource;
use App\Http\Resources\RequestErrorResource;
use App\Http\Resources\LoginFailedResource;


class AuthController extends Controller
{

    /**
     * Register
     */
    public function register(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'phone' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:16|confirmed',
        ]);

        if ($validated->fails()) {
            return new RequestErrorResource($validated);
        }

        //Start Transaction
        DB::beginTransaction();

        try {

            $profile = Profile::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'gender' => $request->get('gender'),
                'dob' => $request->get('dob'),
                'email' => $request->get('email'),
                'address' => $request->get('address'),
            ]);

            $user = User::create([
                'profile_id' => $profile->id,
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();
            return new ExceptionResource($exception);
        }

        DB::commit();


        return new LoginResource($user);

    }


    /**
     * Login
     */
    public function login(Request $request)
    {

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = User::where('email', $request->get('email'))->first();
            return new LoginResource($user);
        }

        return new LoginFailedResource($request);

    }


}

