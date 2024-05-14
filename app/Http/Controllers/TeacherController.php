<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\TeacherResource;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $teachers = Teacher::all();
            $teachers = TeacherResource::collection($teachers);
            return $this->apiResponse($teachers);
            }
            catch (\Throwable $th) {
       
            return $this->apiResponse(null,false,$th->getMessage(),500);
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
        $validate= Validator::make($request->all(),[
            "name"=>"string|required|min:3|max:20",
            "start_year"=>'required|digits:4|integer|min:1900|max:2024',
            "image" =>"required|file|mimes:jpg,png,jpeg,jfif|max:2000",
            "certificates" => "array",
            "certificates.*" => "required|string",
            "salary" => 'required|integer|min:200000|max:1000000',
            'school_uuid'=>'required|string|exists:schools,uuid',
            ]);
            
    
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try{
                    
            $image= $this->uploadImagePublic2($request,'teacher','image');
            $school_id = School::where('uuid',$request->input('school_uuid'))->value('id');
                
            if($image)
            {

            $uuid = str::uuid();
            $teacher = Teacher::firstOrCreate([
            "uuid" => $uuid,
            "name" => $request->name,
            "start_year" => $request->start_year,
            "image" => $image,
            "certificates" => $request->certificates,
            "salary" => $request->salary,
            "school_id" => $school_id
            ]);

            $teacher = TeacherResource::make($teacher);
            return $this->apiResponse($teacher);
            }
            else
            {
                return $this->apiResponse(null,false,['Failed to upload image'],500);
            }
            }
        catch(\Throwable $th)
        {
            return $this->apiResponse(null , false , $th->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $uuid )
    {
        try{
            $teacher = School::where("uuid",$uuid)->first();
            if(!$teacher)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $teacher = TeacherResource::make($teacher);
            return $this->apiResponse($teacher);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            "name"=>"string|min:3|max:20",
            "start_year"=>'digits:4|integer|min:1900|max:2024',
            "image_teacher" =>"file|mimes:jpg,png,jpeg,jfif|max:2000",
            "certificates" => "array",
            "certificates.*" => "string",
            "salary" => 'integer|min:200000|max:1000000',
            'school_uuid'=>'string|exists:schools,uuid',
        ]);
        
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try
        {
            $teacher = Teacher::where("uuid",$uuid)->first();
            $student->update($request->all());
            if ($request->image_teacher)
            {
                $this->deleteFile($teacher->image);
                
            $image = $this->uploadImagePublic2($request,'teacher','image_teacher');
            $teacher->image = $image;
            $teacher->save();
            }
            return $this->apiResponse(['updateted successfully!']);

        }
        catch(\Throwable $th)
        {
            return $this->apiResponse(null,false,$th->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $uuid )
    {
        $teacher = Teacher::where("uuid",$uuid)->first();
        if($teacher)
        {
            $teacher->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
