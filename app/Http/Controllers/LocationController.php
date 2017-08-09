<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use App\Http\Models\Location;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;


class LocationController extends Controller {


	public function index(){
		// Check Setting Menu's permission
		$profile = Permission::checkSettingPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		$id = Input::get('status');
		if($id == "new"){

			return view('locations.store',[Constant::PERMISSION=>$profile]);						

		}else{

			// Get locations for binding to Table
			$locations = Location::where('isDelete','=', 0)
	            		->get();			

			return view('locations.index',[Constant::PERMISSION=>$profile, 'data'=> $locations] );			
		}
	}	




	public function show($id){
		// Check Setting Menu's permission
		$profile = Permission::checkSettingPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		$location = Location::where('id','=', $id)
								->where('isDelete','=', 0)
								->first();
		// Check data have or not
		if($location === NULL){
			return redirect()->route('location');
		}


		return view('locations.update',[Constant::PERMISSION=>$profile,'data'=> $location] );
	}




	public function store()
	{
    	return $this->saveOrEdit(TRUE);
	}



	public function update(){
		return $this->saveOrEdit(FALSE);
	}	



	public function destroy($id){
		// Check Setting Menu's permission
		$profile = Permission::checkSettingPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		
		$data = Location::find($id);
		$data->isDelete = TRUE;

		$data -> save();
		return redirect()->route('location');	
	}


	public function saveAjax()
	{
		return $this->saveOrEdit(TRUE, TRUE);
	}

	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/
	public function saveOrEdit($isSave, $isAjax=FALSE){

	 	$validate = $this->validation($isSave);

		if($validate == FALSE){
			$data = new Location;

			if($isSave == FALSE){
				$id = Input::get('id'); 
				$data = Location::find($id);	
			}


			$data->label = Input::get('name');
			$data->address = Input::get('address');
			$data -> save();



			if($isAjax == TRUE){
				return $data;
			}else{
				return redirect()->route('location');	
			}
			

		}else{

			return $validate; 
		}
	}
	// Note: isSave is represent process(Save or Update)
	public function validation($isSave){
		$rules =  [
		'name' => 'required|min:3|max:30',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'locations?status=new';

			if($isSave == FALSE){
				$str = "location/".Input::get('id');
			}
			return redirect($str)
					->withErrors($validator)
					->withInput();

		}else{
			return FALSE;
		}
	}	

}