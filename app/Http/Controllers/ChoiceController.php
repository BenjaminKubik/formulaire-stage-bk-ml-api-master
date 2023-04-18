<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChoiceCollection;
use App\Http\Resources\ChoiceRessource;
use App\Http\Resources\QuestionRessource;
use App\Models\Choice;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ChoiceController extends Controller
{
    function show($id){
        $choices = Choice::all();

        $c = [];
        foreach ($choices as $choice){
            if ($choice->toArray()['form_id'] === $id){
                $c[] = new ChoiceRessource($choice);
            }
        }
        if($c[0] === null){
            return ResponseBuilder::error(422, null, "Le formulaire n'a pas de section");
        }
        return ResponseBuilder::success(new ChoiceCollection($c));
    }
    function choixList(){
        $choix = Choice::all();
        return ResponseBuilder::success(new ChoiceCollection($choix));
    }
    function store(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'choix' => 'required',
                'num_question' => 'required',
                'num_sec' => 'required',
                'form_id' => 'required',
            ],
            [
                'choix.required' => 'le choix est requis',
                'num_question.required' => "le numéro de la question est requis",
                'num_sec.required' => 'le numéro de la section est requis',
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
        $i = 1;
        $choix = new Choice();
        $choix->num_question = $request->get('num_question');
        $choix->form_id = $request->get('form_id');
        $choix->num_sec = $request->get('num_sec');
        $choix->choice = $request->get('choix');
        $choix->save();

        return ResponseBuilder::success(new ChoiceRessource($choix));
    }
}
