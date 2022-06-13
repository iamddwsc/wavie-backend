<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Movie extends Model
{
    use HasFactory;

    public $table = 'movies';
    protected $primaryKey = 'movieId';

    protected function fullTextWildcards($term)
   {
       // removing symbols used by MySQL
       $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
       $term = str_replace($reservedSymbols, '', $term);

       $words = explode(' ', $term);

       foreach ($words as $key => $word) {
           /*
            * applying + operator (required word) only big words
            * because smaller ones are not indexed by mysql
            */
           if (strlen($word) >= 1) {
               $words[$key] = '*' . $word  . '*';
           }
       }
       
       $searchTerm = implode(' ', $words);

       return $searchTerm;
   }

   public function scopeFullTextSearch($query, $term)
   {
       //$query = DB::table('movies')->select('*');
       $columns = 'title';
       //echo $query;
       $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

       return $query;
   }

   public function comments() {
       return $this->hasMany(Comment::class);
   }
}
