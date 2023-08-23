<?php

namespace App\Http\Controllers;

use App\Utils\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TestingController extends Controller
{
    use HttpResponse;
    public function test(Request $request)
    {
        // dd(Route::getRoutes()->getAllRoutes());\
        $routes = Route::getRoutes()->getRoutes();
        $routes = array_map(function ($route) {
            return [
                'uri' => $route->uri,
                'methods' => $route->methods[0],
            ];
        }, $routes);
        
        // return $this->success(json_encode(Route::getRoutes()));
        // return $this->error('Unauthenticated',401);
    }
}
