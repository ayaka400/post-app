<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * get all the user details from the users table
     */
    public function index(){
        $all_users = $this->user->withTrashed()->latest()->paginate(5);

        return view('admin.users.index')->with('all_users', $all_users);
    }

    /**
     * Method use to deactivate user
     */
    public function deactivate($id){
        $this->user->destroy($id);
        return redirect()->back();
    }

    /**
     * Method use to activate a user
     */
    public function activate($id){
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        // onlyTrashed() -- retrieve soft deleted records only
        // restore() -- This will un-delete a soft deleted user (model) 
        //               (This will set the deleted_at column "null")
        return redirect()->back();
    }
}
