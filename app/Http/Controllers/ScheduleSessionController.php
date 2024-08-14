<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleSessionRequest;
use App\Models\Availability;
use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduleSessionController extends Controller
{
    /**
     * Schedule session with specific user
     */
    public function create(ScheduleSessionRequest $request, $id)
    {
        $user = User::findOrFail($id);
        if (! $user->isUserAvailable($request->date)) {
            return response()->json(['success' => false, 'message' => 'User not available'], 400);
        }
        $request->start_time = $request->date . ' ' . date('H:i:s', strtotime($request->start_time));
        $request->end_time = $request->date . ' ' . date('H:i:s', strtotime($request->end_time));
        $scheduleSession = ScheduleSession::create([
            'user_id' => $id,
            'created_by_id' => Auth::id(),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_repeated' => $request->is_repeated,
        ]);
        return response()->json(['success' => true, 'session' => $scheduleSession], 200);
    }
}
