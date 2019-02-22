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
        $this->limit = $this->request->input('limit');
        $this->offset = $this->request->input('offset');
        

    }

    public function index(){

        $target = DB::table('target');
        

        if($this->id_cms_users){

            $target->where('id_cms_users',$this->id_cms_users);
        }

        if($this->created_at){

            $target->where('created_at','like','%'.$this->created_at.'%');
        }

        if($this->id_mst_branch){
            
            $target->where('id_mst_branch',$this->id_mst_branch);
        
        }

        if($this->category){

            $target->where('category','like','%'.$this->category.'%');
        
        }

        if($this->no_contract){

            $target->where('no_contract','like','%'.$this->no_contract.'%');

        }

       
        if($this->first_name){

            $target->where('first_name','like','%'.$this->first_name.'%');

        }

        
        if($this->provider_1){

        $target->where('provider_1','like','%'.$this->provider_1.'%');

        }

        //test

        
        if($this->provider_2){

            $target->where('provider_2','like','%'.$this->provider_2.'%');

        }

        
        if($this->kelurahan){

            $target->where('kelurahan','like','%'.$this->kelurahan.'%');

        }

        
        if($this->kecamatan){

            $target->where('kecamatan','like','%'.$this->kecamatan.'%');

        }

        
        if($this->kabupaten){

            $target->where('kabupaten','like','%'.$this->kabupaten.'%');

        }

        if($this->provinsi){

            $target->where('provinsi','like','%'.$this->provinsi.'%');

        }

        if($this->id_target_mst_status){

            $target->where('id_target_mst_status',$this->id_target_mst_status);

        }

        if($this->offset){

            $target->offset($this->offset);

        }

        if($this->limit){

            $target->limit($this->limit);

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
