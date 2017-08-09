<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use App\Http\Models\Absence;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use App\Http\Utils\Util;
use Illuminate\Http\Request;


class AbsenceController extends Controller {


	public function index(){
		$loans = Absence::where('isDelete','=', 0)
		->where('employeeId', Input::get('employeeId'))
		->whereMonth('startDate', '=', date('m'))
		->whereYear('startDate', '=', date('Y'))	
		->get();	
		return $loans;
	}

	public function store(){
		$data =new Absence;
		$data->employeeId = Input::get('employeeId');
		$data->startDate = Input::get('startDate');
		$data->endDate = Input::get('endDate');
		$data->day = Input::get('day');
		$data->amount = Input::get('amount');
		$data->note = Input::get('note');
		$data->save();

		return Util::getStatusJSON("successful", "", "");
		// return "string ".Input::get('employeeId')."  ".Input::get('amount')."  ".Input::get('date')."  ".Input::get('note');
	}	


	public function update($id){
		$data = Absence::find($id);
		$data->employeeId = Input::get('employeeId');
		$data->startDate = Input::get('startDate');
		$data->endDate = Input::get('endDate');
		$data->day = Input::get('day');
		$data->amount = Input::get('amount');
		$data->note = Input::get('note');
		$data->save();

		return Util::getStatusJSON("successful", "update", "");
		// return "string ".Input::get('employeeId')."  ".Input::get('amount')."  ".Input::get('date')."  ".Input::get('note');
	}


	public function show($id){
		$absence = Absence::where('id', $id)
		->where('isDelete','=', 0)
		->first();

		return $absence;
	}
	
	public function searchAjax(){
		$empId = Input::get('empId');
		$date = Input::get('date');
		$endDate = date("Y-m-t", strtotime($date));
		//
		$absences = Absence::where('employeeId', $empId)
		->where('isDelete','=', 0)
		->where('startDate', '>=', $date)
		->where('startDate', '<=', $endDate)	
		->get();

		$data = array('data' => $absences);

		return $data;
	}


	public function destroy($id){
		$data = Absence::find($id);
		$data->isDelete = TRUE;
		$data -> save();

		return Util::getStatusJSON("successful", "", "");
	}


	public function sum($employeeId){
		$total = Absence::where('isDelete','=', 0)
		->where('employeeId', $employeeId)
		->whereMonth('bonusDate', '=', date('m'))
		->whereYear('bonusDate', '=', date('Y'))		
		->sum('amount');

		return Util::getStatusJSON("successful", $total, "");
	}
}
