<?php

namespace App\Http\Controllers;

use App\Models\Classrom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\ClassromResource;

class ClassromController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $classroms = Classrom::all();
            $classroms = ClassromResource::collection($classroms);
            return $this->apiResponse($classroms);
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
            'stage'=>'required|string|in:secondary,highschool',
            'level'=>'required|string|in:5,6,7,8,9,10,11,12',
            'branch'=>'required|string|in:scientific,literal',
            ]);
    
            if($validate->fails()){
                return $this->requiredField($validate->errors()->first());    
            }
            try{

            $uuid= str::uuid();
            $classrom = Classrom::firstOrCreate(['uuid'=>$uuid,
            'stage' => $request->stage,
            'level' => $request->level,
            'branch' => $request->branch
            ]);
    
            $classrom = ClassromResource::make($classrom); 
            return $this->apiResponse($classrom) ;  
        
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
            $classrom = Classrom::where("uuid",$uuid)->first();
            if(!$classrom)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $classrom = ClassromResource::make($classrom);
            return $this->apiResponse($classrom);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classrom $classrom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classrom $classrom)
    {
        $validate= Validator::make($request->all(),[
            'stage'=>'string|in:secondary,highschool',
            'level'=>'string|in:5,6,7,8,9,10,11,12',
            'branch'=>'string|in:scientific,literal',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $classrom = Classrom::where('uuid',$uuid)->first();
            
            if (!$classrom)
            { return $this->notFoundResponse($classrom);
             
            }
            $classrom = $classrom->update($request->all());
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
        $classrom = Classrom::where("uuid",$uuid)->first();
        if($classrom)
        {
            $classrom->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
