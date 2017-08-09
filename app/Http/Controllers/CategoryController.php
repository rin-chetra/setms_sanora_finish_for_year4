<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;

use App\Http\Models\Category;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;


class CategoryController extends Controller {

	public function index(){
		// Check Other menu's permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		$id = Input::get('status');
		if($id == "new"){
			return view('categories.store',[Constant::PERMISSION=>$profile] );

		}else{

	    	$categories = Category::where('isDelete','=', 0)
							->get();			

			return view('categories.index',[Constant::PERMISSION=>$profile,'data'=> $categories] );			
		}
	}



	public function show($id){

		// Check Other menu's permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$category = Category::where('id','=', $id)
							->where('isDelete','=', 0)
							->first();

		// Check data have or not
		if($category === NULL){
			return redirect()->route('category');
		}							

		return view('categories.update',[Constant::PERMISSION=>$profile,'data'=> $category] );
	}



	public function store(){
		return $this->saveOrEdit(TRUE);
	}


	public function update(){
		return $this->saveOrEdit(FALSE);
	}		

	public function destroy($id){
		// Check Other menu's permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		
		$data = Category::find($id);
		$data->isDelete = TRUE;

		$data -> save();
		return redirect()->route('category');	
	}

	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/
	public function saveOrEdit($isSave){

		$validate = $this->validation($isSave);
		if($validate == FALSE){
			$data = new Category;

			if($isSave == FALSE){
				$id = Input::get('id'); 
				$data = Category::find($id);	
			}

			$data->name = Input::get('name');
			$data->description = Input::get('description');
			$data->save();

			return redirect()->route('category');	
		}else{
			return $validate; 
		}
		
	}

	// Note: isSave is represent process(Save or Update)
	public function validation($isSave){
		$rules =  [
		'name' => 'required|min:1|max:30',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'category?status=new';

			if($isSave == FALSE){
				$str = "category/".Input::get('id');
			}

			return redirect($str)
					->withErrors($validator)
					->withInput();

		}else{
			return FALSE;
		}
	}
}