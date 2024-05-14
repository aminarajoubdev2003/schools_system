<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Classrom;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\FileResource;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $files = File::all();
            $files = FileResource::collection($files);
            return $this->apiResponse($files);
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
            'subject' => 'string|required|min:3|max:20',
            'classrom_uuid' => 'required|string|exists:classroms,uuid',
            'type' => 'required|string|in:book,abrief,explain',
            'semester' => 'required|string|in:first,second',
            'path' => 'required|file|unique:files,path|mimes:pdf',
            ]);
    
            if($validate->fails()){
                return $this->requiredField($validate->errors()->first());    
            }
            try{
            $path = $this->uploadImagePublic2($request,'file','path');

            if($path)
            {
            $classrom_id = Classrom::where('uuid',$request->input('classrom_uuid'))->value('id');
            $uuid= str::uuid();
            $file = File::firstOrCreate(['uuid'=>$uuid,
            'subject' => $request->subject,
            'classrom_id' => $classrom_id,
            'type' => $request->type,
            'semester' => $request->semester,
            'path' => $request->path
            ]);
    
            $file = FileResource::make($file); 
            return $this->apiResponse($file) ; 
            } 
            else
            {
                return $this->apiResponse(null,false,['Failed to upload file'],500);
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
            $file = File::where("uuid",$uuid)->first();
            if(!$file)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $file = FileResource::make($file);
            return $this->apiResponse($file);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid )
    {
        $validate= Validator::make($request->all(),[
            'subject' => 'string|min:3|max:20',
            'classrom_uuid' => 'string|exists:classroms,uuid',
            'type' => 'string|in:book,abrief,explain',
            'semester' => 'string|in:first,second',
            'path_file' => 'file|unique:files,path|mimes:pdf',
        ]);
        
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try
        {
            $file = File::where("uuid",$uuid)->first();
            $file->update($request->all());
            if ($request->path_file)
            {
                $this->deleteFile($file->path);
                
            $path = $this->uploadImagePublic2($request,'file','path_file');
            $file->path= $path;
            $file->save();
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
        $file = File::where("uuid",$uuid)->first();
        if($file)
        {
            $file->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
