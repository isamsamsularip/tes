<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:7|confirmed',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:7|confirmed'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }

 		//Token created, return with success response and jwt token
        return response()->json(compact('token'));
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

		//Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }

    public function Logic_test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_kontainer'        => 'required',
        ]);
        $prima1 = 0;
        $prima2 = 0;
        $angka0 = 0;
        $urut   = 0;

        for ($i = 2; $i < $request->nomor_kontainer; $i++)
        {
            if ($request->nomor_kontainer % $i == 0)
                $prima1 = 1;
        }
        $cek0=strpos($request->nomor_kontainer,"0");
        if ($cek0){
            $angka0 = 1;
          }

        $hapus_karakter= substr($request->nomor_kontainer, 3);
        for ($i = 2; $i < $request->nomor_kontainer; $i++)
        {
            if ($hapus_karakter % $i == 0)
                $prima2 = 1;
        }
        $angka5 = substr($request->nomor_kontainer,4,-2);
        $angka6 = substr($request->nomor_kontainer,5,-1);
        $angka7 = substr($request->nomor_kontainer,6);
        if($prima1 == 1 and $angka0 == 0 and $prima2 == 1)
        {
            $posisi ="Tengah";
        }
        if($prima1 == 1 and $angka0 == 0 and $angka5 == $angka6 and $angka6==$angka7)
        {
            $posisi ="Kanan";
        }
        if($prima1 == 1 and $angka0 == 0 and $angka6 + 1 ==$angka7 or $angka6 - 1 ==$angka7)
        {
            $posisi ="Kiri";
        }
        if($prima1 == 0 and $angka0 == 1 )
        {
            $posisi ="Reject";
        }
        return $posisi;
    }
}
