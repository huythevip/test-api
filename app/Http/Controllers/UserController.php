<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    public function index() {
        $allUsers = User::paginate(10);
        return response()->json([
            'users' => $allUsers,
        ]);
    }

    public function show($id) {
        $user = User::find($id);
        return response()->json([
            'users' => $user,
        ]);
    }

    public function store(request $request) {
        $newUser = User::create($request->all());
        $newUser->save();
        return response()->json([
            'message' => 'Successfully created new user',
        ]);
    }

    public function update(request $request, $id){
        $selectedUser = User::find($id);
        $selectedUser->update($request->all());
        $selectedUser->save();
        return response()->json([
            'message' => 'Successfully updated user',
        ]);
    }

    public function delete($id) {
        User::find($id)->delete();
        return response()->json([
            'message' => 'Successfully deleted user',
        ]);
    }
}
