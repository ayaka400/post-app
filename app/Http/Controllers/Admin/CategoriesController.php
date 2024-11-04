<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{
    private $category;
    private $post;

    public function __construct(Category $category, Post $post){
        $this->category = $category;
        $this->post = $post;
    }

    /**
     * Get all categories
     */
    public function index(){
        $all_categories = $this->category->orderBy('id', 'desc')->paginate(5);

        $uncategorized_count = 0;
        $all_posts = $this->post->all();
        foreach ($all_posts as $post){
            if ($post->categoryPost->count() == 0){
                $uncategorized_count++;
            }
        }

        return view('admin.categories.index')
            ->with('all_categories', $all_categories)
            ->with('uncategorized_count', $uncategorized_count);
    }

    /**
     * Method to store new category
     */
    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1|max:50|unique:categories,name'
        ]);
        $this->category->name = ucwords(strtolower($request->name));
        // ucwords() -- use to convert first character into uppercase
        // strtolower() -- use to convert words into lowercase
        $this->category->save();

        return redirect()->back();
    }

    /**
     * Method to do the actual update
     */
    public function update(Request $request, $id){
        
        $request->validate([
            'new_name' => 'required|min:1|max:50|unique:categories,name,' . $id
        ]);

        $category      = $this->category->findOrFail($id);
        $category->name = ucwords(strtolower($request->new_name));
        $category->save();

        return redirect()->back();
    }

    /**
     * Method to delete the category
     */
    public function destroy($id){
        $this->category->destroy($id);
        return redirect()->back();
    }
}
