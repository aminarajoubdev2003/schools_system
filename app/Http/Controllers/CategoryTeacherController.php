<?php

namespace App\Http\Controllers;

use App\Models\Category_Teacher;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Teacher;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\CategoryTeacherResource;

class CategoryTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $categories_teachers = Category_Teacher::all();
            $categories_teachers = TeacherResource::collection($categories_teachers);
            return $this->apiResponse($categories_teachers);
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
            'category_uuid'=>'required|string|exists:categories,uuid',
            'teacher_uuid'=>'required|string|exists:teachers,uuid',
            ]);
    
            if($validate->fails()){
                return $this->requiredField($validate->errors()->first());    
            }
            try{
            $category_id = Category::where('uuid',$request->input('category_uuid'))->value('id');
            $category_school_id = Category::where('uuid',$request->input('category_uuid'))->value('school_id');
            $teacher_id = Teacher::where('uuid',$request->input('teacher_uuid'))->value('id');
            $teacher_school_id = Teacher::where('uuid',$request->input('teacher_uuid'))->value('school_id');
            if( $category_school_id==$teacher_school_id ){
            $uuid= str::uuid();
            $category_teacher = Category_Teacher::firstOrCreate(['uuid'=>$uuid,
            'category_id' => $category_id,
            'teacher_id' => $school_id,
            ]);
    
            $category_teacher = CategoryTeacherResource::make($category_teacher); 
            return $this->apiResponse($category_teacher) ;  
            }
        else{
            return $this->apiResponse(null,false,['this teacher not found in this school'],200);
        }
        
            } catch (\Throwable $th) {
              
                return $this->apiResponse(null,false,$th->getMessage(),500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show( $uuid )
    {
        try{
            $category_teacher = Category_Teacher::where("uuid",$uuid)->first();
            if(!$category_teacher)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $category_teacher = CategoryTeacherResource::make($category_teacher);
            return $this->apiResponse($category_teacher);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category_Teacher $category_Teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            'category_uuid'=>'string|exists:categories,uuid',
            'teacher_uuid'=>'string|exists:teachers,uuid',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $category_teacher = Category_Teacher::where('uuid',$uuid)->first();
            
            if (!$category_teacher)
            { return $this->notFoundResponse($category_teacher);
             
            }
            $category_school_id = Category::where('uuid',$request->input('category_uuid'))->value('school_id');
            $teacher_school_id = Teacher::where('uuid',$request->input('teacher_uuid'))->value('school_id');

            if( $category_school_id==$teacher_school_id ){
            $category_teacher = $category_teacher->update($request->all());
            return $this->apiResponse(["updateted successfully!"]);}
            else{
                return $this->apiResponse(null,false,['this teacher not found in this school'],200);
            }
               
          }catch (\Throwable $th) {
            
            return $this->apiResponse(null,false,$th->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $uuid )
    {
        $category_teacher = Category_Teacher::where("uuid",$uuid)->first();
        if($category_teacher)
        {
            $category_teacher->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
