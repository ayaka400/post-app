<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Retrieved the details of the user that we want to display in the profile page
     */
    public function show($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.show')->with('user', $user);
    }

    /**
     * Method to open edit page
     */
    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.profile.edit')->with('user', $user);
    }

    /**
     * Method to perform the update of user details
     */
    public function update(Request $request){
        # 1. Validate the data first
        $request->validate([
            'name' => 'required|min:1|max:50',
            'email' => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar' => 'mimes:jpeg,jpg,png,gif|max:1048',
            'introduction' => 'max:100'
        ]);

        # 2. Save the data
        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        # 3. Check if there is an uploaded avatar
        if ($request->avatar) {
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        # Save
        $user->save();

        # Redirect to profile page update updating
        return redirect()->route('profile.show', Auth::user()->id);
    }


    public function followers($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);
    }

    public function following($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }
}
