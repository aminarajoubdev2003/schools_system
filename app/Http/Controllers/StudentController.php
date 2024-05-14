<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transportation;
use App\Models\School;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $students = Student::all();
            $students = StudentResource::collection($students);
            return $this->apiResponse($students);
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
            "name" => "string|required|min:3|max:20",
            "father_name" => "string|required|min:3|max:20",
            "image" =>"required|file|mimes:jpg,png,jpeg,jfif|max:2000",
            "born"=>'required|digits:4|integer|min:1900|max:2024',
            'school_uuid' => 'required|string|exists:schools,uuid',
            'classrom_uuid' => 'required|string|exists:classroms,uuid',
            'transportation_uuid' => 'string|exists:transportations,uuid'
            ]);
            
    
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try{
                    
            $image= $this->uploadImagePublic2($request,'student','image');
                
            if($image)
            {
            
            $school_id = Shool::where('uuid',$request->input('school_uuid'))->value('id');
            $category_id = Category::where('uuid',$request->input('category_uuid'))->value('id');
            $uuid = str::uuid();
            $student = Student::firstOrCreate([
            "uuid" => $uuid,
            "name" => $request->name,
            "father_name" => $request->father_name,
            "image" => $image,
            "born" => $request->born,
            "school_id" => $school_id,
            "category_id" => $category_id,
            "number" => rand(0000,9999)
            ]);
            if($request->transportation_uuid){
                $transportation_id = Transportation::where('uuid',$request->input('transportation_uuid'))->value('id');
                $student = $student->update($request->all());
            }

            $student = StudentResource::make($student);
            return $this->apiResponse($student);
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
            $student = Student::where("uuid",$uuid)->first();
            if(!$student)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $student = StudentResource::make($student);
            return $this->apiResponse($student);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            "name" => "string|min:3|max:20",
            "father_name" => "string|min:3|max:20",
            "image_student" =>"file|mimes:jpg,png,jpeg,jfif|max:2000",
            "born"=>'digits:4|integer|min:1900|max:2024',
            'school_uuid' => 'string|exists:schools,uuid',
            'category_uuid' => 'string|exists:categories,uuid',
            'transportation_uuid' => 'string|exists:transportations,uuid'
        ]);
        
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try
        {
            $student = Student::where("uuid",$uuid)->first();
            $student->update($request->all());
            if ($request->image_student)
            {
                $this->deleteFile($student->image);
                
            $image = $this->uploadImagePublic2($request,'student','image_student');
            $student->image = $image;
            $student->save();
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
        $student = Student::where("uuid",$uuid)->first();
        if($student)
        {
            $student->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
