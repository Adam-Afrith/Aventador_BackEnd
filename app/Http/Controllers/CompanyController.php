<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::all();
        if($company)
        {
            return response()->json([
                'status' => 200,
                'company' => $company,
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
        $company_name = Company::where('company_name', '=' ,$request->company_name)->exists();
        if($company_name)
        {
            return response()->json([
                'status' => 400,
                'message' => 'Company Name Already Exists',
            ]);
        }
        $company_add = Company::firstOrCreate($request->all());
        if($company_add)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Company Created Successfully!',
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
        $company = Company::where('id',$id)->get();
        if($company)
        {
            return response()->json([
                'status' => 200,
                'company' => $company,
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
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $company_name = Company::where('company_name', '=' ,$request->company_name)->exists();
        if($company_name)
        {
            return response()->json([
                'status' => 400,
                'message' => 'Company Name Already Exists',
            ]);
        }
        $company_update = Company::findOrFail($id)->update($request->all());
        if($company_update)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Company Updated Successfully!',
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
        $company_del = Company::destroy($id);
        if($company_del)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Company Deleted Successfully!',
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Cannot Delete this...',
            ]);
        }
    }

    public function getCompanyList()
    {
        return hii;
        $company_list = Company::get();
        $compList = [];
        foreach($company_list as $row){
            $compList[] = ["value" => $row['id'], "label" => $row['company_name']];
        }
        return response()->json([
            'companylist' => $compList,
        ]);
    }
}
