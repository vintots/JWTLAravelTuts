<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use JWTAuth;
use JWTFactory;
use Auth;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => bcrypt($data['password']),
    //     ]);
    // }

        protected function create(array $data)
    {


        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        return $user->save();

    }


      public function registerUser(Request $request){
        // print_r($request->all());
        // dd();
        $validator = $this->validator($request->all());
        // echo $validator;
        if ($validator->fails()) {
            // return error messages from validation
            // dd("fail nga");
            return $this->response->errorUnauthorized($validator->messages());
        }

        csrf_field();
        $result = $this->create($request->all());
        if($result){
            return $this->response->created('',['response_msg' => 'successful']);
        }else{
            return $this->response->error('There is an error encountered.', 408);
        }

    }

     public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = Input::only('email', 'password');
        // $credentials = array(
        //     'email' => $request->login,
        //     'password' => $request->password);

        // die(print_r($credentials));

        try {
            // attempt to verify the credentials and create a token for the user
            // JWTAuth::setRequest($request)->parseToken()->authenticate();
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->error('invalid_credentials', 401);
            }


        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->response->errorInternal('could_not_create_token');
        }

        // all good so return the token and the user
        $response = array(
            'user' =>Auth::user(),
            'token' => $token);

        return $this->response->array($response);

    }

}
