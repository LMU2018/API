<?php
namespace App\Http\Controllers;
use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
class AuthController extends BaseController 
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 0.1 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'npm'     => 'required',
            'password'  => 'required',
            'device_id'
        ]);


        // Find the user by email
        $user = User::select('cms_users.*','mst_branch.id  as id_mst_branch','mst_branch.branch_name')->where('npm', $this->request->input('npm'))->join('mst_outlet','mst_outlet.id','cms_users.id_mst_outlet')
        ->join('mst_branch','mst_branch.id','mst_outlet.id_mst_branch')->first();
        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the 
            // below respose for now.
            // return response()->json([
            //     'error' => 'Npm does not exist.'
            // ], 400);
            $api_message = "NPM Tidak ditemukan";
                
            $data = ['api_status' => 0,
            'api_message' => $api_message];
                    
            return response()->json($data,400);

        }
        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {

            $device_id = $this->request->input('device_id');

            if (isset($device_id)){

                User::where('npm',$this->request->input('npm'))
                ->update(['device_id' => 
                $device_id]);

                // $api_message = "Berhasil Login";
                
                // $data = ['api_status' => 1,
                // 'api_message' => $api_message,
                // 'token' => $this->jwt($user)];

                // $data = array_merge($data,$user->toArray());

                // return response()->json($data,200);

               

            }

                $api_message = "Berhasil Login";
                
                $data = ['api_status' => 1,
                'api_message' => $api_message,
                'token' => $this->jwt($user)];
                
                $data = array_merge($data,$user->toArray());

                return response()->json($data,200);
                
                // return response()->json([
                //     'token' => $this->jwt($user)
                // ], 200);

            
        }
        // Bad Request response
        // return response()->json([
        //     'error' => 'password is wrong.'
        // ], 400);

        $api_message = "Password Salah";
                
        $data = ['api_status' => 0,
        'api_message' => $api_message];
                
        return response()->json($data,400);
    }
}