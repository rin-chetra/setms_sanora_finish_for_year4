<?php namespace App\Http\Controllers;

use DB;
use Input;
use Illuminate\Http\Request;

class WebsiteController extends Controller {


	public function index(){
			return view('website.index');			
	}	


}