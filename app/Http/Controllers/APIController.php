<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Validator;
use Auth;
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
           return response()->json(["users"=> $users], 200);
       }else{
           $users = User::find($id);
           return response()->json(['users'=> $users], 200);
       }
    }

    public function getUsersList(Request $request){
        $header = $request->header('Authorization');
        if(empty($header)){
            $message = "Header Authorization is missing!";
            return response()->json(['status'=> false, 'message'=> $message], 422);
        }else{
            if($header == "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkFtaXQgR3VwdGEiLCJpYXQiOjE1MTYyMzkwMjJ9.cNrgi6Sso9wvs4GlJmFnA4IqJY4o2QEcKXgshJTjfNg"){
                $users = User::get();
                return response()->json(["users"=> $users], 200);
            }else{
                $message = "Header Authorization is incorrect";
                return response()->json(['status' => false, 'message'=> $message], 422);
            }
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

    public function loginUser(Request $request){
        if($request->isMethod('post')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;
            
            $rules = [
                "email" => "required|email|exists:users", 
                "password" => "required"
            ];

            $customMessages = [
                'email.required' => 'Email is required',
                'email.email' => 'Valid Email is required',
                'email.unique' => 'Email does not exists in database', 
                'password.required' => 'Password is required'
            ];

            $validator = Validator::make($userData, $rules, $customMessages);
            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            // Fetch User Details
            $userDetails =  User::where('email', $userData['email'])->first();

            // Verify the Password
            if(password_verify($userData['password'], $userDetails->password)){
                // echo "password match.. user can login"; die;
                $accessToken = Str::random(60);

                // Update Token
                User::where('email', $userData['email'])->update(['access_token'=> $accessToken]);

                return response()->json(['status'=> true, "message"=> "User logged in Successfully!", 'token'=> $accessToken], 201);
            }else{
                return response()->json(['status'=> false, "message"=> "Password is Incorrect"], 404);
            }
        }
    }

    public function loginUserWithPassport(Request $request){
        if($request->isMethod('post')){
            $userData = $request->input();
            // echo "<pre>"; print_r($userData); die;
            
            $rules = [
                "email" => "required|email|exists:users", 
                "password" => "required"
            ];

            $customMessages = [
                'email.required' => 'Email is required',
                'email.email' => 'Valid Email is required',
                'email.unique' => 'Email does not exists in database', 
                'password.required' => 'Password is required'
            ];

            $validator = Validator::make($userData, $rules, $customMessages);
            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }

            if(Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']])){
                $user= User::where('email', $userData['email'])->first();
                // echo "<pre>"; print_r(Auth::user()); die;
                $authorizationToken = $user->createToken($userData['email'])->accessToken;

                User::where('email', $userData['email'])->update(['access_token' => $authorizationToken]);

                return response()->json([
                    'status' => true,
                    'message' => 'User logged successfully',
                    'token' => $authorizationToken
                ], 201);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Email or Password is incorrect!'
                ], 422);
            }
        }
    }

    public function registerUser(Request $request){
        if($request->isMethod('post')){
            $userData= $request->input();
            // echo "<pre>"; print_r($userData); die;

            // Generate Unique API / Access Token
            $accessToken = Str::random(60);

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
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->password = bcrypt($userData['password']);
            $user->access_token = $accessToken;
            $user->save();

            return response()->json([
                'status'=> true, 
                'message'=> 'User Registered Successfully!',
                'token' => $accessToken
            ], 201);
        }
    }

    public function registerUserWithPassport(Request $request){
        if($request->isMethod('post')){
            $userData= $request->input();
            // echo "<pre>"; print_r($userData); die;

            // Generate Unique API / Access Token

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
            
            // $accessToken = Str::random(60);
            $user = new User;
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->password = bcrypt($userData['password']);
            // $user->access_token = $accessToken;
            $user->save();

            // Attempt to log with the email and password created 
            if(Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']])){
                $user = User::where('email', $userData['email'])->first();
                // echo "<pre>"; print_r(Auth::user()); die;

                // Generate Access Token with Password
                // echo $accessToken = $user->createToken($userData['email'])->accessToken; die;
                $accessToken = $user->createToken($userData['email'])->accessToken;

                // Update Access Token in Users Table 
                User::where('email', $userData['email'])->update(['access_token' => $accessToken]);

                return response()->json([
                    'status'=> true, 
                    'message'=> 'User Registered Successfully!',
                    'token' => $accessToken
                ], 201);   
            }else{
                $message = "Something went wrong! Please try again";
                return response()->json(['status' => false, 'message' => $message], 422);
            }
        }
    }

    public function logoutUser(Request $request){
        $access_token = $request->header("Authorization");
        if(empty($access_token)){
            $message = "User Token is missing in API Header";
            return response()->json(['status'=> false, 'message'=> $message], 422);
        }else{
            // echo "continue logout api";
            // echo $access_token  = str_replace("Bearer ", "", $access_token); die;
            $access_token = str_replace("Bearer ", "", $access_token); 
            // echo $userCount = User::where('access_token', $access_token)->count(); die;
            $userCount = User::where('access_token', $access_token)->count(); 
            if($userCount > 0){
                // Update User Token to Null
                User::where('access_token', $access_token)->update(['access_token'=> NULL]);
                $message = "User logged out successfully";
                return response()->json(['status' => true, 'message'=> $message], 200);
            }
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

            $validator = Validator::make($userData, $rules, $customMessages);
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
