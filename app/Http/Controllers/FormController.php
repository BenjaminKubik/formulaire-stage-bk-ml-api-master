<?php


namespace App\Http\Controllers;


use App\Http\Resources\AuthorizedFormsCollection;
use App\Http\Resources\CommentaireResource;
use App\Http\Resources\FormRessource;
use App\Http\Resources\FormsCollection;
use App\Http\Resources\UserCollection;
use App\Models\AuthorizedForms;
use App\Models\Commentaire;
use App\Models\Forms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class FormController
{
    function del(Request $request){
        Forms::destroy($request->get('id'));
    }
    function formList(){
        $forms = Forms::all();
        return ResponseBuilder::success(new FormsCollection($forms));
    }
    function showByTitre($titre){
        $forms = Forms::all();

        $f = new Forms();
        foreach ($forms as $form){
            if ($form->toArray()['titre'] === $titre){
                $f = $form;
            }
        }
        if($f === null){
            return ResponseBuilder::error(422, null, "Le formulaire n'existe pas");
        }
        return ResponseBuilder::success(new FormRessource($f));
    }
    function showById($id){
        return ResponseBuilder::success(new FormRessource(Forms::find($id)));
    }
    function store(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'titre' => 'required',
                'nbSec' => 'required|numeric|min:1',
                'prive' => '',
            ],
            [
                'titre.required' => 'le titre est requis',
                'nbSec.required' => 'le nombre de section est requis',
                'numeric' => ':attribute est un entier',
                'min' => ':attribute doit être superieur :min ',
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


        $form = new Forms();
        $form->titre = $request->get('titre', null);
        $form->nbSec = $request->get('nbSec', null);
        $form->prive = $request->get('prive', null);
        $form->save();

        return ResponseBuilder::success(new FormRessource($form));
    }

    function getAuthorizedUser($id){
        $users = AuthorizedForms::all()->where('form_id', '=', $id);
        $tab = [];
        foreach ($users as $user) {
            array_push($tab, $user);
        }
        return ResponseBuilder::success(new AuthorizedFormsCollection($tab));
    }
}