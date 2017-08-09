<?php namespace App\Http\Controllers;

use DB;
use Input;
use App\Http\Models\Label;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use App\Http\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends Controller {

	public function index(){
		
		// Check Setting Menu's permission
		$profile = Permission::checkSettingPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		$id = Input::get('id');
		if($id == ''){
			$id = 1;				
		}


		// Get Labels for binding to DropdownBox
		$labels = Label::where('isDelete','=', 0)
	            	->get();


		// Get parameters for binding to Table
		$parameters = Parameter::where('isDelete','=', 0)
					->where('labelId','=', $id)
	            	->get();	            		


		return view('parameters.index',[Constant::PERMISSION => $profile,'data'=> $parameters],['label'=> $labels] );			
		
	}	


	public function store()
	{
		return $this->saveOrEdit();
	}

	public function destroy($id){
		// Check Setting Menu's permission
		$profile = Permission::checkSettingPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		
		$data = Parameter::find($id);
		$labelId = $data->labelId;
		$data->isDelete = TRUE;

		$data -> save();
		return redirect()->route('parameter',['id'=>$labelId]);	
	}	




	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/
	public function saveOrEdit(){

		$data = new Parameter;
		$id = Input::get('id');
		if($id != ''){
			$data = Parameter::find($id);	
		}
		
		$data->labelId = Input::get('typeId');
		$data->parameter = Input::get('name');
		$data->value = Input::get('number');
		$data->sequence = Input::get('sequence');

		$data -> save();
		return redirect()->route('parameter',['id'=>$data->labelId]);	
	}
		
}