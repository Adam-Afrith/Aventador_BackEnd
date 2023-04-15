<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Bike;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $owner = DB::table('owners as o')
                ->join('companies as c','c.id','o.company_id')
                ->join('bikes as b','b.id','o.bike_id')
                ->select(
                    'o.id as oid','o.owner_name',
                    'c.id as cid','c.company_name',
                    'b.id as bid','b.bike_name',
                    'o.price','o.description',
                    )
                ->get();
        if($owner)
        {
            return response()->json([
                'status' => 200,
                'owner' => $owner,
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are incorrect!',
            ]);
        }
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
    public function store(Request $request)
    {
        $owner_name = Owner::where('owner_name', '=' ,$request->owner_name)->exists();
        if($owner_name)
        {
            return response()->json([
                'status' => 400,
                'message' => 'Owner Name Already Exists',
            ]);
        }
        $owner_add = Owner::firstOrCreate($request->all());
        if($owner_add)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Owner Created Successfully!',
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are incorrect!',
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $owner = DB::table('owners as o')
        ->join('companies as c','c.id','o.company_id')
        ->join('bikes as b','b.id','o.bike_id')
        ->select(
            'o.id as oid','o.owner_name',
            'c.id as cid','c.company_name',
            'b.id as bid','b.bike_name',
            'o.price','o.description',
            )
        ->where('o.id',$id)
        ->get();
if($owner)
{
    return response()->json([
        'status' => 200,
        'owner' => $owner,
    ]);
}
else{
    return response()->json([
        'status' => 400,
        'message' => 'Provided Credentials are incorrect!',
    ]);
}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Owner $owner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $owner_name = Owner::where('owner_name', '=' ,$request->owner_name)->exists();
        if($owner_name)
        {
            return response()->json([
                'status' => 400,
                'message' => 'Owner Name Already Exists',
            ]);
        }
        $owner_update = Owner::findOrFail($id)->update($request->all());
        if($owner_update)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Owner Updated Successfully!',
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Sorry,Failed to Update,Try again later!!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $owner_del = Owner::destroy($id);
        if($owner_del)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Owner Deleted Successfully!',
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Cannot Delete this...',
            ]);
        }
    }
}
