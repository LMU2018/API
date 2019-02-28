<?php

namespace App\Http\Controllers;
use App\Target;
use App\TargetLog;
use App\TargetVisum;
use App\TargetNote;
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

        $this->id_mst_log_desc = $this->request->input('id_mst_log_desc');
        $this->null_desc = $this->request->input('null_desc');
        

    }

    public function index(){

        //Note : Target Listing pada android hanya membutuhkan parameter id_cms_users dan id_target_mst_status , pada
        // web tidak membutuhkan parameter id_target_mst_status , jadi logic android di taruh di id_target_mst_status

        $target = DB::table('target');

        if($this->id_cms_users){

            $target->where('target.id_cms_users',$this->id_cms_users);
        
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

    public function dataAssignment(){


        // $target = DB::table('target');
        $target = Target::select('target.id',
        'target.id_mst_branch',
        'target.category',
        'target.first_name',
        'target.no_contract',
        'target.provider_1',
        'target.provider_2',
        'target.kabupaten',
        'target.kecamatan',
        'target.kelurahan',
        'target.id_cms_users',
        'cms_users.name as cms_users_name',
        'target_log.id_mst_log_desc',
        'mst_log_desc.description');

        if($this->id){
            $target->where('target.id','=',$this->id);
        }

        if($this->id_cms_users){
            $target->where('target.id_cms_users',$this->id_cms_users);
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
        if($this->provider_1){
        $target->where('provider_1','like','%'.$this->provider_1.'%');
        }    
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
        if($this->provinsi){
            $target->where('provinsi','like','%'.$this->provinsi.'%');
        }
        if($this->offset){
            $target->offset($this->offset);
        }
        if($this->limit){
            $target->limit($this->limit);
        }

         if($this->id_mst_log_desc){
            $target->where('target_log.id_mst_log_desc',$this->id_mst_log_desc);
        }
        if($this->null_desc == 1){
            $target->whereNull('target_log.id');
        }
        

        $result = $target
        ->join('cms_users','target.id_cms_users','cms_users.id')
        ->leftJoin('target_log','target.id','target_log.id_target')
        ->leftJoin('mst_log_desc','target_log.id_mst_log_desc','mst_log_desc.id')
        // ->groupBy('target.id')
        ->get();
        $api_message = 'success';

        $data = ['api_status' => 1,
        'api_message'=> $api_message,
        'data' => $result];

        return response()->json($data);


    }

    public function listing(){


        // $target = DB::table('target');
        $target = Target::select('target.created_at',
        'target.updated_at',
        'target.id_mst_data_source',
        'target.id',
        'target.id_mst_branch',
        'mst_branch.branch_name',
        'target.business_code',
        'target.priority',
        'target.nopol',
        'target.last_name',
        'target.address',
        'target.provinsi',
        'target.updated_by',
        'target.id_target_mst_status',
        'target.category',
        'target.first_name',
        'target.no_contract',
        'target.provider_1',
        'target.provider_2',
        'target.kabupaten',
        'target.kecamatan',
        'target.kelurahan',
        'target.id_cms_users',
        'cms_users.name as cms_users_name',
        'target_log.id_mst_log_desc',
        'target_log.duration',
        'target_log.recall',
        'target_mst_status.status',
        'target_note.note',
        'target_visum.revisit',
        // 'mst_visum_status.status',
        'mst_log_desc.description');

        if($this->id){
            $target->where('target.id','=',$this->id);
        }

        if($this->id_cms_users){
            $target->where('target.id_cms_users',$this->id_cms_users);
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
        if($this->provider_1){
        $target->where('provider_1','like','%'.$this->provider_1.'%');
        }    
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
        if($this->provinsi){
            $target->where('provinsi','like','%'.$this->provinsi.'%');
        }
        if($this->offset){
            $target->offset($this->offset);
        }
        if($this->limit){
            $target->limit($this->limit);
        }

         if($this->id_mst_log_desc){
            $target->where('target_log.id_mst_log_desc',$this->id_mst_log_desc);
        }
        if($this->null_desc == 1){
            $target->whereNull('target_log.id');
        }
        

        $result = $target
        ->join('cms_users','target.id_cms_users','cms_users.id')
        ->leftJoin('target_log','target.id','target_log.id_target')
        ->leftJoin('mst_log_desc','target_log.id_mst_log_desc','mst_log_desc.id')
        ->leftJoin('mst_branch','target.id_mst_branch','mst_branch.id')
        ->leftJoin('target_mst_status','target.id_target_mst_status','target_mst_status.id')
        ->leftJoin('target_note','target.id','target_note.id_target')
        ->leftJoin('target_visum','target.id','target_visum.id_target')
        // ->Join('mst_visum_status','target_visum.id_mst_visum_status','mst_visum_status.')
        ->get();
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

    public function targetDetailTotal(){

        if(!$this->id){

            $data['api_message'] = "id required";
            $data['api_status'] = 0;    
            return response()->json($data);
        }

        if(!$this->id_cms_users){

            $data['api_message'] = "id_cms_users required";
            $data['api_status'] = 0;    
            return response()->json($data);

        }

        if($this->id && $this->id_cms_users){
            
            $target = Target::select(DB::raw("(select count(target_log.id) from target_log where target_log.id_target = target.id) as logTotal , 
            (select count(target_note.id) from target_note where target_note.id_target = target.id) as noteTotal ,
            (select count(target_visum.id) from target_visum where target_visum.id_target = target.id) as visumTotal "));

            $target->where('target.id_cms_users',$this->id_cms_users);
            $target->where('target.id',$this->id);

            $result = $target->get();

            $data['api_message'] = "success";
            $data['api_status'] = 1;

            // $data = ['api_message' => 'success',
            //         $result];

            $data =  array_merge($data,$result[0]->toArray());

            return response()->json($data);
        }


    }

    public function targetDetail(){
        
        if(!$this->id){

            $data['api_message'] = "id required";
            $data['api_status'] = 0;    
            return response()->json($data);
        }else{

            $target = Target::select(DB::raw('target.id , 
        mst_data_source.datasource , 
        target.id_target_mst_status , 
        target_mst_status.status , 
        target.business_code , 
        target.category ,
        target.priority , 
        target.no_contract , 
        target.nopol , 
        target.first_name , 
        target.last_name , 
        target.hp_1 , 
        target.hp_2 , 
        target.provider_1 , 
        target.provider_2 , 
        target.job , 
        target.address , 
        target.kelurahan , 
        target.kecamatan , 
        target.kabupaten , 
        target.provinsi , 
        cms_users.name as cms_users_name , 
        target_log.id_mst_log_desc as id_mst_log_desc, 
        mst_log_desc.description , 
        target_visum.id_mst_visum_status as id_target_visum  , 
        mst_visum_status.status as visum_status , 
        mst_log_desc.id_mst_log_status as id_mst_log_status'));

        $target->leftjoin('cms_users','target.id_cms_users','cms_users.id');
        $target->leftjoin('target_log','target.id','target_log.id_target');
        $target->leftjoin('mst_log_desc','target_log.id_mst_log_desc','mst_log_desc.id');
        $target->leftjoin('target_visum','target.id','target_visum.id_target');
        $target->leftjoin('mst_visum_status','target_visum.id_mst_visum_status','mst_visum_status.id');
        $target->leftjoin('mst_data_source','target.id_mst_data_source','mst_data_source.id');
        $target->leftjoin('target_mst_status','target.id_target_mst_status','target_mst_status.id');
        $target->where('target.id',$this->id);
        $target->orderBy('target_log.created_at','desc');
        $target->limit(1);
        

        $result = $target->get();

        $data['api_message'] = "success";
        $data['api_status'] = 1;

        // $data = ['api_message' => 'success',
        //         $result];

        $data =  array_merge($data,$result[0]->toArray());

        return response()->json($data);

        }


    }

    public function targetLog(){

        if(!$this->id){

            $data['api_message'] = "id required";
            $data['api_status'] = 0;    
            return response()->json($data);
        }

        if(!$this->id_cms_users){

            $data['api_message'] = "id_cms_users required";
            $data['api_status'] = 0;    
            return response()->json($data);

        }

        if($this->id && $this->id_cms_users){

            $targetLog = TargetLog::where('id',$this->id)->where('id_cms_users',$this->id_cms_users)->get();

            $data['api_message'] = "success";
            $data['api_status'] = 1;
            $data['data'] = $targetLog;   
            return response()->json($data);
        }
    }

    public function targetVisum(){

        if(!$this->id){

            $data['api_message'] = "id required";
            $data['api_status'] = 0;    
            return response()->json($data);
        }

        if(!$this->id_cms_users){

            $data['api_message'] = "id_cms_users required";
            $data['api_status'] = 0;    
            return response()->json($data);

        }

        if($this->id && $this->id_cms_users){

            $targetVisum = TargetVisum::where('id',$this->id)->where('id_cms_users',$this->id_cms_users)->get();

            $data['api_message'] = "success";
            $data['api_status'] = 1;
            $data['data'] = $targetVisum;   
            return response()->json($data);
        }

    }

    public function targetNote(){

        if(!$this->id){

            $data['api_message'] = "id required";
            $data['api_status'] = 0;    
            return response()->json($data);
        }else{

            $targetNote = TargetNote::where('id',$this->id)->get();

            $data['api_message'] = "success";
            $data['api_status'] = 1;
            $data['data'] = $targetNote;   
            return response()->json($data);
        }

    }

    public function targetSearch(){

        if(!$this->id_cms_users){

            $data['api_message'] = "id_cms_users required";
            $data['api_status'] = 0;    
            return response()->json($data);
        }
        
        if(!$this->first_name){

            $data['api_message'] = "first_name required";
            $data['api_status'] = 0;    
            return response()->json($data);
        }

        if($this->id_cms_users && $this->first_name){

            $targetSearch = DB::table('target');
             $targetSearch->select(DB::raw('target.id_cms_users,target.id , 
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
                target.created_at'));
            $targetSearch->join('cms_users','target.id_cms_users','cms_users.id');
            $targetSearch->where('target.id_cms_users',$this->id_cms_users);
            $targetSearch->where('target.first_name','like','%'.$this->first_name.'%');
            $targetSearch->orWhere('target.last_name','like','%'.$this->first_name.'%');
            $targetSearch->where('target.id_cms_users',$this->id_cms_users);
            
            $result = $targetSearch->get();

                
            $data['api_message'] = "success";
            $data['api_status'] = 1;
            $data['data'] = $result;

            return response()->json($data);
        }
    }


}
