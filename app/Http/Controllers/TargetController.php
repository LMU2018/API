<?php

namespace App\Http\Controllers;
use App\Target;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;

class TargetController extends Controller
{
    //

    private $id_cms_users ,$created_at ,$id_mst_branch ,
    $category ,$no_contract,$first_name ,$provider_1 ,$provider_2 ,
    $kelurahan ,$kecamatan ,$kabupaten ,$provinsi ,$id_target_mst_status ,
    $limit ,$offset;

    public function __construct(Request $request)
    {
        //
        $this->request = $request;
        $this->id_cms_users = $this->request->input('id_cms_users');
        $this->created_at = $this->request->input('created_at');
        $this->id_mst_branch = $this->request->input('id_mst_branch');
        $this->category = $this->request->input('category');
        $this->no_contract = $this->request->input('no_contract');
        $this->first_name = $this->request->input('first_name');
        $this->provider_1 = $this->request->input('provider_1');
        $this->provider_2 = $this->request->input('provider_2');
        $this->kelurahan = $this->request->input('kelurahan');
        $this->kecamatan = $this->request->input('kecamatan');
        $this->kabupaten = $this->request->input('kabupaten');
        $this->provinsi = $this->request->input('provinsi');
        $this->id_target_mst_status = $this->request->input('id_target_mst_status');
        $this->imit = $this->request->input('limit');
        $this->offset = $this->request->input('offset');
        

    }

    public function index(){

        $target = Target::select('*');
        

        if($this->id_cms_users){

            $target->where('id_cms_users',$this->id_cms_users);
        }

        if($this->created_at){

            $target->where('created_at',$created_at);
        }

        if($this->id_mst_branch){
            
            $target->where('id_mst_branch',$id_mst_branch);
        
        }

        if($this->category){

            $target->where('category',$category);
        
        }

        if($this->no_contract){

            $target->where('no_contract',$no_contract);

        }

       
        if($this->first_name){

            $target->where('first_name',$first_name);

        }

        
        if($this->provider_1){

        $target->where('provider_1',$provider_1);

        }

        //test

        
        if($this->provider_2){

            $target->where('provider_2',$provider_2);

        }

        
        if($this->kelurahan){

            $target->where('kelurahan',$kelurahan);

        }

        
        if($this->kecamatan){

            $target->where('kecamatan',$kecamatan);

        }

        
        if($this->kabupaten){

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
