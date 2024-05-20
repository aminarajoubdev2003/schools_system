<?php

namespace App\Http\Controllers;

use App\Models\Boss;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\BossResource;

class BossController extends Controller
{
    use GeneralTrait,FileUploader;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $bosses = Boss::all();
            $bosses = BossResource::collection($bosses);
            return $this->apiResponse($bosses);
            }
            catch (\Throwable $th) {
       
            return $this->apiResponse(null,false,$th->getMessage(),201);
            }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate= Validator::make($request->all(),[
            "name"=>"string|required|min:3|max:20",
            "start_year"=>'required|digits:4|integer|min:1900|max:2024',
            "image" =>"required|file|mimes:jpg,png,jpeg,jfif|max:2000",
            ]);
            
    
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try{
                    
            $image= $this->uploadImagePublic2($request,'boss','image');
                
            if($image)
            {

            $uuid= str::uuid();
            $boss= Boss::firstOrCreate([
            "uuid"=>$uuid,
            "name"=>$request->name,
            "start_year"=>$request->start_year,
            "image"=>$image,
            ]);

            $boss= BossResource::make($boss);
            return $this->apiResponse($boss);
            }
            else
            {
                return $this->apiResponse(null,false,['Failed to upload image'],201);
            }
            }
        catch(\Throwable $th)
        {
            return $this->apiResponse(null , false , $th->getMessage(),201);
        }
            
    }

    /**
     * Display the specified resource.
     */
    public function show( $uuid )
    {
        try{
            $boss= Boss::where("uuid",$uuid)->first();
            if(!$boss)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $boss= BossResource::make($boss);
            return $this->apiResponse($boss);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bosses $bosses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            "name"=>"string|min:3|max:20",
            "start_year"=>'digits:4|integer|min:1900|max:',
            "image_boss" =>"file|mimes:jpg,png,jpeg,jfif",
        ]);
        
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try
        {
            $boss= Boss::where("uuid",$uuid)->first();
            $boss->update($request->all());
            if ($request->image_boss)
            {
                $this->deleteFile($boss->image);
                
            $image= $this->uploadImagePublic2($request,'boss','image_boss');
            $boss->image= $image;
            $boss->save();
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
        $boss= Boss::where("uuid",$uuid)->first();
        if($boss)
        {
            $boss->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
