<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    private $follow;

    public function __construct(Follow $follow){
        $this->follow = $follow;
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
    public function store($user_id)
    {
        /**
         * Note: Int this situation, the AUTH user is doing the action.
         */
        $this->follow->follower_id = Auth::user()->id; // the follower (AUTH User is always the follower)
        $this->follow->following_id = $user_id; // the user being followed
        $this->follow->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Follow $follow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        $this->follow
            ->where('follower_id', Auth::user()->id)
            ->where('following_id', $user_id)
            ->delete();

        return redirect()->back();
    }
}
