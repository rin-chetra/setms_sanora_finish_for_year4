<?php namespace App\Http\Utils;
use DB;
use Request;
use Cookie;
use App\Http\Models\User;

class Util{

	public static function createCookieAccount($userId){
		return Cookie::make(Constant::COOKIE_ACCOUNT, $userId, 45000);
	}

	public static function deleteCookieAccount(){
		return Cookie::forget('account');
	}	
	

	public static function getProfile(){
		$user = null;
		$id = Util::getProfileId();

		if(Request::hasCookie(Constant::COOKIE_ACCOUNT)) {
			$user = DB::table('users')
					->where('users.isDelete','=', 0)
					->where('users.id','=', $id)
					->first();
		
		}
		return $user;
	}

	public static function checkLogin(){
		$isHas = FALSE;
		if(Request::hasCookie(Constant::COOKIE_ACCOUNT)) {
			$isHas = TRUE;
		}
		return $isHas;
	}

	public static function getProfileId(){
		return Cookie::get(Constant::COOKIE_ACCOUNT);
	}

	public static function getStatusJSON($status, $data, $description){
		$result = array(

			'status'=> $status, 
			'data'=> $data,
			'description'=> $description,
		);

		return $result;
	}

}