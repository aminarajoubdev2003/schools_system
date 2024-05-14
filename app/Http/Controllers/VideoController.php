<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\VideoResource;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $videos = Video::all();
            $videos = VideoResource::collection($videos);
            return $this->apiResponse($videos);
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
            'school_uuid' => 'required|string|exists:schools,uuid',
            'url' => 'required|string|unique:videos,url',
            'desc' => 'string|max:1000',
            ]);
            if($validate->fails()){
             return $this->requiredField($validate->errors()->first());    
            }
    
            try{
                $school_id = Shool::where('uuid',$request->input('school_uuid'))->value('id');
                $uuid= str::uuid();
                $video = Video::firstOrCreate(['uuid'=>$uuid,
                'school_id' => $school_id,
                'url' => $request->url,
                'desc' => $request->desc
                ]);
        
                $video = VideoResource::make($video); 
                return $this->apiResponse($video) ; 
            
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
            $video = Video::where("uuid",$uuid)->first();
            if(!$video)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $video = VideoResource::make($video);
            return $this->apiResponse($video);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            'school_uuid' => 'string|exists:schools,uuid',
            'url' => 'string|unique:videos,url',
            'desc' => 'string|max:1000',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $video = Video::where('uuid',$uuid)->first();
            
            if (!$video)
            { return $this->notFoundResponse($video);
             
            }
            $video = $video->update($request->all());
            return $this->apiResponse(["updateted successfully!"]);
               
          }catch (\Throwable $th) {
            
            return $this->apiResponse(null,false,$th->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $uuid)
    {
        $video = Video::where("uuid",$uuid)->first();
        if($video)
        {
            $video->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
