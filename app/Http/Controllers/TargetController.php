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

        //Note : Target Listing pada android hanya membutuhkan parameter id_cms_users dan id_target_mst_status , pada
        // web tidak membutuhkan parameter id_target_mst_status , jadi logic android di taruh di id_target_mst_status

        $target = DB::table('target');

        if(!$this->id_cms_users){

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

            // $target->join()
            $target->select(DB::raw('target.id , 
            target.id_target_mst_status , 
            (select status from target_mst_status where target_mst_status.id = target.id_target_mst_status) as target_mst_status_status , 
            target.category ,
            target.first_name ,
            target.last_name, 
            cms_users.npm as cms_users_npm , 
            cms_users.name as cms_users_name , 
            target.updated_by ,
            (select recall from target_log where target_log.id_target=target.id order by id desc limit 1) as recall , 
            (select id_mst_log_desc from target_log where target_log.id_target=target.id order by id desc limit 1) as id_mst_log_desc , 
            (select id_mst_log_status from mst_log_desc where id=id_mst_log_desc)as id_mst_log_status , 
            (select description from mst_log_desc where mst_log_desc.id=id_mst_log_desc) as description ,
            (select status from mst_log_status where mst_log_status.id=id_mst_log_status) as status , 
            (select id_mst_visum_status from target_visum where target_visum.id_target = target.id order by id desc limit 1) id_mst_visum_status , 
            (select revisit from target_visum where target_visum.id_target = target.id order by id desc limit 1	) as revisit , 
            (select status from mst_visum_status where mst_visum_status.id = id_mst_visum_status) as visit_status , 
            (select created_at from target_visum where target_visum.id_target = target.id order by id desc limit 1) as created_at_target_visum , 
            (select created_at from target_log where target_log.id_target=target.id order by created_at desc limit 1) as created_at_target_log , 
            target.created_at '));
            $target->join('cms_users','target.id_cms_users','cms_users.id');

            if($this->id_target_mst_status != 4){
            $target->where('id_target_mst_status',$this->id_target_mst_status);
            }

            if($this->id_target_mst_status == 1){

                $target->whereRaw("(select id from target_visum WHERE target_visum.id_target = target.id limit 1) is null");
                $target->orderBy('target.created_at','DESC');
            }else if($this->id_target_mst_status == 2){

                $target->orderBy('target.updated_at','DESC');
            }else if($this->id_target_mst_status == 3 || $this->id_target_mst_status == 5){

                $target->orderBy('created_at_target_log','DESC');
            }else if($this->id_target_mst_status == 4){

                $target->whereRaw("(SELECT target_visum.id from target_visum where target_visum.id_target = target.id limit 1) is not null");
                $target->orderBy('created_at_target_visum','DESC');
            }

        }else{

            $target->orderBy('target.id','desc');
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
