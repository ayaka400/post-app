<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;

    protected $table = 'category_post';
    protected $fillable = ['post_id', 'category_id'];
    # create() or createMany() method

    public $timestamps = false;

    #Get the name of the categories
    public function category(){
        return $this->belongsTo(Category::class);
    }
    
}
