<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Add Rating of student
     */
    public function create(Request $request)
    {
        $rules = [
            'rating' => 'required|max:5'
        ];
        $response['success'] = false;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['data'] = $validator->messages();
            return response($response, 400);
        } else {
            Rating::create([
                'rating' => $request->rating,
                'user_id' => $request->user_id,
                'comment' => $request->comment,
                'created_by_id' => Auth::id()
            ]);
            $response['success'] = true;
            $response['message'] = 'Rating added successfully';
            return response($response, 200);
        }
    }
}
