<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function AdminDashboard(Request $request){
        try{

//            Checking user login
            $user = Auth::user();
            if(!$user){
                return response()->json(['error' => 'Unauthorized'], 401);
            }

//            Checkin email and redirection

            if ($user->email === 'admin@gmail.com' && $user->role !== 'admin') {
                    $user->role = 'admin';
                    $user->save();

                    return response()->json([
                    'status' => 'success',
                    'message' => 'Welcome to the admin dashboard',
                    'role' => $user->role
                ]);
            }else{
                return response()->json([
                    'status' => 'success',
                    'message' => 'Access granted to the home page',
                    'role' => $user->role
                ]);
            }


        }catch (Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


}
