<?php namespace App\Http\Controllers;
use DB;
use PDF;
use Input;
use App\Http\Models\Parameter;
use App\Http\Models\Location;
use App\Http\Models\Payroll;
use App\Http\Models\Absence;
use App\Http\Models\Bonus;
use App\Http\Models\Loan;
use App\Http\Utils\Constant;
use Illuminate\Http\Request;


class PDFController extends Controller {
	public function __construct(){
		ini_set('max_execution_time', 0);
	}
	public function locationReportPDF(){
		
		$locations = Location::where('isDelete','=', 0)
		->get();		

	   // print_r($locations);

		$pdf = PDF::loadView('pdf.location',['locations'=>$locations]);
		return $pdf->download('location.pdf');
	}


	public function payrollReportPDF(){

		$id = Input::get('id');
		// $searchDate = Input::get('date');
		// $startDate = $searchDate.'-01';
		// $endDate = $searchDate.'-'.date('t', strtotime($startDate) );
		//

		$empId = Input::get('empId');
		$date = Input::get('date');
		$endDate = date("Y-m-t", strtotime($date));
		//
		$absences = Absence::where('employeeId', $empId)
		->where('isDelete','=', 0)
		->where('startDate', '>=', $date)
		->where('startDate', '<=', $endDate)	
		->get();

		$bonus = Bonus::where('employeeId', $empId)
		->where('isDelete','=', 0)
		->where('bonusDate', '>=', $date)
		->where('bonusDate', '<=', $endDate)	
		->get();

		$loans = Loan::where('employeeId', $empId)
		->where('isDelete','=', 0)
		->where('loanDate', '>=', $date)
		->where('loanDate', '<=', $endDate)	
		->get();

		$payroll = DB::table('vpayroll')->where('id',$id)->first();

		$pdf = PDF::loadView('pdf.payroll',['payroll'=>$payroll],['absences'=>$absences,'bonus'=>$bonus,'loans'=>$loans]);
		return $pdf->download('payrolls.pdf');		
	}


	public function sellTicketReportPDF(){
		$startDate = Input::get('start');
		$endtDate = Input::get('end');

		$sale = DB::table('vreportsale')
		->where('saleDate','>=',$startDate)
		->where('saleDate','<=',$endtDate)	
		->where('time','!=','')
		->get();	

		$pdf = PDF::loadView('pdf.ticket',['data'=>$sale],['start'=>$startDate,'end'=>$endtDate]);
		return $pdf->download('Sell Ticket Report.pdf');
	}

	public function deliveryReportPDF(){
		
		$startDate = Input::get('start');
		$endtDate = Input::get('end');

		$delivery = DB::table('vreportsale')
		->where('saleDate','>=',$startDate)
		->where('saleDate','<=',$endtDate)	
		->where('time','!=','')
		->where('deliveryAmount','>','0')
		->get();		

		$pdf = PDF::loadView('pdf.delivery',['data'=>$delivery],['start'=>$startDate,'end'=>$endtDate]);
		return $pdf->download('Delivery Report.pdf');
	}

	public function agencySaleReportPDF(){

		// set_time_limit(0);

		$startDate = Input::get('start');
		$endtDate = Input::get('end');
		$agencyId = Input::get('agencyId');
		$statusId = Input::get('status');
		$status = "";
		if($agencyId > 0){
			$sale = DB::table('vreportsaleagency')
			->where('saleDate','>=',$startDate)
			->where('saleDate','<=',$endtDate)	
			->where('supplierId','=',$agencyId)	
			->where('statusId','=',$statusId)	
			->get();

			$p = Parameter::where('id',$statusId)->first();
			$status = "Status: ".$p->parameter;
		}else{
			$sale = DB::table('vreportsaleagency')
			->where('saleDate','>=',$startDate)
			->where('saleDate','<=',$endtDate)	
			->get();
		}


		$pdf = PDF::loadView('pdf.agency',['data'=>$sale],['start'=>$startDate,'end'=>$endtDate,'status'=>$status]);
		return $pdf->download('Agency Sale Report.pdf');
	}

	public function sellOtherReportPDF(){
		
		$startDate = Input::get('start');
		$endtDate = Input::get('end');

		$sale = DB::table('vreportsale')
		->where('saleDate','>=',$startDate)
		->where('saleDate','<=',$endtDate)	
		->where('time',NULL)
		->get();	

		$pdf = PDF::loadView('pdf.other',['data'=>$sale],['start'=>$startDate,'end'=>$endtDate]);
		return $pdf->download('Agency Sale Report.pdf');
	}
}