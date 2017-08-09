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

class OfficesaleController extends Controller {
	public function index(){
		// Check Other menu's permission
		$profile = Permission::checkIncomePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		$employees = Employee::where('active','=', 1)
		->where('positionId','=', 21)
		->where('isDelete','=', 0)
		->get();	


		$id = Input::get('status');
		if($id == "new"){

			$parameters = Parameter::whereIn('labelId',[4, 5, 7, 8])
			->where('isDelete','=', 0)
			->get();	

			return view('officesales.store',[Constant::PERMISSION=>$profile, 'parameters'=> $parameters,'employees'=>$employees]);			

		}else{

			$locations = Location::where('isDelete','=', 0)
			->get();	


			return view('officesales.index',[Constant::PERMISSION=>$profile,'locations'=>$locations,'drivers'=>$employees] );		
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

		return view('officesales.update',[Constant::PERMISSION=>$profile, 'data'=> $officesales, 'parameters'=> $parameters,'employees'=>$employees]);

	}







	public function searchAjax(){
		$startDate = Input::get('start');
		$endtDate = Input::get('end');
		$driver = Input::get('driver');
		$location = Input::get('location');		


		if($driver == 0 && $location == 0){
			
			$officesales = DB::table('officesales AS s')
			->select('s.id AS saleId', 's.saleDate', 's.qty', 's.amount', 's.deliveryAmount','p.parameter','e.employeeName', 'l.label AS location')
			->join('parameters AS p', 'p.id', '=', 's.timeId')
			->join('employees AS e', 'e.id', '=', 's.employeeId')
			->join('locations AS l', 'l.id', '=', 's.locationId')
			->where('s.saleDate','>=',$startDate)
			->where('s.saleDate','<=',$endtDate)	
			->where('s.isDelete','=', 0)
			->get();
		}else if($driver > 0 && $location == 0){
			$officesales = DB::table('officesales AS s')
			->select('s.id AS saleId', 's.saleDate', 's.qty', 's.amount', 's.deliveryAmount','p.parameter','e.employeeName', 'l.label AS location')
			->join('parameters AS p', 'p.id', '=', 's.timeId')
			->join('employees AS e', 'e.id', '=', 's.employeeId')
			->join('locations AS l', 'l.id', '=', 's.locationId')
			->where('s.saleDate','>=',$startDate)
			->where('s.saleDate','<=',$endtDate)	
			->where('s.employeeId','=',$driver)	
			->where('s.isDelete','=', 0)
			->get();
		}else if($driver == 0 && $location > 0){
			$officesales = DB::table('officesales AS s')
			->select('s.id AS saleId', 's.saleDate', 's.qty', 's.amount', 's.deliveryAmount','p.parameter','e.employeeName', 'l.label AS location')
			->join('parameters AS p', 'p.id', '=', 's.timeId')
			->join('employees AS e', 'e.id', '=', 's.employeeId')
			->join('locations AS l', 'l.id', '=', 's.locationId')
			->where('s.saleDate','>=',$startDate)
			->where('s.saleDate','<=',$endtDate)	
			->where('s.locationId','=',$location)	
			->where('s.isDelete','=', 0)
			->get();

		}else if($driver > 0 && $location > 0){
			$officesales = DB::table('officesales AS s')
			->select('s.id AS saleId', 's.saleDate', 's.qty', 's.amount', 's.deliveryAmount','p.parameter','e.employeeName', 'l.label AS location')
			->join('parameters AS p', 'p.id', '=', 's.timeId')
			->join('employees AS e', 'e.id', '=', 's.employeeId')
			->join('locations AS l', 'l.id', '=', 's.locationId')
			->where('s.saleDate','>=',$startDate)
			->where('s.saleDate','<=',$endtDate)	
			->where('s.locationId','=',$location)
			->where('s.employeeId','=',$driver)		
			->where('s.isDelete','=', 0)
			->get();			
		}

		$post_data = array('data' => $officesales);
		return $post_data;
	}








	// function store(){
	// 	$data = new Officesale;

	// 	$data->saleDate = Input::get('date');
	// 	$data->timeId = Input::get('time');
	// 	$data->carId = Input::get('car');
	// 	$data->employeeId = Input::get('car');
	// 	$data->fromDestinationId = Input::get('from');
	// 	$data->toDestinationId = Input::get('to');
	// 	$data->price = Input::get('price');
	// 	$data->qty = Input::get('quantity');
	// 	$data->discount = Input::get('discount');
	// 	$dis = ($data->price * $data->qty * $data->discount)/100;
	// 	$data->amount = ($data->price * $data->qty) - $dis;
	// 	$data->description = Input::get('description');
	// 	$data->createDate = Input::get('creatdate');
	// 	$data->locationId = Input::get('location');

	// 	$data->save();
	// 	return redirect()->route('office-sale');
	// }


	// public function update(){

	// 	$id = Input::get('id');
	// 	$data = Officesale::find($id);

	// 	$data->saleDate = Input::get('date');
	// 	$data->timeId = Input::get('time');
	// 	$data->carId = Input::get('car');
	// 	$data->employeeId = Input::get('car');
	// 	$data->fromDestinationId = Input::get('from');
	// 	$data->toDestinationId = Input::get('to');
	// 	$data->price = Input::get('price');
	// 	$data->qty = Input::get('quantity');
	// 	$data->discount = Input::get('discount');
	// 	$dis = ($data->price * $data->qty * $data->discount)/100;
	// 	$data->amount = ($data->price * $data->qty) - $dis;
	// 	$data->description = Input::get('description');
	// 	$data->createDate = Input::get('creatdate');
	// 	$data->locationId = Input::get('location');

	 	// Check data have or not
	// 	if($data === NULL){
	// 		return redirect()->route('office-sale');
	// 		}
	// 	$data->save();
	// 	return redirect()->route('office-sale');
	// }


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

		return redirect()->route('office-sale');	
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
			$data->timeId = Input::get('time');
			$data->carId = Input::get('car');
			$data->employeeId = Input::get('driver');
			$data->fromDestinationId = Input::get('from');
			$data->toDestinationId = Input::get('to');
			$data->discount = Input::get('discount');
			$data->price = Input::get('price');
			$data->qty = Input::get('quantity');
			$data->amount = ($data->price * $data->qty) - $data->discount ;
			$data->deliveryAmount = Input::get('deliveryAmount');
			$data->description = Input::get('description');
			$data->locationId = $profile->locationId;

			$data->save();
			return redirect()->route('office-sale');	

		}else{
			return $validate; 
		}
		
	}

	public function validation($isSave){
		$rules =  [
		'price' => 'required|min:1|max:7',
		'quantity' => 'required|min:1|max:7',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'office-sale?status=new';

			if($isSave == FALSE){
				$str = "office-sale/".Input::get('id');
			}

			return redirect($str)
			->withErrors($validator)
			->withInput();

		}else{
			return FALSE;
		}
	}
}



