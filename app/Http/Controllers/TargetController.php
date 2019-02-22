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

    public function index(Request $request){

        $id_cms_users = $request->input('id_cms_users');
        $created_at = $request->input('created_at');
        $id_mst_branch = $request->input('id_mst_branch');
        $category = $request->input('category');
        $no_contract = $request->input('no_contract');
        $first_name = $request->input('first_name');
        $provider_1 = $request->input('provider_1');
        $provider_2 = $request->input('provider_2');
        $kelurahan = $request->input('kelurahan');
        $kecamatan = $request->input('kecamatan');
        $kabupaten = $request->input('kabupaten');
        $provinsi = $request->input('provinsi');
        $id_target_mst_status = $request->input('id_target_mst_status');
        $limit = $request->input('limit');
        $offset = $request->input('offset');

        $target = Target::select('*');

        if(isset($id_cms_users)){

            $target->where('id_cms_users',$id_cms_users);
        }

        if(isset($created_at)){

            $target->where('created_at',$created_at);
        }

        if(isset($id_mst_branch)){
            
            $target->where('id_mst_branch',$id_mst_branch);
        
        }

        if(isset($category)){

            $target->where('category',$category);
        
        }

        if(isset($no_contract)){

            $target->where('no_contract',$no_contract);

        }

       
        if(isset($first_name)){

            $target->where('first_name',$first_name);

        }

        
        if(isset($provider_1)){

        $target->where('provider_1',$provider_1);

        }

        
        if(isset($provider_2)){

            $target->where('provider_2',$provider_2);

        }

        
        if(isset($kelurahan)){

            $target->where('kelurahan',$kelurahan);

        }

        
        if(isset($kecamatan)){

            $target->where('kecamatan',$kecamatan);

        }

        
        if(isset($kabupaten)){

            $target->where('kabupaten',$kabupaten);

        }

        $result = $target->get();
        $api_message = 'success';

        $data = ['api_status' => 1,
        'api_message'=> $api_message,
        'data' => $result];

        return response()->json($data);


    }

    public function target(){

        
    }


}
