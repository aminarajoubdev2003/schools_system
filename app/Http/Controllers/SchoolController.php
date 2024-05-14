<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Boss;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\SchoolResource;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $schools = School::all();
            $schools = SchoolResource::collection($schools);
            return $this->apiResponse($schools);
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
        "title"=>"string|required|min:3|max:20",
        "address"=>"string|required|min:3|max:20",
        'boss_uuid'=>'required|string|exists:bosses,uuid',
        "logo" =>"required|file|mimes:jpg,png,jpeg,jfif|max:2000",
        "phone" => "required|regex:/(21)[0-9]/|not_regex:/[a-z]/min:5",
        "mobile" => "required|regex:/(0)[0-9]/|not_regex:/[a-z]/min:9",
        "installment" => "required|integer",
        "transportation_cost" => "required|integer"
        ]);

        if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
        }
        try{
        $logo=$this->uploadImagePublic2($request,'school','logo');
        if(!$logo)
        {
        return  $this->apiResponse(null, false,['Failed to upload image'],500); 
        }
    
        else{
            
        $boss_id = Shool::where('uuid',$request->input('boss_uuid'))->value('id');
        $uuid=Str::uuid();

        $school = Boss::firstOrCreate(['uuid'=>$uuid,
        'title' => $request->title,
        'address' => $request->address,
        'boss_id' => $boss_id,
        'logo' => $logo,
        'phone' => $request->phone,
        'mobile' => $request->mobile,
        'installment' => $request->installment,
        'transportation_cost' => $request->transportation_cost,
        ]);

        $school = SchoolResource::make($school); 
        return $this->apiResponse($school) ;  
    
        }  } catch (\Throwable $th) {
          
            return $this->apiResponse(null,false,$th->getMessage(),500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        try{
            $school = School::where("uuid",$uuid)->first();
            if(!$school)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $school = SchoolResource::make($school);
            return $this->apiResponse($school);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate = Validator::make($request->all(),[
            "title"=>"string|min:3|max:20",
            "address"=>"string|min:3|max:20",
            'boss_uuid'=>'string|exists:bosses,uuid',
            "school_logo" =>"file|mimes:jpg,png,jpeg,jfif|max:2000",
            "phone" => "regex:/(21)[0-9]/|not_regex:/[a-z]/min:5",
            "mobile" => "regex:/(0)[0-9]/|not_regex:/[a-z]/min:9",
            "installment" => "integer",
            "transportation_cost" => "integer"   
            ]);
        if($validate->fails()){
        return $this->requiredField($validate->errors()->first());     }
        try{
        $image='';
        $school = School::where('uuid',$uuid)->first();
        $school->update($request->all());

        if($request->school_logo)
        { 
        $this->deleteFile($school->logo);
        $image = $this->uploadImagePublic2($request,'school','school_logo');
        if(!$image)
        { return $this->apiResponse(null, false,['Failed to upload image'],500);}

        $school->school_logo = $image;
        $school->save();
        }
        return $this->apiResponse(['updateted successfully!']);
        } catch (\Throwable $th) {
          
        return $this->apiResponse(null,false,$th->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $school = School::where("uuid",$uuid)->first();
        if($school)
        {
            $school->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
