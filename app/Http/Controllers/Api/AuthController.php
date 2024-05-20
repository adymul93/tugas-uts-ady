<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /** @var \App\Models\User **/
            $user = Auth::user();
            $data['token'] = "Bearer " . $user->createToken('ProductAPI')->plainTextToken;
            return $this->sendResponse($data, 'Login berhasil');
        }
        return $this->sendError('Ada kesalahan.', []);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => "required|min:5"
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'user',
            'password' => Hash::make($request->password)
        ]);

        $user->save();

        return $this->sendResponse(null, "Register berhasil");
    }

    public function googleRegister()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleRegisterCallback()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider */
        $driver = Socialite::driver('google');

        $google_user = $driver->stateless()->user();;

        if (!$google_user) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }

        $existing_user = User::where(['email' => $google_user->getEmail()])->first();
        if ($existing_user) {
            return $this->sendError("Email sudah digunakan", [], 400);
        }

        $user = User::create([
            'name' => $google_user->getName(),
            'email' => $google_user->getEmail(),
            'role' => 'user',
            'password' => Hash::make('defaultpassword')
        ]);

        $user->save();

        return $this->sendResponse(null, "Register berhasil");
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logged out']);
    }
}
