<?php



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post(
    'auth/login', 
    [
       'uses' => 'AuthController@authenticate'
    ]
);
$router->group(
    ['middleware' => 'jwt.auth','cors'], 
    function() use ($router) {
        $router->get('users', function() {
            $users = \App\User::all();
            return response()->json($users);
        });
        $router->get('target/targetsearch', 'TargetController@targetSearch');
        $router->get('target/targetnote', 'TargetController@targetNote');
        $router->get('target/targetvisum', 'TargetController@targetVisum');
        $router->get('target/targetlog', 'TargetController@targetLog');
        $router->get('target/index', 'TargetController@index');
<<<<<<< HEAD
        $router->get('target/targetDetailTotal', 'TargetController@targetDetailTotal');
        $router->get('target/targetDetail', 'TargetController@targetDetail');
        $router->put('target/update', 'TargetController@update');
        $router->put('target/assignment', 'TargetController@assignment');
=======
        $router->get('target/assignment', 'TargetController@dataAssignment');
        $router->put('target/update', 'TargetController@update');
        $router->put('target/assignment', 'TargetController@assignment');
         $router->get('target/listing', 'TargetController@listing');


        $router->get('users/index', 'UsersController@index');

       
>>>>>>> 8ba2821a1d10126f0260e0876ce0432d5752ba25
        
    }
);
