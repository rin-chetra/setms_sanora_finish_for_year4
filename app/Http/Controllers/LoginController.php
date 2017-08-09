<?php namespace App\Http\Controllers;

use DB;
use Input;
use Cookie;
use Response;
use Validator;
use App\Http\Models\User;
use App\Http\Utils\Util;
use Illuminate\Http\Request;

class LoginController extends Controller {

	public function index(){
		
		$isHas = Util::checkLogin();
		if($isHas === TRUE) {
			return redirect()->route('dashboard');	
		}else{

			return view('logins.index');
		}
	}
	
	public function login(){

		$this->validation();


		$email = Input::get('email');
		$password = Input::get('password');

		$users = User::where('isDelete','=', 0)
					->where('email','=', $email)
					->where('password','=', $password)
					->first();
		if($users){
			
			$cookie = Util::createCookieAccount($users->id);
			return redirect()->route('dashboard')->withCookie($cookie);

		}else{
			return redirect()->route('login');
		}
		
	    
	}


	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/

	public function validation(){
		$rules =  [
		            'email' => 'required|email',
			        'password' => 'required|min:3',
		          ];
		$validator = Validator::make(Input::all(), $rules);		          

        if ($validator->fails()) {
            return redirect('login')
                        ->withErrors($validator)
                        ->withInput();        	
        }
	}	
}


