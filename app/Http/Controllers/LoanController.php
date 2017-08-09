<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use App\Http\Models\Loan;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use App\Http\Utils\Util;
use Illuminate\Http\Request;


class LoanController extends Controller {


	public function index(){
		$loans = Loan::where('isDelete','=', 0)
		->where('employeeId', Input::get('employeeId'))
		->whereMonth('loanDate', '=', date('m'))
		->whereYear('loanDate', '=', date('Y'))
		->get();	
		return $loans;
	}


	public function show($id){
		$loan = Loan::where('id', $id)
		->where('isDelete','=', 0)
		->first();
		return $loan;
	}


	public function searchAjax(){
		$empId = Input::get('empId');
		$date = Input::get('date');
		$endDate = date("Y-m-t", strtotime($date));
		//
		$loan = Loan::where('employeeId', $empId)
		->where('isDelete','=', 0)
		->where('loanDate', '>=', $date)
		->where('loanDate', '<=', $endDate)	
		->get();

		$data = array('data' => $loan);

		return $data;
	}


	public function store(){
		$data =new Loan;
		$data->employeeId = Input::get('employeeId');
		$data->loanDate = Input::get('date');
		$data->amount = Input::get('amount');
		$data->note = Input::get('note');
		$data->save();

		return Util::getStatusJSON("successful", "", "");
		// return "string ".Input::get('employeeId')."  ".Input::get('amount')."  ".Input::get('date')."  ".Input::get('note');
	}


	public function update($id){
		$data = Loan::find($id);
		$data->employeeId = Input::get('employeeId');
		$data->loanDate = Input::get('date');
		$data->amount = Input::get('amount');
		$data->note = Input::get('note');
		$data->save();

		return Util::getStatusJSON("successful", "update", "");
	}	


	public function destroy($id){
		$data = Loan::find($id);
		$data->isDelete = TRUE;
		$data -> save();

		return Util::getStatusJSON("successful", "", "");
	}	
	

	public function sum($employeeId){
		$total = Loan::where('isDelete','=', 0)
		->where('employeeId', $employeeId)
		->whereMonth('loanDate', '=', date('m'))
		->whereYear('loanDate', '=', date('Y'))		
		->sum('amount');

		return Util::getStatusJSON("successful", $total, "");
	}	
}