<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [ 'title'  , 'description' , 'image' , 'user_id' , 'user_image' , 'user_name' ,'user_email'];

    protected $table = "posts";

    public function user(){

        return $this->belongsTo(User::class , 'user_id');
    }

    public function deleteImage(){

        \Storage::delete($this->image);
    }

}
