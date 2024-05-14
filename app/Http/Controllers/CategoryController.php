<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Classrom;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $categories = Category::all();
            $categories = CategoryResource::collection($categories);
            return $this->apiResponse($categories);
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
            'school_uuid'=>'required|string|exists:schools,uuid',
            'classrom_uuid'=>'required|string|exists:classroms,uuid',
            'number'=>'required|integer|min:1|max:10',
            ]);
    
            if($validate->fails()){
                return $this->requiredField($validate->errors()->first());    
            }
            try{
            $school_id = Shool::where('uuid',$request->input('school_uuid'))->value('id');
            $classrom_id = Classrom::where('uuid',$request->input('classrom_uuid'))->value('id');
            $uuid= str::uuid();
            $category = Category::firstOrCreate(['uuid'=>$uuid,
            'school_id' => $school_id,
            'classrom_id' => $classrom_id,
            'number' => $request->number
            ]);
    
            $category = CategoryResource::make($category); 
            return $this->apiResponse($classrom) ;  
        
            } catch (\Throwable $th) {
              
                return $this->apiResponse(null,false,$th->getMessage(),500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show( $uuid)
    {
        try{
            $category = Category::where("uuid",$uuid)->first();
            if(!$category)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $category = CategoryResource::make($category);
            return $this->apiResponse($category);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            'school_uuid'=>'string|exists:schools,uuid',
            'classrom_uuid'=>'string|exists:classroms,uuid',
            'number'=>'integer|min:1|max:10',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $category = Category::where('uuid',$uuid)->first();
            
            if (!$category)
            { return $this->notFoundResponse($category);
             
            }
            $category = $category->update($request->all());
            return $this->apiResponse(["updateted successfully!"]);
               
          }catch (\Throwable $th) {
            
            return $this->apiResponse(null,false,$th->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $uuid )
    {
        $category = Category::where("uuid",$uuid)->first();
        if($category)
        {
            $category->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
