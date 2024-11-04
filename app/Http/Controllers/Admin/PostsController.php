<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    private $post;

    public function __construct(Post $post){
        $this->post = $post;
    }
    
    /**
     * Get all the posts from posts table
     */
    public function index(){
        $all_posts = $this->post->withTrashed()->latest()->paginate(5);
        return view('admin.posts.index')->with('all_posts', $all_posts);
    }

    /**
     * Method to hide the post
     */
    public function hide($id){
        $this->post->destroy($id);
        return redirect()->back();
    }

    /**
     * Method to unhide the post
     */
    public function unhide($id){
        $this->post->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
