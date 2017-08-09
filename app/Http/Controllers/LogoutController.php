<?php namespace App\Http\Controllers;

use DB;
use Input;
use Cookie;
use Response;
use App\Http\Models\User;
use App\Http\Utils\Util;
use Illuminate\Http\Request;

class LogoutController extends Controller {
	public function index(){
		$cookie = Util::deleteCookieAccount();
		return redirect()->route('login')->withCookie($cookie);
	}
}