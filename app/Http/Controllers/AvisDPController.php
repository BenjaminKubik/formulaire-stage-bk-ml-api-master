<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvisDPResource;
use App\Models\AvisDP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class AvisDPController extends Controller {
/*    public function __construct() {
        $this->middleware('api', ['except' => ['index', 'show']]);
    }*/




    function index(Request $request) {
        $avisId = $request->get('avisDP', null);
        $userId = $request->get('user', null);
        $email = $request->get('email', null);
        $note = $request->get('note', null);

        if (isset($avisId)) {
            $avisDP = AvisDP::where('user_id', $avisId)->get();
        } elseif (isset($userId)) {
            $avisDP = AvisDP::where('avis_id', $userId)->get();
        } elseif (isset($email)) {
            $avisDP = AvisDP::where('email', '<=', $email)->get();
        } elseif (isset($note)) {
            $avisDP = AvisDP::where('note', '<=', $note)->get();
        } else {
            $avisDP = AvisDP::all();
        }

        return ResponseBuilder::success( new AvisDPResource());
    }

    /*function show($id) {
        $avis = AvisDP::findOrFail($id);
        return ResponseBuilder::success(new JeuxDetailsResource($avis), 200, null);
    }*/

    function store(Request $request) {
        $this->middleware('avis:api');

        Log::info('Requête : ' . json_encode($request));
        $validator = Validator::make($request->all(),
            [
                'nom' => 'required|unique:avisDP|between:10,100',
                'note' => 'required',
            ],
            [
                'nom.required' => 'Le nom est requis',
                'nom.unique' => 'Le nom doit être unique',
                'note.required' => 'La description est requise',
                'numeric' => ':attribute est un entier',
                'between' => ':attribute doit être entre :min et :max',
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
        $avis = new AvisDP();
        $avis->nom = $request->nom;
        $avis->note = $request->note;
        //$avis->url_media = $request->get('url_media', 'images/no-image.png');
        $avis->save();
        if (isset($request->note)) {
            Log::info($request->note);
            $avis->commentaires()->attach($request->note);
        }
        $avis->save();

        /*
         *  Code en attente traitement de l'upload d'image
         *
                   if($request->file('image') !== null){
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();

                    // File upload location
                    $location = public_path().'/images/';
                    $filename = uniqid().'.'.$extension;

                    // Upload file
                    $file->move($location, $filename);

                    $avis->url_media = '/imagesjeux/'.$filename;
                );
        */
        $avis->save();
        return ResponseBuilder::success(new AvisDPResource($avis));
    }


}
