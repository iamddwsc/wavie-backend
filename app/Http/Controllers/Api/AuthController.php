<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class AuthController extends Controller
{
    //
    protected function signin(Request $request)
    {
        $creds = $request->only(['email', 'password']);

        if (!$token = auth()->setTTL(30)->attempt($creds)) { //error but still working 
            return response()->json([
                'success' => false,
                'message' => 'invalid credentials'
            ], 401);
        }
        $data = JWTAuth::decode(new Token($token))->toArray();
        $currentTime = new DateTime();
        $currentTime = DateTime::createFromFormat( 'U', $data['exp'] );
        $formattedString = $currentTime->format( 'c' );

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => auth()->user(),
            'expires_at' => gmdate($data['exp']), 
            //'refresh' => auth()->refresh()
            //'time' => $data,
            // 'time' => DateTime::createFromFormat('U', $data['exp'])->format('Y-m-d H:i:s'),
            // 'now' =>  $formattedString
        ]);
    }

    protected function signup(Request $request)
    {
        $encryptedPass = Hash::make($request->password);

        $user = new User;

        try {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = $encryptedPass;
            $user->saveOrFail();
            return $this->login($request);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th
            ]);
        }
    }

    protected function signout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th
            ]);
        }
    }

    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getCode());
        } catch (TokenExpiredException $e) {

            return response()->json(['token_invalid'], $e->getCode());
        } catch (TokenExpiredException $e) {

            return response()->json(['token_absent'], $e->getCode());
        }

        return response()->json(compact('user'));
    }

    public function saveUserData(Request $request){
        $user = User::find(auth()->user()->userId);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        // $photo ='';
        // if($request->photo!=''){
        //     $photo = time().'.jpg';
        //     file_put_contents('storage/profiles/'.$photo,base64_decode($request->photo));
        //     $user->photo = $photo;
        // }
        

        if ($request->hasFile('image') && $request->image->isValid()) {
            $path = $request->file('image')->store('public/users');
            $user->photo = str_replace('public/users/', '', $path);
        }

        $user->update();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    // public function addimage(Request $request)
    // {
    //     $image = new Image;
    //     $image->title = $request->title;
        
    //         if ($request->hasFile('image')) {
            
    //         $path = $request->file('image')->store('images');
    //         $image->url = $path;
    //        }
    //     $image->save();
    //     return new ImageResource($image);
    // }
}
