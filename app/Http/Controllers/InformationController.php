<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\InformationResource;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $informations = Information::all();
            $informations = InformationResource::collection($informations);
            return $this->apiResponse($informations);
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
            'school_uuid' => 'required|string|exists:schools,uuid',
            'title' => 'required|string|min:10|max:100',
            'content' => 'required|string|min:10|max:1000',
            'image' => 'required|file|mimes:jpg,png,jpeg,jfif|max:2000'
            ]);
            
    
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try{
                    
            $image= $this->uploadImagePublic2($request,'information','image');
                
            if($image)
            {
            $school_id = Shool::where('uuid',$request->input('school_uuid'))->value('id');
            $uuid= str::uuid();
            $information = Information::firstOrCreate([
            "uuid" => $uuid,
            "school_id" => $school_id,
            "title" => $request->title,
            "content" => $request->content,
            "image" => $image,
            ]);

            $information = InformationResource::make($information);
            return $this->apiResponse($information);
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
            $information = Information::where("uuid",$uuid)->first();
            if(!$information)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $information = InformationResource::make($information);
            return $this->apiResponse($information);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Information $information)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $uuid)
    {
        $validate= Validator::make($request->all(),[
            'school_uuid' => 'string|exists:schools,uuid',
            'title' => 'string|min:10|max:100',
            'content' => 'string|min:10|max:1000',
            'image_information' => 'file|mimes:jpg,png,jpeg,jfif|max:2000'
        ]);
        
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try
        {
            $information = Information::where("uuid",$uuid)->first();
            $information->update($request->all());
            if ($request->image_information)
            {
                $this->deleteFile($information->image);
                
            $image = $this->uploadImagePublic2($request,'boss','image_information');
            $information->image= $image;
            $information->save();
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
        $information = Information::where("uuid",$uuid)->first();
        if($information)
        {
            $information->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
