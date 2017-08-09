<?php namespace App\Http\Controllers;
use DB;
use Input;
use Validator;
use Carbon\Carbon;
use App\Http\Utils\Util;
use App\Http\Models\Product;
use App\Http\Models\Purchase;
use App\Http\Models\Purchasedetail;
use App\Http\Models\Supplier;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;

class PurchaseController extends Controller {

	public function index(){
		// Check Other menu's permission
		$profile = Permission::checkExpensePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission
		$products = Product::where('isDelete','=', 0)
		->get();

		$supplier = Supplier::where('isDelete','=', 0)
		->get();

		$id = Input::get('status');
		if($id == "new"){

			return view('purchases.store',[Constant::PERMISSION=>$profile,'supplier'=> $supplier, 'products'=> $products]);	
		}else{

			$purchases = DB::table('vpurchases')->get();	
			//print_r($purchases);
			return view('purchases.index',[Constant::PERMISSION=>$profile,'data'=> $purchases] );	

		}
	}

	public function show($id){

		// Check Other menu permission
		$profile = Permission::checkExpensePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End permiision

		$purchase = Purchase::where('id','=', $id)
		->where('isDelete','=', 0)
		->first();

		// Check data have or not
		if($purchase === NULL){
			return redirect()->route('supplier');
		}	

		$details = Purchasedetail::where('purchaseId','=', $id)->get();
		$products = Product::where('isDelete','=', 0)->get();
		$supplier = Supplier::where('isDelete','=', 0)->get();

		return view('purchases.update',[Constant::PERMISSION=>$profile,'data'=> $purchase,'supplier'=> $supplier,'products'=> $products,'details'=> $details]);
	}

	public function store(Request $req){

		// Check Other menu permission
		$profile = Permission::checkExpensePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End permiision


		$isComplete = TRUE;
		DB::beginTransaction(); //Start transaction!
		
		try{
			// Insert payment to table 'Purchase'
			$purchase = new Purchase;
			$purchase->supplierId = Input::get('supplier');
			$purchase->invoiceNo = Input::get('invoiceno');
			$purchase->invoiceDate = Input::get('invoicedate');
			$purchase->createDate = Carbon::now();
			$purchase->createBy = Util::getProfileId();
			$purchase->save();

			// Insert payment to table 'PurchaseDetails'	
			foreach ($req->productName as $key => $value) {
				$data = array(  
					'purchaseId' => $purchase->id,
					'productId' => $value,
					'qty' =>  $req->qty[$key],
					'price' =>  $req->price[$key],
					'discount' =>  $req->dis[$key],
					'amount' =>  $req->amount[$key],
					);	

				Purchasedetail::insert($data);
			}

		}
		catch(\Exception $e)
		{
			$isComplete = FALSE;
			DB::rollback();
			$validator = [
			'Erorr'    => 'Saving error, the information has something wrong.',
			];

			//throw $e;
			return redirect('purchase?status=new')
			->withErrors($validator)
			->withInput();
		}			
		
		DB::commit();

		if($isComplete){
			return redirect()->route('purchase');
		}

	}	

	public function update(Request $req){

		// Check Other menu permission
		$profile = Permission::checkExpensePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End permiision

		
		$isComplete = TRUE;
		DB::beginTransaction(); //Start transaction!
		
		try{
			// Update payment to table 'Purchase'
			$id = Input::get('id'); 
			$purchase = Purchase::find($id);
			$purchase->supplierId = Input::get('supplier');
			$purchase->invoiceNo = Input::get('invoiceno');
			$purchase->invoiceDate = Input::get('invoicedate');
			$purchase->createDate = Carbon::now();
			$purchase->createBy = Util::getProfileId();
			$purchase->save();

			
			// Delete some records follow purchase id
			$details = Purchasedetail::where('purchaseId','=', $id );
			$details->delete();


			// Insert payment to table 'PurchaseDetails'
			foreach ($req->productName as $key => $value) {
				$data = array(  
					'purchaseId' => $purchase->id,
					'productId' => $value,
					'qty' =>  $req->qty[$key],
					'price' =>  $req->price[$key],
					'discount' =>  $req->dis[$key],
					'amount' =>  $req->amount[$key],
					);	

				Purchasedetail::insert($data);
			}

		}
		catch(\Exception $e)
		{
			$isComplete = FALSE;
			DB::rollback();
			$validator = [
			'Erorr'    => 'Editing error, the information has something wrong.',
			];

			//throw $e;
			return redirect('purchase/'.$id)
			->withErrors($validator)
			->withInput();
		}			
		
		DB::commit();

		if($isComplete){
			return redirect()->route('purchase');
		}
	}

	public function destroy($id){
		// Check Other menu's permission
		$profile = Permission::checkExpensePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$data = Purchase::find($id);
		$data->isDelete = TRUE;
		$data -> save();

		return redirect()->route('purchase');
	}
}