<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{   use GeneralTrait;
    public function register( Request $request) {
        try{
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users'],
            'password' => 'required|min:8',
        ],[
           
            'email.unique' => ' Email already exists',
        ]);
        
         
        if ($validatedData->fails()) {
            return $this->apiResponse( null, false, $validatedData->messages(), 401);
        }
     
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
       
        if ( $user ) {
        $tokenresult = $user->createToken('personal access token');
        $token = $tokenresult->plainTextToken;
        return $this->apiResponse2( $user, $token, true, null, 200);
        /*return response()->json([
            'message' => $this->apiResponse( $user, true, null, 200),
            'accessToken' =>$token,
        ]);*/
        
        }else{
            return $this->unAuthorizeResponse();
        }
    }catch(\Exception $ex) {
        return $this->apiResponse(null, false, $ex->getMessage(), 500);

    }
    }

    public function login( Request $request ) {
        try{
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users'],
            'password' => 'required|min:8',
        ],[
           
            'email.unique' => ' Email already exists',
        ]);
        $user = User::where('email', $request->email)->first();
        
        if( !Hash::check($request->password, $user->password)) {
            return $this->unAuthorizeResponse();
        }

        $tokenresult = $user->createToken('personal access token');
        $token = $tokenresult->plainTextToken;
        /*return response()->json([
            'message' => $this->apiResponse( $user, true, null, 200),
            'accessToken' =>$token,
        ]);*/
        return $this->apiResponse( $token, true, null, 200);
        //return $this->apiResponse2( $user, $token, true, null, 200);
    }catch(\Exception $ex) {
        return $this->apiResponse(null, false, $ex->getMessage(), 500);

    }

    
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
