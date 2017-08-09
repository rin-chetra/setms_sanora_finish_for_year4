<?php namespace App\Http\Controllers;

use DB;
use Input;
use App\Http\Models\Percentage;
use App\Http\Modeloginers;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;

use Illuminate\Http\Request;
class DashboardController extends Controller {

	public function index(){
		
		// Check User Login
		$profile = Permission::checkDashboardPermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}

		

		return view('dashboard.index',[Constant::PERMISSION=>$profile] );		
	}

	public function searchIncomeAjax()
	{
		$searchDate = Input::get('date');
		$startDate = $searchDate.'-01';
		$endDate = $searchDate.'-'.date('t', strtotime($startDate) );
		
		//Execute StoreProcedure to get Income
		$sql = "CALL spincome('$startDate', '$endDate');";
		$totalIncome = DB::select($sql);

		//Execute StoreProcedure to get Detail of Income;
		$sql = "CALL spDetailIncome('$startDate', '$endDate');";
		$detailIncome = DB::select($sql);			

		//Execute StoreProcedure to get Expense Purchase;
		$sql = "CALL spexpensepurchase('$startDate', '$endDate');";
		$totalExpensePurchase = DB::select($sql);

				//Execute StoreProcedure to get Expense Payroll;
		$sql = "CALL spexpensepayroll('$startDate', '$endDate');";
		$totalExpensePayroll = DB::select($sql);

		//Execute StoreProcedure to get Annual Report
		$sql = "CALL spannualreport ('2017');";
		$annualReport = DB::select($sql);

		//Expense by suppliers
		$sql = "CALL sppurchasebysuppliers('$startDate', '$endDate');";
		$suppliers = DB::select($sql);

		$maxTotal = 0;
		foreach ($suppliers as $key => $value) {
			$supplier = new Percentage;
			$supplier->supplierId = $value->supplierId;
			$supplier->supplierName = $value->supplierName;
			$supplier->total = $value->total;
			if($key == 0){
				$maxTotal = $supplier->total; 
				$supplier->percentage = 100;
			}else{

				$supplier->percentage = $supplier->total * 100 / $maxTotal;
			}
			
			$listOfSuppliers[] = $supplier;
		}


		$data['income'] = $totalIncome;
		$data['detailIncome'] = $detailIncome;
		$data['expensePurchase'] = $totalExpensePurchase;
		$data['expensePayroll'] = $totalExpensePayroll;
		$data['annualReport'] = $annualReport;
		$data['suppliers'] = $listOfSuppliers;


		return $data;
	}

}
