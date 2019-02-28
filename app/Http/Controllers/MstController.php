<?php

namespace App\Http\Controllers;
use App\MstVisumStatus;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;

class MstController extends Controller
{
    //

    public function __construct(){

    }

    public function listmstvisumstatus(){

        $result = MstVisumStatus::get();
        
        $data['api_message'] = "success";
        $data['api_status'] = 1;
        $data['data'] = $result;

        return response()->json($data);   
    }
}
