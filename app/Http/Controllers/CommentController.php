<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $post_id)
    {
        # 1. Validate the data
        $request->validate(
            [
                'comment_body' . $post_id => 'required|max:150'
            ],
            [
                'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
                'comment_body' . $post_id . '.max' => 'The comment must not have more than 150 characters.'
            ]
        );

        # 2. get and store the actual comments
        $this->comment->body = $request->input('comment_body' . $post_id);

        # 3. get and store the id of the owner of the comments
        $this->comment->user_id = Auth::user()->id;
        
        # 4. get and store the id of the post being commented on
        $this->comment->post_id = $post_id;
        
        # 5. excute the save()
        $this->comment->save();

        # 6. redirect the user to the show post page 
        return redirect()->route('post.show', $post_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->comment->destroy($id);
        return redirect()->back();
    }
}
