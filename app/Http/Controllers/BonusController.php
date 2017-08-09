<?php namespace App\Http\Controllers;

use DB;
use Input;
use Validator;
use App\Http\Models\Bonus;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use App\Http\Utils\Util;
use Illuminate\Http\Request;


class BonusController extends Controller {


	public function index(){
		$bonus = Bonus::where('isDelete','=', 0)
		->where('employeeId', Input::get('employeeId'))
		->whereMonth('bonusDate', '=', date('m'))
		->whereYear('bonusDate', '=', date('Y'))
		->get();	
		return $bonus;
	}


	public function show($id){
		$bonus = Bonus::where('id', $id)
		->where('isDelete','=', 0)
		->first();

		return $bonus;
	}


	public function store(){
		$data =new Bonus;
		$data->employeeId = Input::get('employeeId');
		$data->bonusDate = Input::get('date');
		$data->amount = Input::get('amount');
		$data->note = Input::get('note');
		$data->save();

		return Util::getStatusJSON("successful", "", "");
				// return "string ".Input::get('employeeId')."  ".Input::get('amount')."  ".Input::get('date')."  ".Input::get('note');
	}

	public function searchAjax(){
		$empId = Input::get('empId');
		$date = Input::get('date');
		$endDate = date("Y-m-t", strtotime($date));
		//
		$bonus = Bonus::where('employeeId', $empId)
		->where('isDelete','=', 0)
		->where('bonusDate', '>=', $date)
		->where('bonusDate', '<=', $endDate)	
		->get();

		$data = array('data' => $bonus);

		return $data;
	}


	public function update($id){
		$data = Bonus::find($id);
		$data->employeeId = Input::get('employeeId');
		$data->bonusDate = Input::get('date');
		$data->amount = Input::get('amount');
		$data->note = Input::get('note');
		$data->save();

		return Util::getStatusJSON("successful", "update", "");
	}	


	public function destroy($id){
		$data = Bonus::find($id);
		$data->isDelete = TRUE;
		$data -> save();

		return Util::getStatusJSON("successful", "", "");
	}


	public function sum($employeeId){
		$total = Bonus::where('isDelete','=', 0)
		->where('employeeId', $employeeId)
		->whereMonth('bonusDate', '=', date('m'))
		->whereYear('bonusDate', '=', date('Y'))		
		->sum('amount');

		return Util::getStatusJSON("successful", $total, "");
	}
	
}
