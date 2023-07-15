<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bike = DB::table('bikes as b')
                ->join('companies as c','c.id','b.company_id')
                ->select(
                    'c.id as cid','c.company_name',
                    'b.id as bid','b.bike_name',
                    )
                    ->orderBy('b.id','ASC')
                ->get();
        if($bike)
        {
            return response()->json([
                'status' => 200,
                'bike' => $bike,
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
        $bike_name = Bike::where('bike_name', '=' ,$request->bike_name)->exists();
        if($bike_name)
        {
            return response()->json([
                'status' => 400,
                'message' => 'Bike Name Already Exists',
            ]);
        }
        $bike_add = Bike::firstOrCreate($request->all());
        if($bike_add)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Bike Created Successfully!',
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
        $bike = DB::table('bikes as b')
        ->join('companies as c','c.id','b.company_id')
        ->select(
            'c.id as cid','c.company_name',
            'b.id as bid','b.bike_name',
            )
        ->where('b.id',$id)    
        ->get();
       
if($bike)
{
    return response()->json([
        'status' => 200,
        'bike' => $bike,
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
    public function edit(Bike $bike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bike_name = Bike::where('bike_name', '=' ,$request->bike_name)
                    ->where('id','!=',$id)
                    ->exists();

        if($bike_name)
        {
            return response()->json([
                'status' => 400,
                'errors' => 'Bike Name Already Exists',
            ]);
        }
        $bike_update = Bike::findOrFail($id)->update($request->all());
        if($bike_update)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Bike Updated Successfully!',
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
        try{
        $bike_del = Bike::destroy($id);
        if($bike_del)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Bike Deleted Successfully!',
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'errors' => 'Cannot Delete this...',
            ]);
        }
    }catch(\Illuminate\Database\QueryException $ex){
        $error = $ex->getMessage();

        return response()->json([
            'status' => 400,
            'errors' => 'Unable to delete! This data is used in another file/form/table.',
            "errormessage" => $error,
        ]);
    }

    }

    public function getBikeList($companyId)
    {
        $bikeList = [];
        $bike_list = Bike::where("company_id",$companyId)->get(); 
        foreach($bike_list as $row){
            $bikeList[] = ["value" => $row['id'], "label" => $row['bike_name']];
        }
        return response()->json([
            'bikelist' => $bikeList,
        ]);
    }

    public function getAllBikeList()
    {
        $bikeList = [];
        $bike_list = Bike::get(); 
        foreach($bike_list as $row){
            $bikeList[] = ["value" => $row['id'], "label" => $row['bike_name']];
        }
        return response()->json([
            'allbikelist' => $bikeList,
        ]);
    }
    

    public function getBikeModel()
    {
        $bikeModelList = [];
        $bike_model_list = Bike::where('company_id','=',1)->get(); 
        foreach($bike_model_list as $row){
            $bikeModelList[] = ["value" => $row['id'], "label" => $row['bike_name']];
        }
        return response()->json([
            'bikemodellist' => $bikeModelList,
        ]);
    }


    
}
