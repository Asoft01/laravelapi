<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class APIController extends Controller
{
    //
    public function getUsers(){
        // echo "test"; die;
        $users= User::get();
        // return $users;
        return response()->json(["users"=>$users]);
    }
}
