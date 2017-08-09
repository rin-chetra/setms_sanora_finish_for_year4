<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use Carbon\Carbon;
use App\Http\Utils\Util;
use App\Http\Models\Parameter;
use App\Http\Models\Supplier;
use App\Http\Models\AgencySale;
use App\Http\Models\AgencyPayment;
use App\Http\Models\Category;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;


class AgencyController extends Controller {

	public function index(){

		// Check Other menu's permission
		$profile = Permission::checkIncomePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission


		$suppliers = Supplier::where('typeId','=', 5)
		->orderBy('supplierName')
		->get();

		$status = Parameter::where('labelId','=', 6)
		->orderBy('sequence')
		->get();	

		$id = Input::get('status');
		if($id == "new"){

			$destinations = Parameter::where('labelId','=', 3)	
										->orderBy('sequence')								
										->get();

			return view('agencysales.store',[Constant::PERMISSION=>$profile, 'destinations'=>$destinations, 'suppliers'=>$suppliers, 'status'=>$status]);

		}else{

			// $agencysales = DB::table('agencysales AS a')
			// 				->select('a.id', 'a.saleDate', 's.supplierName', 'a.amount', 'p.parameter')
			// 				->join('suppliers AS s', 's.id', '=', 'a.agencyId')
			// 				->join('parameters AS p', 'p.id', '=', 'a.statusId')
			// 	            ->where('a.saleDate','>=',Carbon::now()->startOfMonth())
			// 	            ->where('a.saleDate','<=',Carbon::now())							
			// 				->where('a.isDelete','=', 0)
			// 				->get();	

			$categories = Category::where('isDelete','=', 0)
									->get();	


			return view('agencysales.index',[Constant::PERMISSION=>$profile,'suppliers'=>$suppliers, 'status'=>$status] );			
		}
	}

	public function show($id){

		//Check Other menu's permission
		$profile = Permission::checkIncomePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		//End Permission
		$suppliers = Supplier::where('typeId','=', 5)
								->orderBy('supplierName')
								->get();

		$status = Parameter::where('labelId','=', 6)
							->orderBy('sequence')
							->get();	
		$destinations = Parameter::where('labelId','=', 3)	
								->orderBy('sequence')								
								->get();

		$agencysales = AgencySale::where('id','=', $id)
								->where('isDelete','=', 0)
								->first();
		// Check data have or not
		if($agencysales === NULL){
			return redirect()->route('agency-sale');
		}							


		return view('agencysales.update',[Constant::PERMISSION=>$profile,'data'=> $agencysales,'suppliers'=>$suppliers, 'status'=>$status, 'destinations'=>$destinations] );

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
		
		$data = AgencySale::find($id);
		$data->isDelete = TRUE;
		// echo "delete";
		$data -> save();
		return redirect()->route('agency-sale');	
	}




	public function paymentAjax(){


		$startDate = Input::get('start');
		$endtDate = Input::get('end');
		$agencyId = Input::get('agency');
		$statusId = Input::get('status');
		$paymentDate = Input::get('paymentDate');
		$paymentBy = Input::get('paymentBy');
		$receiver = Input::get('receiver');
		$total = Input::get('total');
		$description = Input::get('description');

		if($paymentBy == "" || $receiver == ""){
			return Util::getStatusJSON("error", "validation", "paymentBy or receiver is NULL");
		}

		$agencysales = AgencySale::where('isDelete','=', 0)
						        ->where('saleDate','>=',$startDate)
						        ->where('saleDate','<=',$endtDate)		
						        ->where('statusId','=', $statusId)
						        ->where('agencyId','=', $agencyId)	
								->get();


		DB::beginTransaction(); //Start transaction!
		
		try{
			// Insert payment to table 'AgencyPayments'
			$payment = new AgencyPayment;
			$payment->paymentDate = $paymentDate;
			$payment->paymentBy = $paymentBy;
			$payment->receiver = $receiver;
			$payment->total = $total;
			$payment->description = $description;
			$payment->save();

		   	//updating status and paymentId in table 'AgencySales'
			foreach($agencysales as $item){
				$data = AgencySale::find($item->id);
				$data->statusId = 17; // paid
				$data->paymentId = $payment->id;
				$data = $data->save();
			}			
		}
		catch(\Exception $e)
		{
		  	//failed logic here
			DB::rollback();
			//throw $e;
			return Util::getStatusJSON("error", "", $e);
		}			

		DB::commit();	
		
		
		return Util::getStatusJSON("successful", "", "");
	}

	public function searchAjax(){
		$startDate = Input::get('start');
		$endtDate = Input::get('end');
		$agencyId = Input::get('agency');
		$statusId = Input::get('status');

	//	return "Start Date:".$startDate." End:".Input::get('end')." Status".Input::get('status'); 						
		if($agencyId == 0 && $statusId == 0){

			$agencysales = DB::table('agencysales AS a')
						->select('a.id AS saleId','a.saleDate', 's.supplierName', 'a.amount', 'a.profit', 'p.parameter')
						->join('suppliers AS s', 's.id', '=', 'a.agencyId')
						->join('parameters AS p', 'p.id', '=', 'a.statusId')
				        ->where('a.saleDate','>=',$startDate)
				        ->where('a.saleDate','<=',$endtDate)						
						->where('a.isDelete','=', 0)
						->get();	

		}else if($agencyId > 0 && $statusId == 0){

			$agencysales = DB::table('agencysales AS a')
						->select('a.id AS saleId','a.saleDate', 's.supplierName', 'a.amount', 'a.profit', 'p.parameter')
						->join('suppliers AS s', 's.id', '=', 'a.agencyId')
						->join('parameters AS p', 'p.id', '=', 'a.statusId')
				        ->where('a.saleDate','>=',$startDate)
				        ->where('a.saleDate','<=',$endtDate)	
				        ->where('a.agencyId','=', $agencyId)					
						->where('a.isDelete','=', 0)
						->get();	

		}else if($agencyId == 0 && $statusId > 0){

			$agencysales = DB::table('agencysales AS a')
						->select('a.id AS saleId','a.saleDate', 's.supplierName', 'a.amount', 'a.profit', 'p.parameter')
						->join('suppliers AS s', 's.id', '=', 'a.agencyId')
						->join('parameters AS p', 'p.id', '=', 'a.statusId')
				        ->where('a.saleDate','>=',$startDate)
				        ->where('a.saleDate','<=',$endtDate)	
				        ->where('a.statusId','=', $statusId)					
						->where('a.isDelete','=', 0)
						->get();	

		}else{

			$agencysales = DB::table('agencysales AS a')
						->select('a.id AS saleId', 'a.saleDate', 's.supplierName', 'a.amount', 'a.profit', 'p.parameter')
						->join('suppliers AS s', 's.id', '=', 'a.agencyId')
						->join('parameters AS p', 'p.id', '=', 'a.statusId')
				        ->where('a.saleDate','>=',$startDate)
				        ->where('a.saleDate','<=',$endtDate)		
				        ->where('a.statusId','=', $statusId)
				        ->where('a.agencyId','=', $agencyId)					
						->where('a.isDelete','=', 0)
						->get();			
		}

		$post_data = array('data' => $agencysales);
		return $post_data;
	}
	// |--------------------------------------------------------------------------
	// | Custom Function support all functions above
	// |--------------------------------------------------------------------------

	public function saveOrEdit($isSave){
		// for ($i=0; $i < 200; $i++) { 
		// 	$data = new AgencySale;

		// 	$data->createDate = Carbon::now();
		// 	$data->createBy = Util::getProfileId();				
		// 	$data->saleDate = Input::get('date');
		// 	$data->agencyId = Input::get('agency');
		// 	$data->fromDestinationId = Input::get('from');
		// 	$data->toDestinationId = Input::get('to');
		// 	$data->price = Input::get('price');
		// 	$data->qty = Input::get('quantity');
		// 	$data->amount = ($data->qty * $data->price);
		// 	$data->description = Input::get('description');
		// 	if ( ($i % 2) == 0){
		// 		$data->statusId = 16;
		// 	}else{
		// 		$data->statusId = 17;
		// 	}
			
		// 	$data->save();
		// }
		// return redirect()->route('agency-sale');	


		$validate = $this->validation($isSave);
		if($validate == FALSE){
			$data = new AgencySale;

			if($isSave == FALSE){
				$id = Input::get('id'); 
				$data = AgencySale::find($id);	

			}else{
				$data->createDate = Carbon::now();
				$data->createBy = Util::getProfileId();				
			}

			$data->saleDate = Input::get('date');
			$data->agencyId = Input::get('agency');
			$data->fromDestinationId = Input::get('from');
			$data->toDestinationId = Input::get('to');
			$data->price = Input::get('price');
			$data->qty = Input::get('quantity');
			$data->amount = ($data->qty * $data->price);
			$data->profit = Input::get('profit');
			$data->description = Input::get('description');
			$data->statusId = Input::get('status');

			$data->save();
			
			return redirect()->route('agency-sale');	
		}else{
			return $validate; 
		}

	}

	// Note: isSave is represent process(Save or Update)
	public function validation($isSave){
		$rules =  [
		'price' => 'required|min:1|max:5',
		'quantity' => 'required|min:1|max:2',
		];
		$validator = Validator::make(Input::all(), $rules);		          

		if ($validator->fails()) {
			$str = 'agency-sale?status=new';

			if($isSave == FALSE){
				$str = "agency-sale/".Input::get('id');
			}

			return redirect($str)
			->withErrors($validator)
			->withInput();

		}else{
			return FALSE;
		}
	}
}