<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\Classrom;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\ApplicationResource;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $applications = Application::all();
            $applications = ApplicationResource::collection($applications);
            return $this->apiResponse($applications);
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
            'subject' => "string|required|min:3|max:20",
            'classrom_uuid' => 'required|string|exists:classroms,uuid',
            'url' => 'required|string|unique:applications,url',
            'desc' => 'string|max:1000',
            ]);
            if($validate->fails()){
             return $this->requiredField($validate->errors()->first());    
            }
    
            try{
                $classrom_id = Classrom::where('uuid',$request->input('classrom_uuid'))->value('id');
                $uuid= str::uuid();
                $application = Application::firstOrCreate(['uuid'=>$uuid,
                'subject' => $request->subject,
                'classrom_id' => $classrom_id,
                'url' => $request->url,
                'desc' => $request->desc
                ]);
        
                $application = ApplicationResource::make($application); 
                return $this->apiResponse($application) ; 
            
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
            $application = Application::where("uuid",$uuid)->first();
            if(!$application)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $application = ApplicationResource::make($application);
            return $this->apiResponse($application);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid )
    {
        $validate= Validator::make($request->all(),[
            'subject' => "string|min:3|max:20",
            'classrom_uuid' => 'string|exists:classroms,uuid',
            'url' => 'string|unique:applications,url',
            'desc' => 'string|max:1000',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $application = Application::where('uuid',$uuid)->first();
            
            if (!$application)
            { 
                return $this->notFoundResponse($application);
            }
            $application = $application->update($request->all());
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
        $application = Application::where("uuid",$uuid)->first();
        if($application)
        {
            $application->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
