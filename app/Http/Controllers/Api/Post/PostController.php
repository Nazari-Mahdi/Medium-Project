<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{


    public function index(){              //  Returning All Created Posts

        return Post::all();
    }




    public function show($id){         //  Showing Specific Post

        $post = Post::findOrFail($id);

        return response(['Post' => $post]);
    }



    public function store(CreatePostRequest  $request){     //  Creating New Post

        $createData = $request->all();

        if ($request->hasFile('image')){
            $image = $request['image']->store('post-image');

            $createData['image'] = $image ;
        }
        if ($request->hasFile('user_image')){
            $image = $request['user_image']->store('post-user-image');

            $createData['user_image'] = $image ;
        }


        $post = Post::create($createData);

        return response(['Message' =>'Post Created Successfully' , 'Post' => $post]);
    }



    public function update(UpdatePostRequest  $request , $id){      //    Updating Post

        $post = Post::findOrFail($id);

        $updateData = $request->all();

        if ($request->hasFile('image')){

            $image = $request['image']->store('post-image');

            $post->deleteImage();
            $updateData['image'] = $image;
        }


        $post->update($updateData);

        return response(['Message' => 'Post Updated Successfully' , 'Post' => $post]);
    }




    public function destroy($id){            //  Deleting Post

        $post = Post::findOrFail($id);

        $post->deleteImage();

        $post->delete();
    }
}
