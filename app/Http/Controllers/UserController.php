<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    public function getAllUsers() {
        $allUsers = User::paginate(10);
        return response()->json([
            'users' => $allUsers,
        ]);
    }

    public function getUsersById($id) {
        $user = User::find($id);
        return response()->json([
            'users' => $user,
        ]);
    }

    public function postInsertUsers(request $request) {
        $newUser = User::create($request->all());
        $newUser->save();
        return json_encode([
            'message' => 'Successfully created new user',
        ]);
    }

    public function putUpdateUsersById(request $request, $id){
        $selectedUser = User::find($id);
        $selectedUser->update($request->all());
        $selectedUser->save();
        return json_encode([
            'message' => 'Successfully updated user',
        ]);
    }

    public function deleteDestroyUsersById($id) {
        User::find($id)->delete();
        return json_encode([
            'message' => 'Successfully deleted user',
        ]);
    }
}
