<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
        ]);

         // Hash del password
        $hashedPassword = Hash::make($request->input('password'));

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashedPassword,
        ]);

        return response()->json(['message' => 'Usuario creado..', 'user' => $user], 201);
    }

    public function authenticate(Request $request)
	{

    $credentials = $request->only('email', 'password');

		try {
			if (!Auth::attempt($credentials)) {
				throw ValidationException::withMessages([
					'email' => ['Invalid credentials'],
				]);
			}

			$user = Auth::user();
			$token = JWTAuth::fromUser($user);

			return response()->json([
				"meta" => [
					'success' => true,
					'errors' => []
				],
				"data" => [
					"token"=> $token,
					"minutes_to_expire" => JWTAuth::factory()->getTTL()
				]
			]);
		} catch (\Exception $e) {
			return response()->json([
				"meta" => [
					'success' => false,
					'errors' => "Password incorrect for: {$request->email}"
				],
			], 401);
		}
  }
}
