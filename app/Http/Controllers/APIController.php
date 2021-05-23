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

    public function updateUserDetails(Request $request, $id){
        if($request->isMethod('put')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;
            
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

            // $userData['id']
            User::where('id', $id)->update(['name'=> $userData['name'], 'email'=> $userData['email'], 'password' => bcrypt($userData['password'])]);
            return response()->json(['message' => "User details updated successfully"], 202);
        }
    }

    public function updateUserName(Request $request, $id){
        if($request->isMethod('patch')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;
            // User::where('id', $userData['id'])->update(['name' => $userData['name']]);

            $rules = [
                "name" => "required|regex:/^[\pL\s\-]+$/u"
            ];

            $customMessages = [
                'name.required'=> 'Name is required'
            ];

            $validator = Validator::make($userData, $rules, $customMessages);
            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            User::where('id', $id)->update(['name' => $userData['name']]);
            return response()->json(['message' => 'User details updated successfully'], 202);
        }
    }

    public function deleteUser($id){
        User::where('id', $id)->delete();
        return response()->json(['message' => 'User deleted Successfully'], 202);
    }

    public function deleteUserWithJson(Request $request){
        if($request->isMethod('delete')){
            $userData = $request->all();
            // echo "<pre>"; print_r($userData); die;
            User::where('id', $userData['id'])->delete();
            return response()->json(['message'=> 'User deleted Successfully'], 202);
        }
    }

    public function deleteMultipleUsers($ids){
        // echo $ids; die;
        $ids = explode(",", $ids);
        // echo "<pre>"; print_r($ids); die;
        User::whereIn('id', $ids)->delete();
        return response()->json(['message' => "Users deleted Successfully"], 202);
    }

    public function deleteMultipleUsersWithJson(Request $request){
        if($request->isMethod('delete')){
            $userData = $request->all();
            // echo "<pre>"; print_r($userData); die;
            
            ////////////// First Method of deleting the multiple users 
            // echo "<pre>"; print_r($userData['ids']); die;
            // foreach ($userData as $key => $value) {
            //     echo "<pre>"; print_r($value); die;
            //     // User::where('id', $value)->delete();
            // }

            ///////////// Second Method of deleting the multiple users ///////////////////
            User::whereIn('id', $userData['ids'])->delete();
            return response()->json(['message'=> 'Users deleted successfully'], 202);
        }
    }
}
