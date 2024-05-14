<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\OpinionResource;

class OpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $opinions = Opinion::all();
            $opinions = OpinionResource::collection($opinions);
            return $this->apiResponse($opinions);
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
        $validate = Validator::make($request->all(),[
            "name" => "string|required|min:3|max:20|exists:students,father_name",
            'school_uuid' => 'required|string|exists:schools,uuid',
            "comment" => "string|required|min:3|max:200",
            'rate' => 'required|integer|min:0|max:100',
            ]);
            if($validate->fails()){
             return $this->requiredField($validate->errors()->first());    
            }
    
            try{
                $school_id = Shool::where('uuid',$request->input('school_uuid'))->value('id');
                $uuid= str::uuid();
                $opinion = Opinion::firstOrCreate(['uuid'=>$uuid,
                'name' => $request->name,
                'school_id' => $school_id,
                'comment' => $request->comment,
                'rate' => $request->rate
                ]);
        
                $opinion = OpinionResource::make($opinion); 
                return $this->apiResponse($opinion) ; 
            
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
            $opinion = Opinion::where("uuid",$uuid)->first();
            if(!$opinion)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $opinion = OpinionResource::make($opinion);
            return $this->apiResponse($opinion);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Opinion $opinion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            "name" => "string|min:3|max:20|exists:students,father_name",
            'school_uuid' => 'string|exists:schools,uuid',
            "comment" => "string|min:3|max:200",
            'rate' => 'integer|min:0|max:100',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $opinion = Opinion::where('uuid',$uuid)->first();
            
            if (!$opinion)
            { return $this->notFoundResponse($opinion);
             
            }
            $opinion = $opinion->update($request->all());
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
        $opinion = Opinion::where("uuid",$uuid)->first();
        if($opinion)
        {
            $opinion->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
