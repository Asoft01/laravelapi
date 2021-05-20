<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
class APIController extends Controller
{
    //
    // public function getUsers(){
    //     // echo "test"; die;
    //     $users= User::get();
    //     // return $users;
    //     return response()->json(["users"=>$users]);
    // }

    // Fetching one single record
    public function getUsers($id= null){
       if(empty($id)){
           $users = User::get();
           return response()->json(["users"=> $users]);
       }else{
           $users = User::find($id);
           return response()->json(['users'=> $users]);
       }
    }

    public function addUsers(Request $request){
        if($request->isMethod('post')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;

            // Simple POST API Validations
            // First Method of writing error message
            // if(empty($userData['name']) || empty($userData['email']) || empty($userData['password'])){
            //     $message = "Please complete user details";
            //     return response()->json(['status'=>false, 'message'=> $message], 422);
            // }

            // // Check if email is valid
            // if(!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)){
            //     $message ="Please enter valid email ";
            //     return response()->json(['status'=> false, 'message'=> $message], 422);
            // }
            
            // // Check if email already exists
            // $userCount = User::where(['email' => $userData['email']])->count();
            // if($userCount > 0){
            //     $message = "Email already exists";
            //     return response()->json(['status'=> false, "message" => $message], 422);
            // }

            // Simplified way of displaying the form errors
            // if(empty($userData['name']) || empty($userData['email']) || empty($userData['password'])){
            //     $error_message = "Please complete user details";
            // }

            // // Check if email is valid
            // if(!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)){
            //     $error_message ="Please enter valid email ";
            // }
            
            // // Check if email already exists
            // $userCount = User::where(['email' => $userData['email']])->count();
            // if($userCount > 0){
            //     $error_message = "Email already exists";
            // }

            // if(isset($error_message) && !empty($error_message)){
            //     return response()->json(['status' => false, "message" => $error_message], 422);
            // }

            // Advance POST API Validation

            $rules = [
                "name" => "required|regex:/^[\pL\s\-]+$/u",
                "email" => "required|email|unique:users", 
                "password" => "required"
            ];

            $customMessages = [
                'name.required'=> 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Valid Email is required',
                'email.unique' => 'Email already exists in database', 
                'password.required' => 'Password is required'
            ];

            $validator = Validator::make($userData, $rules, $customMessages);
            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            $user = new User;
            $user->name= $userData['name'];
            $user->email= $userData['email'];
            $user->password= bcrypt($userData['email']);
            $user->save();
            return response()->json(['message'=> 'User added successfully'], 201);
        }
    }

    public function addMultipleUsers(Request $request){
        if($request->isMethod('post')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;

            $rules = [
                "users.*.name" => "required|regex:/^[\pL\s\-]+$/u",
                "users.*.email" => "required|unique:users",
                "users.*.password" => "required"
            ];

            $customMessages = [
                'users.*.name.required'=> 'Name is required',
                'users.*.email.required' => 'Email is required',
                'users.*.email.email' => 'Valid Email is required',
                'users.*.email.unique' => 'Email already exists in database', 
                'users.*.password.required' => 'Password is required'
            ];

            $validator = Validator::make($userData, $rules);
            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            foreach($userData['users'] as $key => $value){
                $user = new User;
                $user->name = $value['name'];
                $user->email = $value['email'];
                $user->password = bcrypt($value['password']);
                $user->save();
            }
            return response()->json(['message'=> 'Users added successfully'], 201);
        }
    }
}
