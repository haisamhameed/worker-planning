<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\WorkerResource;
use App\Http\Requests\WorkerStoreRequest;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workers = User::latest()->take(10)->get();
        return WorkerResource::collection($workers);
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
    public function store(WorkerStoreRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('123456')
        ]);
        return response()->json(['message'=>'Created successfully!'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return new WorkerResource(User::findOrFail($id));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'ERROR',
                'error' => 'Record not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkerStoreRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            return response()->json(['message'=>'Updated successfully!'],200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'ERROR',
                'error' => 'Invalid Worker ID'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $worker = User::find($id);    
        if($worker)
        {
            $worker->delete();
            return response()->json(['message'=>'Deleted successfully!'],200);
        }
        return response([
            'status' => 'ERROR',
            'error' => 'Invalid Worker ID'
        ], 404);
    }
}
