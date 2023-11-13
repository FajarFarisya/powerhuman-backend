<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        $users = DB::table('users')->get();
        return view('test', ['users'=>$users]);
    }

    public function add(){
        DB::table('users')->insert([
            "name" => "jajay",
            "email" => "jajay@euy.com",
            "password" => "123456"
        ]);
    }
}
