<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->get('token');
        
        if(!$token) {
            // Unauthorized response if token not there

            $api_message = "Token not provided";
                
            $data = ['api_status' => 0,
            'api_message' => $api_message];
                    
            return response()->json($data,401);


            // return response()->json([
            //     'error' => 'Token not provided.'
            // ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {

            $api_message = "Token kadaluarsa";
                
            $data = ['api_status' => 0,
            'api_message' => $api_message];
                    
            return response()->json($data,400);

            // return response()->json([
            //     'error' => 'Provided token is expired.'
            // ], 400);
        } catch(Exception $e) {

            $api_message = "Error while decoding token";
                
            $data = ['api_status' => 0,
            'api_message' => $api_message];
                    
            return response()->json($data,400);

            // return response()->json([
            //     'error' => 'An error while decoding token.'
            // ], 400);
        }
        $user = User::find($credentials->sub);
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        return $next($request);
    }
}