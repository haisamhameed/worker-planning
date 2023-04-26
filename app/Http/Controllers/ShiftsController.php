<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftEditRequest;
use App\Http\Requests\ShiftStoreRequest;

class ShiftsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::with('worker')->get();
        return response()->json(['data' => $shifts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftStoreRequest $request)
    {
        $endTime = date('Y-m-d H:i:s', strtotime($request->input('start_time') . ' +8 hours'));

        $shift = Shift::create([
            'worker_id' => $request->input('worker_id'),
            'start_time' => $request->input('start_time'),
            'end_time' => $endTime
        ]);

        return response()->json(['message'=>'Created successfully!'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shift = Shift::with('worker')->find($id);

        if (!$shift) {
            return response()->json(['error' => 'Shift not found'], 404);
        }

        return response()->json(['data' => $shift]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return response()->json(['error' => 'Shift not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftStoreRequest $request, string $id)
    {
        $endTime = date('Y-m-d H:i:s', strtotime($request->input('start_time') . ' +8 hours'));

        $shift->worker_id = $request->input('worker_id');
        $shift->start_time = $request->input('start_time');
        $shift->end_time = $endTime;
        $shift->save();
        return response()->json(['message'=>'Updated successfully!'],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shift = Shift::find($id);    
        if($shift)
        {
            $shift->delete();
            return response()->json(['message'=>'Deleted successfully!'],200);
        }
        return response([
            'status' => 'ERROR',
            'error' => 'Invalid Shift ID'
        ], 404);
    }
}
