<?php namespace App\Http\Controllers;
use DB;
use Input;
use Validator;
use Carbon\Carbon;
use App\Http\Models\Officesale;
use App\Http\Models\Location;
use App\Http\Models\Parameter;
use App\Http\Models\Employee;
use App\Http\Utils\Util;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;

class MiscellaneousController extends Controller {
	public function index(){
		// Check Other menu's permission
		$profile = Permission::checkIncomePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission	

		$id = Input::get('status');
		if($id == "new"){
			$employees = Employee::where('active','=', 1)
			->where('positionId','=', 21)
			->where('isDelete','=', 0)
			->get();	

			$parameters = Parameter::whereIn('labelId',[4, 5, 7, 8])
			->where('isDelete','=', 0)
			->get();	

			return view('miscellaneous.store',[Constant::PERMISSION=>$profile, 'parameters'=> $parameters,'employees'=>$employees]);			

		}else{
			return view('miscellaneous.index',[Constant::PERMISSION=>$profile] );		
		}
	}

	public function show($id){

		// Check Other menu's permission
		$profile = Permission::checkIncomePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$officesales = DB::table('officesales')
		->where('officesales.id','=', $id)
		->first();


		$employees = Employee::where('active','=', 1)
		->where('positionId','=', 21)
		->where('isDelete','=', 0)
		->get();	

		$parameters = Parameter::whereIn('labelId',[4, 5, 7, 8])
		->where('isDelete','=', 0)
		->get();		

		// Check data have or not
		if($officesales === NULL){
			return redirect()->route('office-sale');
		}							

		return view('miscellaneous.update',[Constant::PERMISSION=>$profile, 'data'=> $officesales, 'parameters'=> $parameters,'employees'=>$employees]);

	}







	public function searchAjax(){
		$startDate = Input::get('start');
		$endtDate = Input::get('end');

		$miscellaneous = DB::table('vreportsale')
		->where('saleDate','>=',$startDate)
		->where('saleDate','<=',$endtDate)	
		->where('time',NULL)
		->get();	

		$post_data = array('data' => $miscellaneous);
		return $post_data;
	}

	public function store(){

		return $this->saveOrEdit(TRUE);
	}		
	

	public function update(){
		return $this->saveOrEdit(FALSE);
	}


	public function destroy($id){
	 	// Check Other menu's permission
		$profile = Permission::checkIncomePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
	 	// End Permission
		
		$data = Officesale::find($id);

		$data->isDelete = TRUE;
		$data -> save();

		return redirect()->route('miscellaneous-sale');	
	}

	public function saveOrEdit($isSave){

		$validate = $this->validation($isSave);
		if($validate == FALSE){
			$data = new Officesale;
			$profile = Util::getProfile();
			if($isSave == FALSE){
				$id = Input::get('id'); 
				$data = Officesale::find($id);	
			}else{
				$data->createDate = Carbon::now();
				$data->createBy = $profile->id;
			}

			$data->saleDate = Input::get('date');
			$data->carId = Input::get('car');
			$data->employeeId = Input::get('driver');
			$data->amount = Input::get('amount');
			$data->description = Input::get('description');
			$data->locationId = $profile->locationId;

			$data->save();
			return redirect()->route('miscellaneous-sale');	

		}else{
			return $validate; 
		}
		
	}

	public function validation($isSave){
		$rules =  [
		'amount' => 'required|min:1|max:7',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'miscellaneous-sale?status=new';

			if($isSave == FALSE){
				$str = "miscellaneous-sale/".Input::get('id');
			}

			return redirect($str)
			->withErrors($validator)
			->withInput();

		}else{
			return FALSE;
		}
	}
}



