<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataImportantController extends Controller
{
////////////////File Upload//////////////////////

   /****************UPDATE*********/
   public function update(Request $request, $id)
   {
       $user = Token::where('tokenid', $request->tokenid)->first(); 
   
       // Check if a new file has been uploaded
       if ($request->hasFile('file')) {
           $file = $request->file('file');
           
           // Move the new file to a new location
           $hasFileName = $file->hashName();
           $originalFileName = $file->getClientOriginalName();
           $fileType = $file->getClientOriginalExtension();
           $fileSize = $file->getSize();
           
             
   
           // Update the file information in the database
           $leaveRegistersFile = LeaveRegistersFile::where('mainid', $id)->first();
           $destinationPath = 'uploads/attendanceregisterfiles/'. $leaveRegistersFile->hasfilename;
           unlink($destinationPath);
           
           $leaveRegistersFile->hasfilename = $hasFileName;
           $leaveRegistersFile->filename = $originalFileName;
           $leaveRegistersFile->filetype = $fileType;
           $leaveRegistersFile->filesize = $fileSize;
           $leaveRegistersFile->save();
           $result = $file->move('uploads/attendanceregisterfiles/',   $leaveRegistersFile->hasfilename);
   
       //     $destinationPath = 'uploads/attendanceregisterfiles/'. $leaveRegistersFile->hasfilename;
       //     unlink($destinationPath);
       //     $result = $file->move('uploads/attendanceregisterfiles/', $hasfileName);
       }
       
       // Update the attendance register information in the database
       $attendanceUpdate = LeaveRegister::findOrFail($id)->update($request->all());
   
       if ($attendanceUpdate) {
           return response()->json([
               'status' => 200,
               'message' => "Updated Successfully!",
           ]);
       } else {
           return response()->json([
               'status' => 400,
               'message' => "Sorry, Failed to Update, Try again later"
           ]);
       }
   }
   /*********************************** */


//////////download file///////////////////
    public function download($filename)
    {
        $doc = File::find($filename);
        if($doc)
        {
            $filename = $doc['filename'];
            $file = public_path('uploads/files/'.$filename);
            return response()->download($file);
        }
    }   

/////////////////Attendance Filter///////////////////   
public function getEmployeeLeaveListTest(Request $request)
{
    
    $userID = $request->user_id;
    $roleID = $request->role_id;
    $month = $request->from_date;
    $curr_month = Carbon::now()->month;

    $user_list = [];
    $users = User::where('userType','!=',1)->get();
    foreach($users as $row)
    {
        $user_list[] =  ['id'=>$row->id,'name'=>$row->name]; 
    }

    $leave = DB::table('leave_registers as lr')
            ->join('users as u','u.id','lr.user_id')
            ->join('roles as r','r.id','u.userType')
            ->join('attendance_types as at','at.id','lr.attendance_type_id')
            ->select(
                'lr.user_id',
                'lr.attendance_type_id',
                'at.attendanceType',
                'lr.from_date',
                'lr.to_date',
                'lr.start_time',
                'lr.reason',
                'r.id as role_ID',
            );
               
            if($userID)
            {
                $leave->where('lr.user_id',$userID);
            }
            if($roleID)
            {
                $leave->where('r.id',$roleID);
            }
            if($month)
            {
                $leave->whereMonth('lr.from_date','=',$month);
            } else {
                $leave->whereMonth('lr.from_date','=',$curr_month);
            }
                $leave = $leave->get();
                

    $result = [];
    foreach ($leave as $row) {
        $user_id = $row->user_id;
        $result[$user_id][] = [
            'user_id' => $user_id,
            'attendance_type_id' => $row->attendance_type_id,
            'attendanceType' =>$row->attendanceType,
            'from_date' => $row->from_date,
            'to_date' => $row->to_date,
            'start_time' => $row->start_time,
            'reason' => $row->reason,
            'role' => $row->role_ID,
        ];
    }
            $holidaylist = []; 
            $holiday = Holiday::orderBy('created_at', 'ASC')
                    ->whereMonth('date',$curr_month)
                    ->get();
    
            foreach($holiday as $row)
            {
                $holidaylist[] = ['id'=>$row->id,'date'=>$row->date,'occasion'=>$row->occasion,'remarks'=>$row->remarks]; 
            }

    if ($holiday)
    return response()->json([
        'status' => 200,
        'userlist' => $user_list,
        'result' => $result,
        'holiday' => $holidaylist,
    ]);
    else {
        return response()->json([
            'status' => 404,
            'message' => 'The provided credentials are incorrect.'
        ]);
    }
}
///////////////////////Migrations//////////////////////////////////////////////////
public function up()
    {
        Schema::create('call_log_creations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("customer_id")->unsigned();
            $table->foreign ('customer_id')->references('id')->on('customer_creation_profiles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->date('call_date')->format('Y/m/d h:i:s A');
            $table->integer("call_type_id");
            $table->foreign('call_type_id')->references('id')->on('call_types_mst')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->integer("bizz_forecast_id");
            $table->foreign('bizz_forecast_id')->references('id')->on('business_forecasts')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->integer("bizz_forecast_status_id");
            $table->foreign('bizz_forecast_status_id')->references('id')->on('business_forecast_statuses')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger("executive_id")->unsigned(); //staff id
            $table->foreign ('executive_id')->references('id')->on('users')->restrictOnDelete()->onUpdate("NO ACTION");

            $table->integer("procurement_type_id");
            $table->foreign('procurement_type_id')->references('id')->on('call_procurement_types')->restrictOnDelete()->onUpdate("NO ACTION");

            $table->enum('action', ['next_followup', 'close'])->default('next_followup');
            $table->date('next_followup_date')->format('Y/m/d')->nullable();
            $table->date('close_date')->format('Y/m/d')->nullable();
            $table->integer('close_status_id')->nullable();
            $table->foreign('close_status_id')->references('id')->on('call_close_statuses')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->text("additional_info")->nullable();
            $table->text("remarks")->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            //have to create sub table for file maintainenece multiple file uploads for every call log entry
        });
    }
//////////////////Count/////////////////
 ///////////////////////GET CALL LIST///////////////////
 public function getTodayCalls()
 {
     $today = Carbon::today();
     $today_calls = CallLog::where('call_date', '=' ,$today)->count();
     return  response()->json([
         'todaycalls' =>  $today_calls,
     ]);
 }

 public function getPendingCalls()
 {
     $pending_calls = CallLog::where('action', '=' ,'next_followup')->count();
     return  response()->json([
         'pendingcalls' =>  $pending_calls,
     ]);
 }

 public function getClosedCalls()
 {
     $closed_calls = CallLog::where('action', '=' ,'close')->count();
     return  response()->json([
         'closedcalls' =>  $closed_calls,
     ]);
 }


}
