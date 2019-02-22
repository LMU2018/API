<?php

namespace App\Http\Controllers;
use App\Target;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class TargetAssigment extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index($id)
    {
        return response()->json(ModelWarna::find($id));
    }
    public function login()
    {
        $credentials = request(['npm', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    public function coba(Request $request)
    {
        $limit = $request->input('limit');
        $category = $request->input('category');
        $no_contract = $request->input('no_contract');
        $provider_1 = $request->input('provider_1');
        $provider_2 = $request->input('provider_2');
        $kelurahan = $request->input('kelurahan');
        $kabupaten = $request->input('kabupaten');
        $provinsi = $request->input('provinsi');
        $cms_users_name = $request->input('cms_users_name');
        $id_mst_log_desc = $request->input('id_mst_log_desc');

        
        
        $query = Target::
        select('target.id','target.first_name','target.category','target.no_contract','target.provider_1','target.provider_2','target.kelurahan','target.kabupaten','target.provinsi','target.id_cms_users','cms_users.name','target.provider_2','target_log.id_target','target_log.id_mst_log_desc')
        ->leftjoin('target_log','target.id', '=', 'target_log.id_target')
        ->join('cms_users','target.id_cms_users','cms_users.id')
        ->limit($limit);

        if ($id_mst_log_desc == true)
    $query->where('id_mst_log_desc', '=', $id_mst_log_desc);

   if (isset($category))
    $query->where('category', '=', $category);

    if (isset($no_contract))
    $query->where('no_contract', '=', $no_contract);

    if (isset($provider_1))
    $query->where('provider_1', '=', $provider_1);

    if (isset($provider_2))
    $query->where('provider_2', '=', $provider_2);

    if ($kelurahan == true)
    $query->where('kelurahan', 'like', $kelurahan);

    if (isset($kabupaten))
    $query->where('kabupaten', '=', $kabupaten);

    if (isset($provinsi))
    $query->where('provinsi', '=', $provinsi);

    if ($cms_users_name == true)
    $query->where('cms_users.name', 'like', '%'.$cms_users_name.'%');


       

        $result = $query->get();
        

        if (count($result) == null) {
            $api_message = "data kosong";
        }else {
            $api_message = "success";
        }
        $data = ['api_status' => 1,
                'api_message' => $api_message,
                'data' => $result,];
        return response()->json($data);

        
        
        
        
    } #endregion
    

    //
}
