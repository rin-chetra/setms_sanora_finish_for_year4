<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use App\Http\Models\Payroll;
use App\Http\Models\Loan;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;


class PayrollController extends Controller {
	public function index(){
		// Check Human Resource Menu's permission
		$profile = Permission::checkHumanResourcePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		return view('payrolls.index',[Constant::PERMISSION=>$profile] );			
		
	}	

	public function show($id){

		// Check Other menu's permission
		$profile = Permission::checkHumanResourcePermission();
		if($profile === FALSE){
			return redirect()->route('login');	
		}
		// End Permission

		$payroll = DB::table('vpayroll')->where('id',$id)->first();

		//Check data have or not
		if($payroll === NULL){
			return redirect()->route('payroll');
		}							

		return view('payrolls.detail',[Constant::PERMISSION=>$profile,'data'=> $payroll]);
	}

	public function generatePayrollAjax()
	{

		$generateDate = Input::get('date');
		$startDate = $generateDate.'-01';
		$endDate = $generateDate.'-'.date('t', strtotime($startDate) );

		//Delete old data
		$sql = "CALL spdeletepayroll('$startDate', '$endDate');";
		$delete = DB::select($sql);

		// Generate Payroll
		$sql = "CALL spayrolls('$startDate', '$endDate');";
		$payrolls = DB::select($sql);
		
		$records = array();
		foreach ($payrolls as $key => $value) {
			# code...
			$data = new Payroll;
			$data->employeeId = $value->employeeId;
			$data->baseSalary = $value->baseSalary;
			$data->daily = $value->daily;
			$data->bonus = $value->bonus;
			$data->loan = $value->loan;
			$data->absence = $value->absence;
			$data->total = $data->baseSalary + $data->daily + $data->bonus - $data->loan - $data->absence;
			$data->payDate = $startDate;
			$data->save();

			$value->id = $data->id;
			$value->total = $data->total;
			$records[] = $value;
		}
		$data = array('data' => $records);
		return $data;
	}

	public function searchPayrollAjax()
	{
		$searchDate = Input::get('date');
		$startDate = $searchDate.'-01';
		$endDate = $searchDate.'-'.date('t', strtotime($startDate) );
		//
		$payrolls = DB::table('vpayroll')
		->where('payDate','>=',$startDate)
		->where('payDate','<=',$endDate)
		->get();

		$data = array('data' => $payrolls);
		return $data;
	}
}