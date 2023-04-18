<?php

namespace App\Http\Controllers;

use App\Http\Resources\FormsCollection;
use App\Http\Resources\MultipleAnswerCollection;
use App\Http\Resources\NumberAnswerCollection;
use App\Http\Resources\NumberAnswerRessource;
use App\Http\Resources\TextAnswerCollection;
use App\Http\Resources\TextAnswerRessource;
use App\Models\Forms;
use App\Models\MultipleAnswer;
use App\Models\NumberAnswer;
use App\Models\Question;
use App\Models\TextAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use function PHPUnit\Framework\isEmpty;

class AnswerController extends Controller
{
    function storeText(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'question_id' => 'required',
                'answer' => 'required',
            ],
            [
                'user_id.required' => "l'id utilisateur est requis",
                'question_id.required' => "L'id de la question est requis",
                'answer.required' => 'La réponse est requise',
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

        $answers = new TextAnswerCollection(TextAnswer::all());
        foreach ($answers as $a){
            if ($a->user_id == $request->get('user_id') && $a->question_id == $request->get('question_id')){
                $this->deleteTextAnswer($a->id);
            }
        }
        $answer = new TextAnswer();
        $answer->user_id = $request->get('user_id', null);
        $answer->question_id = $request->get('question_id', null);
        $answer->answer = $request->get('answer', null);
        $answer->save();
        return ResponseBuilder::success(new TextAnswerRessource($answer));

    }
    function storeNumber(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'question_id' => 'required',
                'answer' => 'required|numeric',
            ],
            [
                'user_id.required' => "l'id utilisateur est requis",
                'question_id.required' => "L'id de la question est requis",
                'answer.required' => 'La réponse est requise',
                'answer.numeric' => 'La réponse doit être numérique'
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

        $answers = new NumberAnswerCollection(NumberAnswer::all());
        foreach ($answers as $a){
            if ($a->user_id == $request->get('user_id') && $a->question_id == $request->get('question_id')){
                $this->deleteNumberAnswer($a->id);
            }
        }
        $answer = new NumberAnswer();
        $answer->user_id = $request->get('user_id', null);
        $answer->question_id = $request->get('question_id', null);
        $answer->answer = $request->get('answer', null);
        $answer->save();
        return ResponseBuilder::success(new NumberAnswerRessource($answer));
    }
    function storeMultiple(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'question_id' => 'required',
                'answer' => 'required',
            ],
            [
                'user_id.required' => "l'id utilisateur est requis",
                'question_id.required' => "L'id de la question est requis",
                'answer.required' => 'La réponse est requise',
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


        $answer = new MultipleAnswer();
        $answer->user_id = $request->get('user_id', null);
        $answer->question_id = $request->get('question_id', null);
        $answer->answer = $request->get('answer', null);
        $answer->save();

        return ResponseBuilder::success(new TextAnswerRessource($answer));
    }
    function getFormsAnswered($id){
        $tAns = TextAnswer::all()->where('user_id',$id)->toArray();
        $nAns = NumberAnswer::all()->where('user_id', $id)->toArray();
        $mAns = MultipleAnswer::all()->unique->where('user_id', $id)->toArray();
        $qIdTab = [];
        foreach ($tAns as $a){
            if (!in_array($a['question_id'], $qIdTab)){
                array_push($qIdTab, $a['question_id']);
            }
        }
        foreach ($nAns as $a){
            if (!in_array($a['question_id'], $qIdTab)){
                array_push($qIdTab, $a['question_id']);
            }
        }
        foreach ($mAns as $a){
            if (!in_array($a['question_id'], $qIdTab)){
                array_push($qIdTab, $a['question_id']);
            }
        }
        $quest = Question::all()->toArray();
        $fIdTab = [];
        foreach ($quest as $q){
            if (in_array($q['id'], $qIdTab) && !in_array($q['form_id'], $fIdTab)){
                array_push($fIdTab, $q['form_id']);
            }
        }
        $forms = Forms::all();
        $formTab = [];
        foreach ($forms as $f){
            if (in_array($f['id'], $fIdTab)){
                array_push($formTab, $f);
            }
        }
        return ResponseBuilder::success(new FormsCollection($formTab));

    }
    function deleteTextAnswer($id){
        TextAnswer::destroy($id);
    }
    function deleteNumberAnswer($id){
        NumberAnswer::destroy($id);
    }
    function deleteMultipleAnswer(Request $request){
        $mAns = new MultipleAnswerCollection(MultipleAnswer::all());
        foreach ($mAns as $a){
            if ($a->question_id == $request->get('question_id') && $a->user_id == $request->get('user_id')){
                MultipleAnswer::destroy($a->id);
            }
        }
    }
    function getSomething($uId, $fId){
        $answers = new TextAnswerCollection(TextAnswer::all());
        $ans = null;
        foreach ($answers as $a){
            if ($a->user_id === $uId && $a->question_id === $fId){
                $ans = $a;
                $t = TextAnswer::find($a->id);
                var_dump($t->id);
            }
        }
    }
    function getFormsUsersTextAnswers($uId, $fId){
        $tAns = TextAnswer::all()->where('user_id',$uId);
        $tab = [];
        foreach ($tAns as $tA){
            $form_id = Question::find($tA['question_id'])->form_id;
            if ($form_id == $fId){
                array_push($tab, $tA);
            }
        }
        return ResponseBuilder::success(new TextAnswerCollection($tab));
    }
    function getFormsUsersNumberAnswers($uId, $fId){
        $nAns = NumberAnswer::all()->where('user_id',$uId);
        $tab = [];
        foreach ($nAns as $nA){
            $form_id = Question::find($nA['question_id'])->form_id;
            if ($form_id == $fId){
                array_push($tab, $nA);
            }
        }
        return ResponseBuilder::success(new NumberAnswerCollection($tab));
    }
    function getFormsUsersMultipleAnswers($uId, $fId){
        $mAns = MultipleAnswer::all()->where('user_id',$uId);
        $tab = [];
        foreach ($mAns as $mA){
            $form_id = Question::find($mA['question_id'])->form_id;
            if ($form_id == $fId){
                array_push($tab, $mA);
            }
        }
        return ResponseBuilder::success(new MultipleAnswerCollection($tab));
    }
}
