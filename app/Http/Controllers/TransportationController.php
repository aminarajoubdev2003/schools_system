<?php

namespace App\Http\Controllers;

use App\Models\Transportation;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\TransportationResource;

class TransportationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $transportations = Transportation::all();
            $transportations = TransportationResource::collection($transportations);
            return $this->apiResponse($transportations);
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
            "number_bus" => "required|digits:4|integer|min:1900|max:2024",
            'school_uuid' => 'required|string|exists:schools,uuid',
            "street_name" => "string|required|min:3|max:20",
            'departure_hour' => 'required|time',
            ]);
            if($validate->fails()){
             return $this->requiredField($validate->errors()->first());    
            }
    
            try{
                $school_id = Shool::where('uuid',$request->input('school_uuid'))->value('id');
                $uuid= str::uuid();
                $transportation = Transportation::firstOrCreate(['uuid'=>$uuid,
                'number_bus' => $request->number_bus,
                'school_id' => $school_id,
                'street_name' => $request->street_name,
                'departure_hour' => $request->departure_hour
                ]);
        
                $transportation = TransportationResource::make($transportation); 
                return $this->apiResponse($transportation) ; 
            
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
            $transportation = Transportation::where("uuid",$uuid)->first();
            if(!$transportation)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $transportation = TransportationResource::make($transportation);
            return $this->apiResponse($transportation);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transportation $transportation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            "number_bus" => "digits:4|integer|min:1900|max:2024",
            'school_uuid' => 'string|exists:schools,uuid',
            "street_name" => "string|min:3|max:20",
            'departure_hour' => 'time',
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
            try{
            $transportation = Transportation::where('uuid',$uuid)->first();
            
            if (!$transportation)
            { return $this->notFoundResponse($transportation);
             
            }
            $transportation = $transportation->update($request->all());
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
        $transportation = Transportation::where("uuid",$uuid)->first();
        if($transportation)
        {
            $transportation->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
