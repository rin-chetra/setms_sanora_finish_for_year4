<?php

namespace App\Http\Controllers;
use DB;
use Excel;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Http\Models\Location;
use App\Http\Utils\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;

 /* 
|--------------------------------------------------------------------------
| For more details, please go to this link:
  http://www.maatwebsite.nl/laravel-excel/docs/export 
|--------------------------------------------------------------------------
*/
class ExcelController extends Controller
{
    function locationReportExcel(){

    	$locations = Location::select('id AS ID', 'label AS Name', 'address AS Address')
    							->where('locations.isDelete','=', 0)
    							->get();

	    Excel::create('Export Data', function($excel) use($locations){

	    	$excel->sheet('Location Report', function($sheet) use($locations){

	    		/*
				--------------------------------------------------------------------------
	    		  Set Style 
				--------------------------------------------------------------------------
	    		*/
				// Set height for multiple rows
				$sheet->setHeight(array(
				    1     =>  15,
				));	

				// Set width for multiple cells
				$sheet->setWidth(array(
				    'A'     =>  10,
				    'B'     =>  50,
				    'C'     =>  100,
				));	    

				// Set black background
				$sheet->row(1, function($row) {
				    $row->setBackground('#808080');
				});			

				// Set border for range
				$sizeRow = count($locations)+1;
				$borderSize = 'A1:C'.$sizeRow;
				$sheet->setBorder($borderSize, 'thin');		
						
	    		/*
				--------------------------------------------------------------------------
	    		  End Style
				--------------------------------------------------------------------------
	    		*/



	    		/*
				--------------------------------------------------------------------------
	    		  Body formate: Display data like table format
				--------------------------------------------------------------------------
	    		*/				
	    		$sheet->fromArray($locations);
					

	    		/*
				--------------------------------------------------------------------------
	    		  Header format
				--------------------------------------------------------------------------
	    		*/					
				// Add before first row
				$sheet->prependRow(1, array(
				    ' ', ''
				));						
				$sheet->prependRow(1, array(
				    'Report: Location', '', 'Export By: '.Util::getProfile()->name,
				));
				$sheet->prependRow(1, array(
				    'Company: Saly Express Co.ltd ','','Export Date: '.Carbon::now(),
				));				
			
	    	});
	    })->export('xlsx');
    }
}	
