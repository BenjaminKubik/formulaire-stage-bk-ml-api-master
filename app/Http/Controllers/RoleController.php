<?php

namespace App\Http\Controllers;


use App\Http\Resources\RoleCollection;
use App\Models\Role;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class RoleController extends Controller
{
    function getRoleList(){
        return ResponseBuilder::success(new RoleCollection(Role::all()));
    }
}
