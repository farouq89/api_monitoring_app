<?php

namespace App\Models\Services;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\Uuid;
use App\Helpers\Logger;
use App\Helpers\Random;

class UserService
{
    /*
     * Getting filtered users
     */
    public function getFilteredElements($request)
    {

        //Query all users
        $query = User::query();
        $query->orderBy('created_at', 'DESC');
        //Filter users by by search keywords
        if ($request->search) {
            $query = $query->where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query;
        return $users;
    }

    /*
     * Add a new user
     */
   public function addUser($request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address_1 = $request->address_1;
        $user->address_2 = $request->address_2;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->state_id = $request->state;
        $user->country_id = $request->country;
        $user->password = Hash::make($request->password);
        $user->save();
    }

    /*
     * Update a new user
     */
   public function updateUser($request, $user, $password, $status_id)
    {
        $updateArray = [];
        $updateArray['first_name'] = $request->first_name;
        $updateArray['last_name'] = $request->last_name;
        $updateArray['email'] = $request->email;
        $updateArray['password'] = $password;
        $updateArray['status_id'] = $status_id;
        $updateArray['phone'] = $request->phone;
        $updateArray['address_1'] = $request->address_1;
        $updateArray['address_2'] = $request->address_2;
        $updateArray['city'] = $request->city;
        $updateArray['postal_code'] = $request->postal_code;
        $updateArray['state_id'] = $request->state;
        $updateArray['country_id'] = $request->country;   
        $user->update($updateArray);
    }
}