<?php namespace App\Http\Controllers;
use DB;
use Input;
use Validator;
use App\Http\Models\Product;
use App\Http\Models\Category;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;

class ProductController extends Controller {
	public function index(){
		// Check Other menu's permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$id = Input::get('status');
		if($id == "new"){
			$categories = Category::where('isDelete','=', 0)
									->get();
			return view('products.store',[Constant::PERMISSION=>$profile,'categories'=> $categories] );	
		}else{
			// Get Products for binding to Table
			$products = DB::table('categories')
							->join('products', 'categories.id', '=', 'products.categoryId')
							->where('products.isDelete','=', 0)
							->get();
							
			return view('products.index',[Constant::PERMISSION=>$profile, 'data'=> $products] );		
		}
	}

	public function show($id){
		// Check Other menu's permission
		$profile = Permission::checkOtherPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		$product = Product::where('id','=', $id)
							->where('isDelete','=', 0)
							->first();

		// Check data have or not
		if($product === NULL){
			return redirect()->route('product');
		}

		$categories = Category::where('isDelete','=', 0)
								->get();

		return view('products.update',[Constant::PERMISSION=>$profile,'data'=> $product], ['categories'=> $categories]);
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

		$data = Product::find($id);
		$data->isDelete = TRUE;
		$data -> save();

		return redirect()->route('product');	
	}




	/* 
	|--------------------------------------------------------------------------
	| Custom Function support all functions above
	|--------------------------------------------------------------------------
	*/
	public function saveOrEdit($isSave){

		$validate = $this->validation($isSave);
		if($validate == FALSE){
			$data = new Product;

			if($isSave == FALSE){
				$id = Input::get('id'); 
				$data = Product::find($id);	
			}else{
				$data->image = "default_image.jpg";
			}
			// Note Image: in form html, we must be add => enctype="multipart/form-data" 
			if(Input::hasFile('file')){	
				$file = Input::file('file');
				$image = $file->getClientOriginalName();
				$file->move('images/products', $image);
				$data->image = $image;
			}

			$data->productName = Input::get('name');
			$data->categoryId = Input::get('category');
			$data->description = Input::get('description');
			

			$data->save();

			return redirect()->route('product');	
		}else{
			return $validate; 
		}
		
	}

	// Note: isSave is represent process(Save or Update)
	public function validation($isSave){
		$rules =  [
		'name' => 'required|min:3|max:50',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'product?status=new';

			if($isSave == FALSE){
				$str = "product/".Input::get('id');
			}

			return redirect($str)
					->withErrors($validator)
					->withInput();

		}else{
			return FALSE;
		}
	}
}

