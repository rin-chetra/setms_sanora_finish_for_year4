<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use App\Http\Models\Supplier;
use App\Http\Models\Parameter;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;


class SupplierController extends Controller {


	public function index(){

		// Check Other menu permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End permiision


		$id = Input::get('status');
		if($id == "new"){
			// Get parameters for binding to ComboBox
			$parameters = Parameter::where('isDelete','=', 0)
									->where('labelId','=', 2)
					            	->get();	

			return view('suppliers.store',[Constant::PERMISSION=>$profile,'parameters'=> $parameters]);

		}else{

			// Get locations for binding to Table
			$suppliers = Supplier::where('isDelete','=', 0)
									->get();			

			return view('suppliers.index',[Constant::PERMISSION=>$profile, 'data'=> $suppliers] );			
		}		
	}

	public function show($id){

		// Check Other menu permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End permiision

		$supplier = Supplier::where('id','=', $id)
								->where('isDelete','=', 0)
								->first();
		
		// Check data have or not
		if($supplier === NULL){
			return redirect()->route('supplier');
		}	
			// Get parameters for binding to ComboBox
		$parameters = Parameter::where('isDelete','=', 0)
								->where('labelId','=', 2)
								->get();	
		return view('suppliers.update',[Constant::PERMISSION=>$profile,'data'=> $supplier,'parameters'=> $parameters]);
	}



	public function store()
	{
		return $this->saveOrEdit(TRUE);
	}

	public function update(){
		return $this->saveOrEdit(FALSE);
	}		

	public function destroy($id){
		// Check Other menu permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End permiision

		$data = Supplier::find($id);
		$data->isDelete = TRUE;

		$data -> save();
		return redirect()->route('supplier');	
	}


	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/
	public function saveOrEdit($isSave, $isAjax=FALSE){

		$validate = $this->validation($isSave);

		if($validate == FALSE){
			$data = new Supplier;

			if($isSave == FALSE){
				$id = Input::get('id'); 
				$data = Supplier::find($id);	
			}


			$data->supplierName = Input::get('name');
			$data->telephone = Input::get('telephone');
			$data->address = Input::get('address');
			$data->description = Input::get('description');
			$data->typeId = Input::get('type');
			$data -> save();



			if($isAjax == TRUE){
				return $data;
			}else{
				return redirect()->route('supplier');	
			}
			

		}else{

			return $validate; 
		}

		
		
	}


	// Note: isSave is represent process(Save or Update)
	public function validation($isSave){
		$rules =  [
		'name' => 'required|min:3|max:30',
		'telephone' => 'required|min:9|max:50',
		'address' => 'required|max:200',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'supplier?status=new';

			if($isSave == FALSE){
				$str = "supplier/".Input::get('id');
			}

			return redirect($str)
			->withErrors($validator)
			->withInput();

		}else{
			return FALSE;
		}
	}		

}