<?php namespace App\Http\Controllers;

use DB;
use Input;
use App\Http\Models\Location;
use App\Http\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;

class ReportController extends Controller {


	public function index(){
		return view('reports.income');			
	}	

	public function income(){
		// Check Other menu's permission
		$profile = Permission::checkReportPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$locations = Location::where('isDelete','=', 0)
		->get();		

		$times = Parameter::whereIn('labelId',[4])
		->where('isDelete','=', 0)
		->get();	

		return view('reports.income',[Constant::PERMISSION=>$profile,'locations'=> $locations, 'times'=> $times] );	
	}	

	public function saleTicketAjax(){
		$startDate = Input::get('start');
		$endDate = Input::get('end');
		$time = Input::get('time');
		$location = Input::get('location');

		$sql = "SELECT * FROM vreportsale WHERE 1=1 AND saleDate >= '$startDate' AND saleDate <= '$endDate' AND TIME IS NOT NULL ";

		// Filter Time
		if($time != "NULL"){
			$sql .= " AND TIME = '$time' ";
		}
		// Filter Location
		if($location != "NULL"){
			$sql .= " AND location = '$location' ";
		}

		// Execute Query
		$sale = DB::select($sql);


		// $sale = DB::table('vreportsale')
		// ->where('saleDate','>=',$startDate)
		// ->where('saleDate','<=',$endDate)	
		// ->where('time','!=','')
		// ->get();

		$data = array('data' => $sale);
		return $data;
	}

	public function deliveryAjax(){
		$startDate = Input::get('start');
		$endDate = Input::get('end');
		$time = Input::get('time');
		$location = Input::get('location');

		$sql = "SELECT * FROM vreportsale WHERE 1=1 AND saleDate >= '$startDate' AND saleDate <= '$endDate' AND TIME IS NOT NULL AND deliveryAmount > 0";

		// Filter Time
		if($time != "NULL"){
			$sql .= " AND TIME = '$time' ";
		}
		// Filter Location
		if($location != "NULL"){
			$sql .= " AND location = '$location' ";
		}

		// Execute Query
		$delivery = DB::select($sql);

		// $delivery = DB::table('vreportsale')
		// ->where('saleDate','>=',$startDate)
		// ->where('saleDate','<=',$endDate)	
		// ->where('time','!=','')
		// ->where('deliveryAmount','>','0')
		// ->get();	

		$data = array('data' => $delivery);
		return $data;
	}

	public function saleAgencyAjax(){
		$startDate = Input::get('start');
		$endDate = Input::get('end');

		$sale = DB::table('vreportsaleagency')
		->where('saleDate','>=',$startDate)
		->where('saleDate','<=',$endDate)	
		->get();	
		
		$data = array('data' => $sale);
		return $data;
	}

	public function saleOtherAjax(){
		$startDate = Input::get('start');
		$endDate = Input::get('end');
		$location = Input::get('location');

		$sql = "SELECT * FROM vreportsale WHERE 1=1 AND saleDate >= '$startDate' AND saleDate <= '$endDate' AND TIME IS NULL ";

		// Filter Location
		if($location != "NULL"){
			$sql .= " AND location = '$location' ";
		}

		// Execute Query
		$sale = DB::select($sql);
		// $sale = DB::table('vreportsale')
		// ->where('saleDate','>=',$startDate)
		// ->where('saleDate','<=',$endDate)	
		// ->where('time',NULL)
		// ->get();	

		$data = array('data' => $sale);
		return $data;
	}

	//==================================================
	//Expense Report
	//==================================================

	public function expense(){
		// Check Other menu's permission
		$profile = Permission::checkReportPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		return view('reports.expense',[Constant::PERMISSION=>$profile] );	
	}

	public function purchaseAjax(){
		$startDate = Input::get('start');
		$endDate = Input::get('end');

		$purchases = DB::table('vpurchases')
		->where('invoiceDate','>=',$startDate)
		->where('invoiceDate','<=',$endDate)
		->get();	

		$data = array('data' => $purchases);
		return $data;
	}		

	public function purchaseDetailAjax(){
		$purchaseId = Input::get('purchaseId');

		$purchaseDetails = DB::table('vpurchasedetails')
		->where('purchaseId',$purchaseId)
		->get();	

		$data = array('data' => $purchaseDetails);
		return $data;
	}	

	public function purchaseSupplierAjax(){
		$startDate = Input::get('start');
		$endDate = Input::get('end');

		$sql = "CALL sppurchasebysuppliers('$startDate', '$endDate');";
		$purchaseSuppliers = DB::select($sql);

		$data = array('data' => $purchaseSuppliers);
		return $data;
	}


	//==================================================
	//Payroll Report
	//==================================================
	public function payroll(){
		// Check Other menu's permission
		$profile = Permission::checkReportPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		return view('reports.payroll',[Constant::PERMISSION=>$profile] );	
	}	

	public function payrollAjax(){
		$startDate = Input::get('start');
		$endDate = Input::get('end');

		$payrolls = DB::table('vpayroll')
		->where('payDate','>=',$startDate)
		->where('payDate','<=',$endDate)
		->get();
		
		$data = array('data' => $payrolls);
		return $data;
	}
}