<?php

namespace App\Http\Controllers;

use App\Mail\PasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Validator;
use google\appengine\api\mail\Message;



class AuthController extends Controller {

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|between:2,100',
            'prenom' => 'required|string|between:2,100',
            'email' => 'required|string|email|unique:users',
            'role' => 'required|string',
        ], [
            'email.required' => 'We need to know your email address!',
            'email.email' => 'not valid email address format',
            'email.unique' => 'email address already used',
            'nom.required' => 'We need to know your lastname',
            'role.required' => 'We need to know your role',
            'prenom.required' => 'We need to know your firstname',
            'between'=> ':attribute doit contenir entre :min et :max '
        ]);

        if ($validator->fails()) {
            $tab = [];
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    $tab[] = $error;
                }
            }
            return ResponseBuilder::error(400, null, $tab, 400);
        }

        $val = $this->generateRandomString();
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($val)]
        ));
        Mail::to($user->email)->send(new PasswordMail($val));

        return ResponseBuilder::success('User successfully registered', 200, null);

    }


    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error(400, null, ['requête invalide'], 400);
        }
        $credentials = $request->only('email', 'password');
        Log::info("coucou : ". implode(" ",$credentials));

        if (!$token = auth()->attempt($credentials)) {
            return ResponseBuilder::error(401, null, ['Authentification invalide'], 401);
        }

        return $this->createNewToken($token);

    }

    public function userProfile() {
        $data = auth()->user();
        return ResponseBuilder::success($data, 200, null);
    }


    public function logout() {
        auth()->logout();
        return ResponseBuilder::success('User successfully signed out', 204, null);
    }

    protected function createNewToken($token) {
        $data = [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 15,
            'user' => auth()->user()
        ];

        return ResponseBuilder::success($data, 200, null);

    }


    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

}
