<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\FileUploader;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $tags = Tag::all();
            $tags = TagResource::collection($tags);
            return $this->apiResponse($tags);
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
            'subject' => 'string|required|min:3|max:20',
            'student_uuid' => 'required|string|exists:students,uuid',
            'semester' => 'required|string|in:first,second',
            'type' => 'required|string|in:studying,exam',
            'value' => 'integer|required|min:200|max:600',
            'oral' => 'integer|min:|max:'
            ]);
            if($validate->fails()){
             return $this->requiredField($validate->errors()->first());    
            }
    
            try{
                $student_id = Student::where('uuid',$request->input('student_uuid'))->value('id');
                $uuid= str::uuid();
                $tag = Tag::firstOrCreate(['uuid'=>$uuid,
                'subject' => $request->subject,
                'student_id' => $student_id,
                'semester' => $request->semester,
                'type' => $request->type,
                'value' => $request->value,
                'oral' => $request->oral,
                'total' => $request->value+$request->oral
                ]);
        
                $tag = TagResource::make($tag); 
                return $this->apiResponse($tag) ; 
            
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
            $tag = Tag::where("uuid",$uuid)->first();
            if(!$tag)
            {
                return $this->apiResponse(null , true, ['notfound'], 200);
            }
            else
            {
            $tag = TagResource::make($tag);
            return $this->apiResponse($tag);
            } 
             }catch (\Throwable $th) {
       
                return $this->apiResponse(null,false,$th->getMessage(),500);
             
            }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $validate= Validator::make($request->all(),[
            'subject' => 'string|min:3|max:20',
            'student_uuid' => 'required|string|exists:students,uuid',
            'semester' => 'string|in:first,second',
            'type' => 'string|in:studying,exam',
            'value' => 'integer|min:200|max:600',
            'oral' => 'integer|min:|max:'
        ]);
        
        if($validate->fails())
        {
            return $this->requiredField($validate->errors()->first());
        }
        try{
            $tag = Tag::where('uuid',$uuid)->first();
            
            if (!$tag)
            { return $this->notFoundResponse($tag);
             
            }
            $tag = $tag->update($request->all());
            return $this->apiResponse(["updateted successfully!"]);
               
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
        $tag = Tag::where("uuid",$uuid)->first();
        if($tag)
        {
            $tag->delete();
            return $this->apiResponse(["sucessfull delete"],true,null,201);
        }
             
        else {
            return $this->apiResponse(null , true, ['notfound'], 200);
        }
    }
}
