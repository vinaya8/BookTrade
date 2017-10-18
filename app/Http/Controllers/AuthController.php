<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use Validator;
use Hash;
use Session;

class AuthController extends Controller
{
    public function do_login(Request $request) {
        // Validate the input
        $messages = [
            'required' => 'Enter your :attribute',
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required | max:64',
            'password' => 'required ',
        ], $messages);
        // If validator fails
        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => 'There are some errors in your form !',
            ]);
        } else {
            // Check if user exists
            $checkUser = Student::where('email', $request->input('email'))->first();
            if($checkUser) {
                if (Hash::check($request->input('password'), $checkUser->password)) {
                    Session::regenerate();
                    Session::put('session', $checkUser);
                    if($checkUser->role == 'admin') {
                        return response()->json([
                        'status' => 'success',
                        'message' => 'admin',
                    ]);
                    } else if($checkUser->role == 'student') {
                        return response()->json([
                        'status' => 'success',
                        'message' => 'student',
                    ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Incorrect Password !',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'There is no such User !',
                ]);
            }
        }
    }

}