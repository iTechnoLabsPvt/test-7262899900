<?php

namespace App\Http\Requests;

use App\Models\ScheduleSession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ScheduleSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ];
    }

    /**
     * Validates start time and end time
     *      Checks for end time should be greater than start time
     *      Checks for session will not overlaps
     *      Session should be maximum of 15 minutes
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $startTime = $this->input('start_time');
            $endTime = $this->input('end_time');
            $date = $this->input('date');
            $userId = $this->input('user_id');

            if (strtotime($startTime) >= strtotime($endTime)) {
                $validator->errors()->add('end_time', 'End time must be greater than start time.');
            }

            $startTime = $date . ' ' . date('H:i:s', strtotime($startTime));
            $endTime = $date . ' ' . date('H:i:s', strtotime($endTime));
            $sessions = ScheduleSession::where('user_id', $userId)
                ->whereDate('date', $date)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere('start_time', '>=', $startTime)
                        ->orWhere('end_time', '<=', $endTime);
                })
                ->exists();

            if ($sessions) {
                $validator->errors()->add('start_time', 'Session already scheduled');
            }

            $sessionExist = ScheduleSession::where('user_id', $userId)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereTime('start_time', '>=', $startTime)
                        ->orWhereTime('end_time', '<=', $endTime);
                })
                ->where('is_repeated', 1)
                ->exists();

            if ($sessionExist) {
                $validator->errors()->add('start_time', 'Session already scheduled');
            }

            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            if (abs($end->diffInMinutes($start)) > 15) {
                $validator->errors()->add('end_time', 'Session should be maximum of 15 minutes');
            }
        });
    }
}
