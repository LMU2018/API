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
        $this->id = $this->request->input('id');
        $this->created_at = $this->request->input('created_at');
         $this->updated_at = $this->request->input('updated_at');
         $this->id_mst_branch = $this->request->input('id_mst_branch');
         $this->id_mst_data_source = $this->request->input('id_mst_data_source');
         $this->id_target_mst_status = $this->request->input('id_target_mst_status');
         $this->business_code = $this->request->input('business_code');
        $this->category = $this->request->input('category');
        $this->priority = $this->request->input('priority');
        $this->no_contract = $this->request->input('no_contract');
        $this->nopol = $this->request->input('nopol');
        $this->first_name = $this->request->input('first_name');
        $this->last_name = $this->request->input('last_name');
        $this->hp_1 = $this->request->input('hp_1');
        $this->hp_2 = $this->request->input('hp_2');
        $this->provider_1 = $this->request->input('provider_1');
        $this->provider_2 = $this->request->input('provider_2');
        $this->job = $this->request->input('job');
        $this->address = $this->request->input('address');
        $this->kelurahan = $this->request->input('kelurahan');
        $this->kecamatan = $this->request->input('kecamatan');
        $this->kabupaten = $this->request->input('kabupaten');
        $this->provinsi = $this->request->input('provinsi');
        $this->id_cms_users = $this->request->input('id_cms_users');
        $this->updated_by = $this->request->input('updated_by');
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

    //Update Target Global column ga wajib diisi
    public function update(){
        try {
            $target = Target::findOrFail($this->id);
            if ($target) {
                if($this->updated_at){
                    $target->updated_at = $this->updated_at;
                }
                if($this->id_mst_branch){
                    $target->id_mst_branch = $this->id_mst_branch;
                }
                if($this->id_mst_data_source){
                    $target->id_mst_data_source = $this->id_mst_data_source;
                }
                if($this->id_target_mst_status){
                    $target->id_target_mst_status = $this->id_target_mst_status;
                }
                if($this->business_code){
                     $target->business_code = $this->business_code;
                }
                 if($this->category){
                    $target->category = $this->category;
                }
                 if($this->no_contract){
                   $target->no_contract = $this->no_contract;
                }
                if($this->priority){
                  $target->priority = $this->priority;
                }
                if($this->nopol){
                  $target->nopol = $this->nopol;
                }    
                if($this->first_name){
                  $target->first_name = $this->first_name;
                }
                if($this->last_name){
                   $target->last_name = $this->last_name;
                }
                if($this->hp_1){
                    $target->hp_1 = $this->hp_1;
                }
                if($this->hp_2){
                    $target->hp_2 = $this->hp_2;
                }
                if($this->provider_1){
                   $target->provider_1 = $this->provider_1;
                }
                 if($this->provider_2){
                   $target->provider_2 = $this->provider_2;
                }
                if($this->job){
                  $target->job = $this->job;
                }
                if($this->address){
                   $target->address = $this->address;
                }
                if($this->kelurahan){
                   $target->kelurahan = $this->kelurahan;
                }
                if($this->kecamatan){
                   $target->kecamatan = $this->kecamatan;
                }
                if($this->kabupaten){
                   $target->kabupaten = $this->kabupaten;
                }
                if($this->provinsi){
                   $target->provinsi = $this->provinsi;
                }
                if($this->id_cms_users){
                    $target->id_cms_users = $this->id_cms_users;
                }
                if($this->updated_by){
                     $target->updated_by = $this->updated_by;
                }

                $target->save();
                $res['api_status'] = 1;
                $res['message'] = 'success';
                $res['data'] = $target;
                return response($res, 200);
            } else {
                $res['success'] = false;
                $res['message'] = 'data not found';
                return response($res, 200);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['success'] = false;
            $res['message'] = $ex->getMessage();
            return response($res, 500);
        }
    }

    //untuk proses assignment
    public function assignment(){
        try {
            $target = Target::findOrFail($this->id);
            if ($target) {
               
                if(!$this->id_target_mst_status){
                     $res['api_status'] = 0;
                $res['message'] = 'id_target_mst_status require';
                return response($res, 200);
                }else {
                    $target->id_target_mst_status = $this->id_target_mst_status;
                }

                if(!$this->id_cms_users){
                     $res['api_status'] = 0;
                $res['message'] = 'id_cms_users require';
                return response($res, 200);
                }else {
                    $target->id_cms_users = $this->id_cms_users;
                }

                if(!$this->updated_by){
                     $res['api_status'] = 0;
                $res['message'] = 'updated_by require';
                return response($res, 200);
                }else {
                    $target->updated_by = $this->updated_by;
                }

                $target->save();
                $res['api_status'] = 1;
                $res['message'] = 'success';
                $res['data'] = $target;
                return response($res, 200);
            } else {
                $res['success'] = false;
                $res['message'] = 'data not found';
                return response($res, 200);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['success'] = false;
            $res['message'] = $ex->getMessage();
            return response($res, 500);
        }
    }


}
