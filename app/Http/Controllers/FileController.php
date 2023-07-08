<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicle_list = DB::table('files as f')
                        ->leftJoin('bikes as b','b.id','f.vehicle_name')
                        ->select(
                            'f.id',
                            'b.bike_name', 
                        )->orderBy('f.id','asc')
                        ->get();

        if($vehicle_list){
            return response()->json([
                'status'=> 200,
                'vehicles'=>$vehicle_list
            ]);
        }
        
    }

    public function store(Request $request)
    {
        
        $vehicle_add = File::firstOrCreate($request->all());
        if($vehicle_add)
        {
            return response()->json([
                'status' => 200,
                'message' => 'vehicle Added Successfully!',
            ]);
        }
        else{
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are incorrect!',
            ]);
        }

    }

    public function fileupload(Request $request)
    {
        
        if($request ->hasFile('file')){
            $file = $request->file('file');
            $originalfileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $hasfileName=$file->hashName();

            $filenameSplited=explode(".",$hasfileName);
            $filename2 = 'calllog' . time() . '.' . $filenameSplited[1];
            
           
                       
            if($filenameSplited[1]!=$originalfileName)
            {
            $fileName=$filenameSplited[0]."".$originalfileName;
            }
            else{
                $fileName=$hasfileName;
            }
            //$file->storeAs('uploads/CallLogs/CallLogFiles/', $fileName, 'public');
            $destinationPath = 'uploads/Files/';
            $result = $file->move($destinationPath, $hasfileName);

            
                $get_id = File::orderBy('id', 'desc')->first('id');
           
             
                $FileSub = new FileSub;
                $FileSub->mainid = $get_id->id;
                // $FileSub->filename = $filename2;
                $FileSub->originalfilename = $originalfileName;
                $FileSub->file_type = $fileType;
                $FileSub->file_size = $fileSize;
                $FileSub->hasfilename = $hasfileName;
                $FileSub->save();

                
                return response()->json([
                    'status' => 200,
                    'message' => 'File Uploaded Succssfully!'
                ]);
            }
            else{
            return response()->json([
                'status' => 400,
                'message' => 'Provided Credentials are Incorrect!'
            ]);
            }
    }

    public function download($filename)
    {
        $doc = FileSub::find($filename);
        
        if($doc)
        {
            $filename = $doc['hasfilename'];
            $file = public_path('uploads/Files/'.$filename);
            return response()->download($file);
        }
    }
    

    public function show($id)
    {
        return "hii";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
    }
}
