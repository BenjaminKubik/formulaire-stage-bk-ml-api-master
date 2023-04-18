<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorizedFormsCollection;
use App\Http\Resources\AuthorizedFormsRessource;
use App\Http\Resources\DemandesCollection;
use App\Http\Resources\DemandesRessource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserProfilResource;
use App\Http\Resources\UserRessource;
use App\Mail\PasswordMail;
use App\Models\AuthorizedForms;
use App\Models\Demandes;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class UserController extends Controller {

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function userList() {
        $user = User::all();
        return ResponseBuilder::success(new UserCollection($user), 200, null);
    }
    function show($id) {
        $user = User::findOrFail($id);
        return ResponseBuilder::success(new UserRessource($user), 200, null);
    }

    function update(Request $request, $id) {

        $user = User::findOrFail($id);
        $users = User::all();
        $users = new UserCollection($users);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|between:2,100',
            'prenom' => 'required|string|between:2,100',
            'role' => 'required',
            'email' => 'required|string|email',
        ], [
            'nom.required' => 'We need to know your lastname',
            'prenom.required' => 'We need to know your firstname',
            'role.required' => 'We need to know your status',
            'email.required' => 'We need to know your email address!',
            'email.email' => 'not valid email address format',
            'between' => ':attribute doit contenir entre :min et :max '
        ]);

        foreach ($users as $u){
            if ($u->id != $user->id && $u->email == $user->email){
                return ResponseBuilder::error(00, null, 'Bad Request', 400);
            }
        }

        if ($validator->fails()) {
            $tab = [];
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    $tab[] = $error;
                }
            }
            return ResponseBuilder::error(400, null, $tab, 400);
        }

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->role = $request->role;
        $user->email = $request->email;

        $user->save();

        return ResponseBuilder::success('User successfully registered', 200, null);
    }

    function reset($email) {

        $users = new UserCollection(User::all());
        $user = new User();
        foreach ($users as $u){
            if ($u->email === $email){
                $user = $u;
            }
        }
        if ($user === null){
            return ResponseBuilder::error(400, null, 'User not found', 400);
        }
        $val = $this->generateRandomString();
        Mail::to($user->email)->send(new PasswordMail($val));

        $user->password = bcrypt($val);
        $user->save();

        return ResponseBuilder::success('User successfully registered', 200, null);
    }
    function addToForms(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'form_id' => 'required',
                'user_id' => 'required',
            ],
            [
                'form_id.required' => "l'identifiant du formulaire est requis",
                'user_id.required' => 'l\'identifiant de l\'utilisateur est requis',
            ]
        );
        if ($validator->fails()) {
            Log::info($validator->errors()->toArray());
            $tab = [];
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    $tab[] = $error;
                }
            }
            return ResponseBuilder::error(422, null, $tab);
        }
        $authorized = new AuthorizedForms();
        $authorized->form_id = $request->get('form_id');
        $authorized->user_id = $request->get('user_id');
        $authorized->save();

        return ResponseBuilder::success(new AuthorizedFormsRessource($authorized));
    }
    function delFromForms(Request $request){
        $users = AuthorizedForms::all()->where('form_id', $request->get('form_id'))->where('user_id', $request->get('user_id'));
        foreach ($users as $u){
            AuthorizedForms::destroy($u->id);
        }
    }

    function test(){
        $users = AuthorizedForms::all()->where('form_id', 1)->where('user_id', 1);
        var_dump($users[0]->id);
    }
    function storeDemande(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'form_id' => 'required',
            ],
            [
                'user_id.required' => 'l\'identifiant de l\'utilisateur est requis',
                'form_id.required' => "l'identifiant du formulaire est requis",
            ]
        );
        if ($validator->fails()) {
            Log::info($validator->errors()->toArray());
            $tab = [];
            foreach ($validator->errors()->messages() as $messages) {
                foreach ($messages as $error) {
                    $tab[] = $error;
                }
            }
            return ResponseBuilder::error(422, null, $tab);
        }
        $demande = new Demandes();
        $demande->user_id = $request->get('user_id');
        $demande->form_id = $request->get('form_id');
        $demande->save();

        return ResponseBuilder::success(new DemandesRessource($demande));
    }
    function demandeList(){
        return ResponseBuilder::success(new DemandesCollection(Demandes::all()));
    }
    function deleteDemande($id){
        Demandes::destroy($id);
        return ResponseBuilder::success('Supprimé');
    }
}
