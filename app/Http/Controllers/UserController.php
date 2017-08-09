<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use Carbon\Carbon;
use App\Http\Models\User;
use App\Http\Utils\Util;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use App\Http\Models\Location;
use Illuminate\Http\Request;
class UserController extends Controller {


	public function index(){
		// Check Setting Menu's permission
		$profile = Permission::checkSettingPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$id = Input::get('status');
		if($id == "new"){

			$locations = Location::where('isDelete','=', 0)
						->get();

			return view('users.store',[Constant::PERMISSION=>$profile,'locations'=> $locations] );						

		}else{

			// Get users for binding to Table
			$users = DB::table('locations')
	            	->join('users', 'locations.id', '=', 'users.locationId')
	            	->where('users.isDelete','=', 0)
	            	->where('users.isHide','=', 0)
	            	->get();


			return view('users.index',[Constant::PERMISSION=>$profile,'data'=> $users] );			
		}
	}	

	

	
	public function show($id){
		// Check Setting Menu's permission
		$profile = Permission::checkSettingPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$locations = Location::where('isDelete','=', 0)
						->get();

		$user = User::where('id','=', $id)
						->where('isDelete','=', 0)
		        		->first();

		// Check data have or not
		if($user === NULL){
			return redirect()->route('user');
		}		        		

		return view('users.update',[Constant::PERMISSION=>$profile,'data'=> $user],['locations'=> $locations] );
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


		$data = User::find($id);
		$data->isDelete = TRUE;

		$data -> save();
		return redirect()->route('user');	
	}



	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/
	public function saveOrEdit($isSave){


		// Validate data input from user. 
		$validate = $this->validation($isSave);

		// If return FALSE mean data is correct.
		if($validate == FALSE){
			$data = new User;
			if($isSave == FALSE){

				$id = Input::get('id'); 
				$data = User::find($id);	
			}else{
				$data->image = "default_image.jpg";
				$data->createDate = Carbon::now();
				$data->createBy = Util::getProfileId();
			}

			// Note Image: in form html, we must be add => enctype="multipart/form-data" 
			
			if(Input::hasFile('file')){	
				$file = Input::file('file');
				$image = $file->getClientOriginalName();
				$file->move('images/users', $image);
				$data->image = $image;
			}

			// For personal user
			$data->name = Input::get('name');
			$data->email = Input::get('email');
			$data->password = Input::get('password');
			$data->phone = Input::get('telephone');
			$data->locationId = Input::get('location');
			

			// For permission
			//--------------------------------------------------------------------------
			if(Input::get('isIncome') == "on")
				$data->isIncome = 1;
			else
				$data->isIncome = 0;

			if(Input::get('isExpense') == "on")
				$data->isExpense = 1;
			else
				$data->isExpense = 0;

			if(Input::get('isHumanResource') == "on")
				$data->isHumanResource = 1;
			else
				$data->isHumanResource = 0;

			if(Input::get('isOther') == "on")
				$data->isOther = 1;
			else
				$data->isOther = 0;			

			if(Input::get('isReport') == "on")
				$data->isReport = 1;
			else
				$data->isReport = 0;

			if(Input::get('isSetting') == "on")
				$data->isSetting = 1;
			else
				$data->isSetting = 0;											

			// For access modifier
			//--------------------------------------------------------------------------
			if(Input::get('allowCreate') == "on")
				$data->allowCreate = 1;
			else
				$data->allowCreate = 0;		

			if(Input::get('allowUpdate') == "on")
				$data->allowUpdate = 1;
			else
				$data->allowUpdate = 0;	

			if(Input::get('allowDelete') == "on")
				$data->allowDelete = 1;
			else
				$data->allowDelete = 0;				

			$data -> save();
			return redirect()->route('user');	
		}else{
			return $validate;
		}

	}

	// Note: isSave is represent process(Save or Update)
	public function validation($isSave){
		$rules =  [
		'name' => 'required|min:3|max:30',
		'email' => 'required|email',
		'password' => 'required|min:3|max:10',
		'location' => 'required',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'users?status=new';

			if($isSave == FALSE){
				$str = "users/".Input::get('id');
			}			

			return redirect($str)
						->withErrors($validator)
						->withInput();    
     	
		}else{
			return FALSE;
		}
	}

}