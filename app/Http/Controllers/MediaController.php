<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\Classrom;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\MediaResource;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $medias = Media::all();
            $medias = MediaResource::collection($medias);
            return $this->apiResponse($medias);
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
            'type'=>'required|string|in:sound,course',
            'title' => "string|required|min:3|max:30",
            'subject' => "string|required|min:3|max:20",
            'classrom_uuid' => 'required|string|exists:classroms,uuid',
            'url' => 'required|string|unique:medias,url',
            'desc' => 'string|max:1000',
            ]);
            if($validate->fails()){
             return $this->requiredField($validate->errors()->first());    
            }
    
            try{
                $classrom_id = Classrom::where('uuid',$request->input('classrom_uuid'))->value('id');
                $uuid= str::uuid();
                $classrom = Media::firstOrCreate(['uuid'=>$uuid,
                'type' => $request->type,
                'title' => $request->title,
                'subject' => $request->subject,
                'classrom_id' => $classrom_id,
                'url' => $request->url,
                'desc' => $request->desc
                ]);
        
                $media = MediaResource::make($media); 
                return $this->apiResponse($media) ; 
            
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
            $media = Media::where("uuid",$uuid)->first();
            if(!$media)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $media = MediaResource::make($media);
            return $this->apiResponse($media);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sound $sound)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid )
    {
        $validate= Validator::make($request->all(),[
            'type'=>'string|in:sound,course',
            'title' => "string|min:3|max:30",
            'subject' => "string|min:3|max:20",
            'classrom_uuid' => 'string|exists:classroms,uuid',
            'url' => 'string|unique:medias,url',
            'desc' => 'string|max:1000',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $media = Media::where('uuid',$uuid)->first();
            
            if (!$media)
            { return $this->notFoundResponse($media);
             
            }
            $media = $media->update($request->all());
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
        $media = Media::where("uuid",$uuid)->first();
        if($media)
        {
            $media->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
