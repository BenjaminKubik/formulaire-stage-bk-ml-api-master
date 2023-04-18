<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionCollection;
use App\Http\Resources\QuestionRessource;
use App\Models\Choice;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class QuestionController extends Controller
{
    function show($id){
        $questions = Question::all();

        $q = [];
        foreach ($questions as $question){
            if ($question->toArray()['form_id'] === $id){
                $q[] = new QuestionRessource($question);
            }
        }
        if($q[0] === null){
            return ResponseBuilder::error(422, null, "Le formulaire n'a pas de section");
        }
        return ResponseBuilder::success(new QuestionCollection($q));
    }
    function questionList(){
        $question = Choice::all();
        return ResponseBuilder::success(new QuestionCollection($question));
    }
    function store(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'num_question' => 'required',
                'libelle' => 'required',
                'type' => 'required',
                'num_sec' => 'required',
                'form_id' => 'required',
            ],
            [
                'num_question.required' => 'le numéro de la question est requis',
                'libelle.required' => 'le libelle est requis',
                'type.required' => 'le type est requis',
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
        $question = new Question();
        $question->num_question = $request->get('num_question');
        $question->libelle = $request->get('libelle');
        $question->type = $request->get('type');
        $question->num_sec = $request->get('num_sec');
        $question->form_id = $request->get('form_id');
        $question->min = $request->get('min');
        $question->max = $request->get('max');
        $question->pas = $request->get('pas');
        $question->save();

        return ResponseBuilder::success(new QuestionRessource($question));
    }
}
