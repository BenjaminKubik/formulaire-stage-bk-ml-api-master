<?php

namespace App\Http\Controllers;

use App\Http\Resources\FormRessource;
use App\Http\Resources\FormsCollection;
use App\Http\Resources\SectionCollection;
use App\Http\Resources\SectionRessource;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use function PHPUnit\Framework\isEmpty;

class SectionController extends Controller
{
    function sectionList(){
        $section = Section::all();
        return ResponseBuilder::success(new SectionCollection($section));
    }
    function show($id){
        $sections = Section::all();

        $s = [];
        foreach ($sections as $section){
            if ($section->toArray()['form_id'] === $id){
                $s[] = new SectionRessource($section);
            }
        }
        if($s[0] === null){
            return ResponseBuilder::error(422, null, "Le formulaire n'a pas de section");
        }
        return ResponseBuilder::success(new SectionCollection($s));
    }
    function store(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'titre' => 'required',
                'num_sec' => 'required',
                'form_id' => 'required',
            ],
            [
                'titre.required' => 'le titre est requis',
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
        $section = new Section();
        $section->titre = $request->get('titre');
        $section->num_sec = $request->get('num_sec');
        $section->form_id = $request->get('form_id');
        $section->save();

        return ResponseBuilder::success(new SectionRessource($section));
    }
}
