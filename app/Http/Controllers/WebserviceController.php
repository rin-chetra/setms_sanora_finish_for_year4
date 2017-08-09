<?php namespace App\Http\Controllers;

use DB;
use Input;
use App\Http\Utils\Util;
use App\Http\Models\Parameter;
use App\Http\Models\Supplier;
use App\Http\Models\AgencySale;
use App\Http\Models\Category;
use App\Http\Utils\Permission;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;



class WebserviceController extends Controller {

	public function search(){
		$startDate = Input::get('start');
		$endtDate = Input::get('end');
		$agencyId = Input::get('agency');
		$statusId = Input::get('status');

		$agencysales = DB::table('agencysales AS a')
						->select('a.saleDate', 's.supplierName', 'a.amount', 'p.parameter')
						->join('suppliers AS s', 's.id', '=', 'a.agencyId')
						->join('parameters AS p', 'p.id', '=', 'a.statusId')
						->where('a.agencyId','=', $agencyId)
						->where('a.isDelete','=', 0)
						->get();	

		$post_data = array('data' => $agencysales);
		print (json_encode($post_data));	
	}
}