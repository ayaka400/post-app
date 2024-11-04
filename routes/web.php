<?php

/** routes related to regular users */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;

/** routes relateed to admin */
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CategoriesController;


Auth::routes();
Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [HomeController::class, 'index'])->name('index');

    /**
     * Route related to posts
     */

    # Route to open the create post page (create.blade.php)
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');

    # Route to insert data into the database
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');

    # Route to open the show post page
    Route::get('/post/{id}/show', [PostController::class, 'show'])->name('post.show');

    # Route to open the edit post page
    Route::get('/post//{id}/edit', [PostController::class, 'edit'])->name('post.edit');

    # Route to perform/excute the update
    Route::patch('/post/{id}/update', [PostController::class, 'update'])->name('post.update');

    # Route for deleting a post
    Route::delete('/post/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    /**
     * Route related to comments
     */
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');

    Route::delete('/comment/{id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');


    /**
     * Routes related to User details profile
     */
    Route::get('/profile/{id}/show', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile/update' , [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');

    Route::get('/prodile/{id}/following', [ProfileController::class, 'following'])->name('profile.following');
   
    
    /**
     * Routes related to likes
     */
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');

    Route::delete('/like/{post_id}/destroy', [LikeController::class, 'destroy'])->name('like.destroy');

    /**
     * Routes related to follow/unfollow
     */
    Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');

    Route::delete('follow/{user_id}/destroy', [FollowController::class, 'destroy'])->name('follow.destroy');


    /**
     * Routes user for Admin users
     */
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
        /**
         * Users
         */
        Route::get('users', [UsersController::class, 'index'])->name('users');
        Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
        Route::patch('/users/{user_id}/activate', [UsersController::class, 'activate'])->name('users.activate');

        /**
         * Posts
         */
        Route::get('/posts', [PostsController::class, 'index'])->name('posts');
        Route::delete('/post/{post_id}/hide', [PostsController::class, 'hide'])->name('post.hide');
        Route::patch('/post/{post_id}/unhide', [PostsController::class, 'unhide'])->name('post.unhide');

        /**
         * Categories
         */
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{category_id}/update', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/ategories/{category_id}/destroy', [CategoriesController::class, 'destroy'])->name('categories.destroy');

    });
});

