<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post; //posts table
use App\Models\Category; //categories table

class PostController extends Controller
{
    # Difine the properties
    private $post;
    private $category;

    # Difine constructor
    public function __construct(Post $post, Category $category){
        $this->post = $post;
        $this->category = $category;
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
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        # Data coming from the Form

        #1: Validate
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'required|mimes:jpeg, jpg, png, gif|max:1048'
        ]);

        #2. Save the data
        $this->post->user_id = Auth::user()->id; //the owner of the post
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save();

        #3. Save the categories to the category_post table (PIVOT)
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $this->post->categoryPost()->createMany($category_post);

        #4. Go back to homepage
        return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);
        return view('users.posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        # Get the post that you want to edit
        $post = $this->post->findOrFail($id);

        # Get all the categories from the categories table
        $all_categories = $this->category->all();

        # Get the selected categories
        $selected_categories = [];
        foreach ($post->categoryPost as $category_post) { 
            $selected_categories[] = $category_post->category_id; 
        }

        return view('users.posts.edit')
            ->with('post', $post)
            ->with('all_categories', $all_categories)
            ->with('selected_categories', $selected_categories);  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) //the $id is the id ofthe post we want to update
    {
        #1. Validate the data
        $request->validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'mimes:jpeg,jpg,png,gif|max:1048'
        ]);      

        #2. Save the data into database
        $post = $this->post->findOrFail($id);
        $post->description = $request->description;

        //** Check if there are new image uploaded */
        if ($request->image) {
            $post->image = 'data:image/' . $request->image->extension() . ";base64," . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        /** Save the selected categories into the category_post table*/

        # 3. Delete the old selected categories
        $post->categoryPost()->delete();

        #4. Save the new categories into the table
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id'=> $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        #5. Redirect the user to the show post page (to confirm the update)
        return redirect()->route('post.show', $id);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $this->post->destroy($id); //Delete from posts where id = $id

        $this->post->findOrFail($id)->forceDelete();
        return redirect()->route('index');
    }

}
