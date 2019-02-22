<?php

namespace App\Http\Controllers;
use App\Target;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;

class TargetController extends Controller
{
    //

    public function __construct()
    {
        //
    }

    public function index(){

        $this->validate($this->request,[
            'id_cms_users',
            'created_at',

        ]);


    }

    public function target(){

        
    }


}
