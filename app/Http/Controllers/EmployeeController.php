<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use App\Http\Models\Employee;
use App\Http\Models\File;
use App\Http\Models\Loan;
use App\Http\Models\Parameter;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use App\Http\Utils\Util;
use Illuminate\Http\Request;


class EmployeeController extends Controller {

	public function index(){

		// Check Human-Resource Menu's permission
		$profile = Permission::checkHumanResourcePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$id = Input::get('status');
		if($id == "new"){
			$parameters = Parameter::whereIn('labelId',[8, 9])
			->where('isDelete','=', 0)
			->get();

			return view('employees.store',[Constant::PERMISSION=>$profile,'positions'=> $parameters] );	

		}else{
			return view('employees.index',[Constant::PERMISSION=>$profile] );		
		}
	}

	public function employeeAjax(){
		// Get employees for binding to Table
		$employees = DB::table('employees AS e')
		->select('e.id AS empId','e.employeeName','e.address', 'p.parameter')
		->join('parameters AS p', 'p.id', '=', 'e.positionId')
		->where('p.labelId','=', 8)
		->where('e.active','=', 1)
		->where('e.isDelete','=', 0)
		->get();
		$data = array('data' => $employees);
		return $data;
	}
	public function show($id){
		// Check Human-Resource menu's permission
		$profile = Permission::checkHumanResourcePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		
		$parameters = Parameter::whereIn('labelId',[8, 9])
		->where('isDelete','=', 0)
		->get();

		$files = File::where('parentId',$id)
		->where('status','=', 'employee')
		->get();

		$employee = Employee::where('id','=', $id)
		->where('isDelete','=', 0)
		->first();

		// Check data have or not
		if($employee === NULL){
			return redirect()->route('employee');
		}


		return view('employees.update',[Constant::PERMISSION=>$profile,'data'=> $employee,'positions'=> $parameters,'files'=> $files]);
	}	


	// public function loanStoreAjax(){

	// 	$data =new Loan;
	// 	$data->employeeId = Input::get('employeeId');
	// 	$data->loanDate = Input::get('date');
	// 	$data->amount = Input::get('amount');
	// 	$data->note = Input::get('note');
	// 	$data->save();

	// 	return Util::getStatusJSON("successful", "", "");
	// 	// return "string ".Input::get('employeeId')."  ".Input::get('amount')."  ".Input::get('date')."  ".Input::get('note');

	// }

	public function store()
	{
		return $this->saveOrEdit(TRUE);
	}

	public function update(){
		return $this->saveOrEdit(FALSE);
	}

	public function destroy($id){
		// Check Human-Resource menu permission
		$profile = Permission::checkHumanResourcePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End permiision

		$data = Employee::find($id);
		$data->isDelete = TRUE;

		$data -> save();
		return redirect()->route('employee');	
	}


	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/
	public function saveOrEdit($isSave){

		$validate = $this->validation($isSave);

		if($validate == FALSE){
			

		DB::beginTransaction(); //Start transaction!
		
		try{
			// Insert/Update employee to table 'Employees'
			$data = new Employee;

			if($isSave == FALSE){
				$id = Input::get('id'); 
				$data = Employee::find($id);	
			}else{
				$data->image = "default_image.jpg";
			}
			// Note Image: in form html, we must be add => enctype="multipart/form-data" 
			
			if(Input::hasFile('file')){	
				$file = Input::file('file');
				$image = $file->getClientOriginalName();
				$file->move('images/employees', $image);
				$data->image = $image;
			}

			$data->employeeName = Input::get('name');
			$data->telephone = Input::get('telephone');
			$data->positionId = Input::get('position');
			$data->gender = Input::get('gender');
			$data->birthday = Input::get('brithday');
			$data->address = Input::get('address');
			$data->pob = Input::get('pob');
			$data->hiredate = Input::get('hiredate');
			$data->dailyPay = Input::get('dailySalary');
			$data->salary = Input::get('salary');
			$data->description = Input::get('description');
			
			
			if(Input::get('active') == "on")
				$data->active = 1;
			else
				$data->active = 0;	

			$data -> save();

		   	//Insert files in table 'Files'
			if(Input::hasFile('files')){
				foreach (Input::file('files') as $myFile) {
					
					$fileName = $myFile->getClientOriginalName();
					$myFile->move('files/employees', $fileName);


					$file = new File;
					$file->parentId = $data->id;
					$file->file = $fileName;
					$file->status = 'employee';
					$file = $file->save();
				}
			}	
		}
		catch(\Exception $e)
		{
		  	//failed logic here
			DB::rollback();
			throw $e;
		}			
		DB::commit();	

		return redirect()->route('employee');	
		


	}else{

		return $validate; 
	}



}


	// Note: isSave is represent process(Save or Update)
public function validation($isSave){
	$rules =  [
	'name' => 'required|min:3|max:30',
	'telephone' => 'required|min:9|max:50',
	];
	$validator = Validator::make(Input::all(), $rules);		          

	if ($validator->fails()) {
		$str = 'employee?status=new';

		if($isSave == FALSE){
			$str = "employee/".Input::get('id');
		}

		return redirect($str)
		->withErrors($validator)
		->withInput();

	}else{
		return FALSE;
	}
}			
}