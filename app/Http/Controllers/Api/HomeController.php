<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    //
    protected function home() {
        return Storage::disk('public')->get('/images/1.jpg');
    }

    public function getFirstItem() {
        $item = DB::table('movies')->where('genres', 'LIKE', '%action%')->where('rating', '>', '8.8')->get();
        return response()->json([
            'success' => true,
            'message' => $item
        ]);
    }

    public function getFirst10Items() {
        $item = DB::table('movies')->limit(10)->get(['*']);
        #echo $item[0]->title;
        return response()->json([
            'success' => true,
            'results' => $item
        ]);
    }

    // public function test() {
    //     $image = 
    // }
}
