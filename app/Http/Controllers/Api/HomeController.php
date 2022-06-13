<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Movie;
use App\Models\User;
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
        $replaces = ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Film-Noir', 'Game-Show', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'News', 'Reality-TV', 'Romance', 'Sci-Fi', 'Sport', 'Talk-Show', 'Thriller', 'War', 'Western'];
        $searchs = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26'];

        $item = DB::table('movies')->limit(10)->get(['*']);
        for ($i=0; $i < count($item); $i++) {
            $item[$i]->genres = explode('|', $item[$i]->genres);
            $item[$i]->genres = str_replace($searchs, $replaces, $item[$i]->genres);}
        #echo $item[0]->title;
        return response()->json([
            'success' => true,
            'results' => $item
        ]);
    }

    public function getPopular() {
        $item = DB::table('movies')->where('rating', '>', '6.5')->where('year', '>', '2018')->orderBy('year', 'desc')->get(['*']);
        return response()->json([
            'success' => true,
            'results' => $item
        ]);
    }

    public function getMovieToday() {
        $replaces = ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Film-Noir', 'Game-Show', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'News', 'Reality-TV', 'Romance', 'Sci-Fi', 'Sport', 'Talk-Show', 'Thriller', 'War', 'Western'];
        $searchs = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26'];
        //$item = DB::table('movies')->where('year', '>', '2018')->orderBy('year', 'desc')->get(['*']);
        $item = Movie::inRandomOrder()->limit(10)->get();
        // return response()->json([
        //     'success' => true,
        //     'results' => $item
        // ]);
        for ($i=0; $i < count($item); $i++) {
            $item[$i]->genres = explode('|', $item[$i]->genres);
            $item[$i]->genres = str_replace($searchs, $replaces, $item[$i]->genres);}
        #echo $item[0]->title;
        return response()->json([
            'success' => true,
            'results' => $item
        ]);
    }

    public function getSearchedMovies(Request $request) {
        $query = (string) $request->get('query');

        //$a = DB::table('moviezz');

        $item = Movie::FullTextSearch($query)->get();
        //echo $query;
        //$item = DB::table('movies')->where('title', 'LIKE', '%'.$query.'%')->get(['*']);
        //$item = DB::select('select * from movies where title like "%'.$query.'%"');
        //$item = DB::select('select * from movies where soundex(title) = soundex("'.$query.'")');
        //$item = DB::select('select match (title) from movies against ('.$term.')');

        $replaces = ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Film-Noir', 'Game-Show', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'News', 'Reality-TV', 'Romance', 'Sci-Fi', 'Sport', 'Talk-Show', 'Thriller', 'War', 'Western'];
        $searchs = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26'];

        //$item = DB::table('movies')->limit(10)->get(['*']);
        for ($i=0; $i < count($item); $i++) {
            $item[$i]->genres = explode('|', $item[$i]->genres);
            $item[$i]->genres = str_replace($searchs, $replaces, $item[$i]->genres);}
        #echo $item[0]->title;
        return response()->json([
            'success' => true,
            'results' => $item
        ]);
    }

    public function comments(Request $request) {
        $comments = Comment::where('movieId', $request->movieId)->get();

        foreach ($comments as $comment) {
            $comment->user;
            //$comment->movie;
        }
        return response()->json([
            'success' => true,
            'results' => $comments
        ]);
    }

    public function create(Request $request) {
        $comment = new Comment;
        $comment->movieId = $request->movieId;
        $comment->userId = Auth::user()->userId;
        $comment->comment = $request->comment;
        $comment->save();
        return response()->json([
            'success' => true,
            'results' => $comment
        ]);
    }

    public function update(Request $request) {
        $comment = Comment::find($request->comment_id);
        
        //check if user edit
        if($comment->userId != Auth::user()->userId){
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access '
            ]);
        }
        $comment->comment = $request->comment;
        $comment->update();
        return response()->json([
            'success' => true,
            'results' => $comment
        ]);
    }

    public function delete(Request $request) {
        $comment = Comment::find($request->comment_id);
        if($comment->userId != Auth::user()->userId){
            return response()->json([
                'success' => false,
                'message' => 'unauthorized access '
            ]);
        }

        $comment->delete();
        return response()->json([
            'success' => true,
            'results' => $comment
        ]);
    }

    public function movieWithComments(Request $request) {
        $comment = Comment::where('movieId', $request->movieId)->get();
        return response()->json([
            'success' => true,
            'results' => $comment
        ]);
    }

    public function getMovieDetail(Request $request) {
        $replaces = ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Film-Noir', 'Game-Show', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'News', 'Reality-TV', 'Romance', 'Sci-Fi', 'Sport', 'Talk-Show', 'Thriller', 'War', 'Western'];
        $searchs = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26'];
        
        $item = DB::table('movies')->where('movieId', $request->id)->get(['*']);
        $item[0]->genres = explode('|', $item[0]->genres);
        $item[0]->genres = str_replace($searchs, $replaces, $item[0]->genres);

        $item[0]->duration = str_replace(' min', '', $item[0]->duration);

        $item[0]->director_cast = str_replace(['Directors: ','Director: ', 'Stars: '],['','',''],explode(' | ', $item[0]->director_cast));
        $item[0]->director = explode(', ', $item[0]->director_cast[0]);
        $item[0]->cast = explode(', ', $item[0]->director_cast[1]);

        //$comments = Comment::where('movieId',$request->id)->get();
        
        //$item[0]->comments = $comments;

        unset($item[0]->director_cast);
        return response()->json([
            'success' => true,
            'results' => $item[0],
            //'comments' => $comments
        ]);
    }
}
