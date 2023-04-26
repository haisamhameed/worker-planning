<?php

namespace App\Http\Requests;

use App\Models\Shift;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ShiftStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $request = request();
        return [
            'worker_id' => 'required|exists:users,id',
            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after_or_equal:now',
                'before_or_equal:' . date('Y-m-d 23:59:59', strtotime('+1 month')),
                function ($attribute, $value, $fail) use ($request) {
                    $workerId = $request->input('worker_id');
                    $startTime = $value;
    
                    $existingShift = Shift::where('worker_id', $workerId)
                        ->whereDate('start_time', date('Y-m-d', strtotime($startTime)))
                        ->first();
    
                    if ($existingShift) {
                        $fail('The worker already has a shift on this day.');
                    }
                }
            ]
        ];
    }
}
