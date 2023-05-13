<?php

namespace App\Http\Controllers;

use App\Models\Authentication;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function customLogin(Request $request)
    {
        $user_name = $request->user_name;
        $password = $request->password;
        //$credentials = $request->only('email', 'password');
        $data = Authentication::where('user_name','=',$user_name)
                ->where('password','=',$password)->exists();
                if($data)
                {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Login Successfull!!',
                    ]);
                }
                else{
                    return response()->json([
                        'status' => 400,
                        'message' => 'Incorrect User Name or Password!!',
                    ]);
                }
    }

      
    public function signOut() {
        Session::flush();
        Auth::logout();
  
       // return Redirect('login');
    }

   
    public function store(Request $request)
    {
        if($check)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Registration Successfull!!',
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are incorrect!',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Authentication $authentication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Authentication $authentication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Authentication $authentication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Authentication $authentication)
    {
        //
    }
}
