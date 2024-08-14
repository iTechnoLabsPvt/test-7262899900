<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create Student
     */
    public function create(Request $request)
    {
        $rules = [
            'first_name' => 'required|max:64',
            'middle_name' => 'required|max:64',
            'last_name' => 'required|max:64',
            'date_of_birth' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required']
        ];
        $response = ['success' => false];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['data'] = $validator->messages();
            return response($response, 400);
        } else {
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->addAvailability();
            $response['success'] = true;
            $response['id'] = $user->id;
            $response['message'] = 'User Created';
        }
        return response($response, 200);
    }

    /**
     * Returns the list of users
     */
    public function list()
    {
        try {
            $users = User::all();
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $rating = $user->getAvgRating();
                    $user->rating = $rating ?? 0; // Default to 0 if null
                }

                $response['message'] = 'List retrived successfully';
                $response['data'] = $users;
                $response['success'] = true;
            } else {
                $response['message'] = 'List retrived successfully';
                $response['data'] = [];
                $response['success'] = true;
            }
            return response($response, 200);
        } catch (\Exception $th) {
            $response['message'] = 'Internal Server Error';
            $response['success'] = false;
            return response($response, 500);
        }
    }

    /**
     * Change availability of specific record
     */
    public function setAvailability(Request $request)
    {
        $availabilities = Availability::where('user_id', $request->user_id)->whereNotIn('day_id', $request->day_id)->get();
        foreach ($availabilities as $availability) {
            $availability->delete();
        }
        $response['message'] = 'Success';
        $response['success'] = true;
        return response($response, 200);
    }

    /**
     * Returns the detailed json of specific user
     */
    public function detail($id)
    {
        $user = User::with('availabilty')->where('id', $id)->first();
        $user->rating = $user->getAvgRating();
        $data['success'] = true;
        $data['data'] = $user;
        return $data;
    }
}
