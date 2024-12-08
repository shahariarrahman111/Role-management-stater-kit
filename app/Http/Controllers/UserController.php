<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function RegistrationForm () {

        return view ('pages.registration');
    }

    public function LoginForm () {

        return view ('pages.login');
    }

    public function HomePage (){

        return view ('pages.home');
    }

    public function Dashboard () {

        // Check if the user is logged in
        if (Auth::check()) {
            // Get the logged-in user's information
            $user = Auth::user();

            // Check if the user's role is admin
            if ($user->role === 'admin') {
                // Pass user data to the dashboard view
                return view('pages.dashboard');
            } else {
                // Redirect non-admin users to the home page
                return redirect('/home')->with('error', 'You are not authorized to access the admin dashboard.');
            }
        } else {
            // Redirect to the login page if the user is not logged in
            return redirect()->route('login.form')->with('error', 'Please login to access this page.');
        }

    }

    public function UserRegister(Request $request){

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:255',
                'password' => 'required|string|min:6|confirmed'

            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'role' => 'user',
                'password' => Hash::make($request->input('password'))

            ]);


            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'User created successfully'
            // ]);

            return redirect()->route('login.form');

        }catch (Exception $exception){
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }


    public function userLogin(Request $request)
    {
        try {
            // Request Validation
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
    
            // Check if the user exists
            $user = User::where('email', '=', $request->input('email'))->first();
    
            // Check if user is found and password is correct
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Credentials'
                ]);
            }
    
            // If the user email is admin@gmail.com and role is not admin, update role to admin
            if ($user->email === 'admin@gmail.com' && $user->role !== 'admin') {
                $user->role = 'admin';
                $user->save();
            }
    
            // Generate JWT Token
            $token = JWTToken::CreateToken($user->email);
    
            // Log the user in using Auth
            Auth::login($user);
    
            // Check if the user is admin or not, and redirect accordingly
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->cookie('jwt_token', $token, 60, null, null, false, true);
            } else {
                return redirect()->route('user.home')->cookie('jwt_token', $token, 60, null, null, false, true);
            }
    
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    

}
