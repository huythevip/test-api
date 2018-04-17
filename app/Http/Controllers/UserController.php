<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
class UserController extends Controller
{   
    //Define key:value array for sorting datatables.
    var $columnID = array('name', 'email', 'gender', 'birthday', 'location');

    public function selectData($id = null) {
        if (is_null($id)){
        $users = User::select('name', 'email', 'gender', 'birthday', 'location');
    }
    else {
        $users = User::where('id', '=', $id)->select('name', 'email', 'gender', 'birthday', 'location');
    };
        return $users;
    }



    public function index(request $request) 
    {
        $allUsers = $this->selectData();
        $filterArray = array();

            //Get column name corresponding with column index from request.

            //Check to see what column index from request have filter value
            //create an array that has following format: ['name' => <filter>, 'age => <filter>]
        for ( $i = 0; $i < count( $this->columnID ); $i++ ){

            if ( $request->columns[$i]['search']['value'] ){
                $columnArray = $request->columns[$i];
                $columnName = $this->columnID[$i];
                $searchContent = $columnArray['search']['value'];
                $filterArray[$columnName] = $searchContent;
            }
            else {
                continue;
            };
        };
            //filter database.
        if ( count($filterArray) > 0 ) {
            foreach ($filterArray as $key => $value) {
                $allUsers->where($key, 'like', "%" .$value. "%");
            };
        };


        //Retrieve sort values from datatables. Order array is: [ ['column' => <value>, 'dir' => <value> ] ]
        $orderRequest = $request->order[0];
        $sortColumn = $this->columnID[$orderRequest['column']];
        $sortMethod = $orderRequest['dir'];

        //Check if there is search content:
        if ( isset($request->search['value']) ) {
            $searchRequest = $request->search['value'];
            $userData = $allUsers->where(function($query) use ($searchRequest) {
                $query->orWhere('name', 'like', "%".$searchRequest."%")
                                           ->orWhere('email', 'like', "%".$searchRequest."%")
                                           ->orWhere('gender', 'like', "%".$searchRequest."%")
                                           ->orWhere('birthday', 'like', "%".$searchRequest."%")
                                           ->orWhere('location', 'like', "%".$searchRequest."%");

            })->orderBy($sortColumn, $sortMethod)->get();
        }
        else {
        //Get sorted data
        $userData = $allUsers->orderBy($sortColumn, $sortMethod)->get();
        };

        //Data sent from datatables
        $draw = $request->draw;
        $recordsTotal = count($userData);
        $recordsFiltered = count($userData);

        $startIndex = $request->start;
        $indexSum = $request->start + $request->length;
        $endIndex = $indexSum <= count($userData) ? $indexSum : count($userData);
        $data = [];
        for ($i = $startIndex; $i < $endIndex; $i++) {
            $index = $userData[$i];
            array_push($data, [
                $index['name'],
                $index['email'],
                $index['gender'],
                $index['birthday'],
                $index['location']
            ]);
        };
        return response([
            'draw'              => $draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsFiltered,
            'data'              => $data,
        ]);
    }

    public function show($id) {
        $user = $this->selectData($id);
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
        $selectedUser = $this->selectData($id);
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
