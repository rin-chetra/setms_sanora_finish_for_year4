<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });






/* 
|--------------------------------------------------------------------------
| Dashboar Routes
|--------------------------------------------------------------------------
*/
Route::get('/', array('as'=>'dashboard' , 'uses'=>'DashboardController@index' ));
Route::get('/dashboard', array('as'=>'dashboard' , 'uses'=>'DashboardController@index' ));
Route::get('income-ajax', 'DashboardController@searchIncomeAjax' );
/*End dashboard */





/* 
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', array('as'=>'login' , 'uses'=>'LoginController@index' ));
Route::post('/login', array('as'=>'login.login' , 'uses'=>'LoginController@login' ));
/*End login */



/* 
|--------------------------------------------------------------------------
| Logout Routes
|--------------------------------------------------------------------------
*/
Route::get('/logout', array('as'=>'logout' , 'uses'=>'LogoutController@index' ));
/*End logout */





/* 
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::get('/user', array('as'=>'user' , 'uses'=>'UserController@index' ));
Route::get('/user/{id}', array('as'=>'user.show' , 'uses'=>'UserController@show' ));
Route::get('/user/delete/{id}', array('as'=>'user.destroy' , 'uses'=>'UserController@destroy' ));

Route::post('/user', array('as'=>'user.store' , 'uses'=>'UserController@store' ));
Route::post('/user/{id}', array('as'=>'user.update' , 'uses'=>'UserController@update' ));

/*End user */




/* 
|--------------------------------------------------------------------------
| Location Routes
|--------------------------------------------------------------------------
*/
Route::get('/location', array('as'=>'location' , 'uses'=>'LocationController@index' ));
Route::get('/location/{id}', array('as'=>'location.show' , 'uses'=>'LocationController@show' ));
Route::get('/location/delete/{id}', array('as'=>'location.destroy' , 'uses'=>'LocationController@destroy' ));

Route::post('/location', array('as'=>'location.store' , 'uses'=>'LocationController@store' ));
Route::post('/location/{id}', array('as'=>'location.update' , 'uses'=>'LocationController@update' ));

Route::post('saveAjax', 'LocationController@saveAjax' );

/*End location */




/* 
|--------------------------------------------------------------------------
| Location Routes
|--------------------------------------------------------------------------
*/
Route::get('/parameter', array('as'=>'parameter' , 'uses'=>'ParameterController@index' ));
Route::get('/parameter/delete/{id}', array('as'=>'parameter.destroy' , 'uses'=>'ParameterController@destroy' ));

Route::post('/parameter', array('as'=>'parameter.store' , 'uses'=>'ParameterController@store' ));
Route::post('/parameter/{id}', array('as'=>'parameter.update' , 'uses'=>'ParameterController@update' ));

/*End location */



/* 
|--------------------------------------------------------------------------
| Cateogry Routes
|--------------------------------------------------------------------------
*/
Route::get('/category', array('as'=>'category' , 'uses'=>'CategoryController@index' ));
Route::get('/category/{id}', array('as'=>'category.show' , 'uses'=>'CategoryController@show' ));
Route::get('/category/delete/{id}', array('as'=>'category.destroy' , 'uses'=>'CategoryController@destroy' ));

Route::post('/category', array('as'=>'category.store' , 'uses'=>'CategoryController@store' ));
Route::post('/category/{id}', array('as'=>'category.update' , 'uses'=>'CategoryController@update' ));

/*End Cateogry */





/* 
|--------------------------------------------------------------------------
| Porducts Routes
|--------------------------------------------------------------------------
*/
Route::get('/product', array('as'=>'product' , 'uses'=>'ProductController@index'));
Route::get('/product/{id}', array('as'=>'product.show' , 'uses'=>'ProductController@show' ));
Route::get('/product/delete/{id}', array('as'=>'product.destroy' , 'uses'=>'ProductController@destroy' ));

Route::post('/product', array('as'=>'product.store' , 'uses'=>'ProductController@store' ));
Route::post('/product/{id}', array('as'=>'product.update' , 'uses'=>'ProductController@update' ));
/*End Porducts */






/* 
|--------------------------------------------------------------------------
| Purchase Routes
|--------------------------------------------------------------------------
*/
Route::get('/purchase', array('as'=>'purchase' , 'uses'=>'PurchaseController@index' ));
Route::get('/purchase/{id}', array('as'=>'purchase.show' , 'uses'=>'PurchaseController@show' ));
Route::get('/purchase/delete/{id}', array('as'=>'purchase.destroy' , 'uses'=>'PurchaseController@destroy' ));
Route::get('/purchase-analysis', array('as'=>'purchase-analysis' , 'uses'=>'PurchaseController@analysis' ));
Route::get('/expense-report', array('as'=>'expense-report' , 'uses'=>'PurchaseController@report' ));

Route::post('/purchase', array('as'=>'purchase.store' , 'uses'=>'PurchaseController@store' ));
Route::post('/purchase/{id}', array('as'=>'purchase.update' , 'uses'=>'PurchaseController@update' ));
/*End Purchase */







/* 
|--------------------------------------------------------------------------
| Ofiice Sale Routes
|--------------------------------------------------------------------------
*/
Route::get('/office-sale', array('as'=>'office-sale' , 'uses'=>'SaleController@index' ));
Route::get('/income-report', array('as'=>'income-report' , 'uses'=>'ReportController@income' ));
Route::get('sale-ticket-ajax', 'ReportController@saleTicketAjax' );
Route::get('delivery-ajax', 'ReportController@deliveryAjax' );
Route::get('sale-other-ajax', 'ReportController@saleOtherAjax' );
Route::get('sale-agency-ajax', 'ReportController@saleAgencyAjax' );

/*End Ofiice Sale */

Route::get('/expense-report', array('as'=>'expense-report' , 'uses'=>'ReportController@expense' ));
Route::get('purchase-ajax', 'ReportController@purchaseAjax' );
Route::get('purchase-detail-ajax', 'ReportController@purchaseDetailAjax' );
Route::get('purchase-supplier-ajax', 'ReportController@purchaseSupplierAjax' );


Route::get('/payroll-report', array('as'=>'payroll-report' , 'uses'=>'ReportController@payroll' ));
Route::get('payroll-ajax', 'ReportController@payrollAjax' );
/* 
|--------------------------------------------------------------------------
| Webservice Routes
|--------------------------------------------------------------------------
*/

Route::get('/wb-search', 'WebserviceController@search' );
/*End Webservice */

/* 
|--------------------------------------------------------------------------
| Agency Sale Routes
|--------------------------------------------------------------------------
*/
Route::get('/agency-sale', array('as'=>'agency-sale' , 'uses'=>'AgencyController@index' ));
Route::get('/agency-sale/{id}', array('as'=>'agency-sale.show' , 'uses'=>'AgencyController@show' ));

Route::post('/agency-sale', array('as'=>'agency-sale.store' , 'uses'=>'AgencyController@store' ));
Route::post('/agency-sale/{id}', array('as'=>'agency-sale.update' , 'uses'=>'AgencyController@update' ));
Route::get('/agency-sale/delete/{id}', array('as'=>'agency-sale.destroy' , 'uses'=>'AgencyController@destroy' ));

Route::get('searchAjax', 'AgencyController@searchAjax' );
Route::get('paymentAjax', 'AgencyController@paymentAjax' );
/*End Agency Sale */


/* 
|--------------------------------------------------------------------------
| Miscellaneous Sale Routes
|--------------------------------------------------------------------------
*/
Route::get('/miscellaneous-sale', array('as'=>'miscellaneous-sale' , 'uses'=>'MiscellaneousController@index' ));

Route::get('miscellaneous-ajax', 'MiscellaneousController@searchAjax' );
Route::get('/miscellaneous-sale/{id}', array('as'=>'miscellaneous-sale.show' , 'uses'=>'MiscellaneousController@show' ));

Route::post('/miscellaneous-sale', array('as'=>'miscellaneous-sale.store' , 'uses'=>'MiscellaneousController@store' ));
Route::post('/miscellaneous-sale/{id}', array('as'=>'miscellaneous-sale.update' , 'uses'=>'MiscellaneousController@update' ));
Route::get('/miscellaneous-sale/delete/{id}', array('as'=>'miscellaneous-sale.destroy' , 'uses'=>'MiscellaneousController@destroy' ));

/* 
|--------------------------------------------------------------------------
| Miscellaneous Sale Routes
|--------------------------------------------------------------------------
*/
Route::get('/office-sale', array('as'=>'office-sale' , 'uses'=>'OfficesaleController@index' ));
Route::get('/office-sale/{id}', array('as'=>'office-sale.show' , 'uses'=>'OfficesaleController@show' ));
Route::get('/office-sale/delete/{id}', array('as'=>'office-sale.destroy' , 'uses'=>'OfficesaleController@destroy' ));

Route::post('/office-sale', array('as'=>'office-sale.store' , 'uses'=>'OfficesaleController@store' ));
Route::post('/office-sale/{id}', array('as'=>'office-sale.update' , 'uses'=>'OfficesaleController@update' ));

Route::get('office-sale-ajax', 'OfficesaleController@searchAjax' );

/*End Ofiice Sale */



/* 
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
*/
Route::get('/employee', array('as'=>'employee' , 'uses'=>'EmployeeController@index' ));
Route::get('/employee/{id}', array('as'=>'employee.show' , 'uses'=>'EmployeeController@show' ));
Route::get('/employee/delete/{id}', array('as'=>'employee.destroy' , 'uses'=>'EmployeeController@destroy' ));

Route::post('/employee', array('as'=>'employee.store' , 'uses'=>'EmployeeController@store' ));
Route::post('/employee/{id}', array('as'=>'employee.update' , 'uses'=>'EmployeeController@update' ));
Route::get('employee-ajax', 'EmployeeController@employeeAjax' );


// Route::post('submit', function(){
// 	echo "<pre>";
// 	print_r(Input::file('image'));
// 	echo "</pre>";

// 	foreach (Input::file('image') as $iamge) {
// 		# code...
// 		$imageName = time().$image->getClientOriginalName();
// 		$image->move('public/img', $imageName);

// 		if($uploadFlag){
// 			$uploadediamge[] = $imageName;
// 		}
// 	}
	
// 	return Response::json(['success'=>true, 'message'=>'image uploaded', 'images'=> $uploadediamge]);
// });
/*End Employee */




/* 
|--------------------------------------------------------------------------
| Absence Routes
|--------------------------------------------------------------------------
*/
// Route::get('/absence', array('as'=>'absence' , 'uses'=>'AbsenceController@index' ));
// Route::get('/absence', array('as'=>'absence' , 'uses'=>'AbsenceController@index' ));
// Route::get('/absence', array('as'=>'absence' , 'uses'=>'AbsenceController@index' ));
// Route::get('/absence', array('as'=>'absence' , 'uses'=>'AbsenceController@index' ));

Route::get('/absence', array('as'=>'absence' , 'uses'=>'AbsenceController@index' ));
Route::get('absence/sum/{employeeId}', 'AbsenceController@sum' );
Route::get('absence/{id}', 'AbsenceController@show' );
Route::get('absence-save', 'AbsenceController@store' );
Route::get('absence/update/{id}', 'AbsenceController@update' );
Route::get('absence/delete/{id}', 'AbsenceController@destroy' );
Route::get('absence-by-empid-ajax', 'AbsenceController@searchAjax' );
/*End Absence */


/* 
|--------------------------------------------------------------------------
| Loan Routes
|--------------------------------------------------------------------------
*/


/*End Loan */


Route::get('loan', 'LoanController@index' );
Route::get('loan/sum/{employeeId}', 'LoanController@sum' );
Route::get('loan/{id}', 'LoanController@show' );
Route::get('loan-save', 'LoanController@store' );
Route::get('loan-by-empid-ajax', 'LoanController@searchAjax' );
// Route::get('loan/update/{id}', 'LoanController@update' );
// Route::get('loan/delete/{id}', 'LoanController@destroy' );
/* 
|--------------------------------------------------------------------------
| Loan Routes
|--------------------------------------------------------------------------
*/
Route::get('bonus', array('as'=>'bonus' , 'uses'=>'BonusController@index' ));
Route::get('bonus/sum/{employeeId}', 'BonusController@sum' );
Route::get('bonus/{id}', array('as'=>'bonus.show' , 'uses'=>'BonusController@show' ));
Route::get('bonus-save', array('as'=>'bonus.store' , 'uses'=>'BonusController@store' ));
Route::get('bonus/update/{id}', array('as'=>'bonus.update' , 'uses'=>'BonusController@update' ));
Route::get('bonus/delete/{id}', array('as'=>'bonus.destroy' , 'uses'=>'BonusController@destroy' ));
Route::get('bonus-by-empid-ajax', 'BonusController@searchAjax' );
/*End Loan */




/* 
|--------------------------------------------------------------------------
| Loan Routes
|--------------------------------------------------------------------------
*/
Route::get('/payroll', array('as'=>'payroll' , 'uses'=>'PayrollController@index' ));
Route::get('payroll/{id}', 'PayrollController@show' );
Route::get('generate-payroll-ajax', 'PayrollController@generatePayrollAjax' );
Route::get('search-payroll-ajax', 'PayrollController@searchPayrollAjax' );
/*End Loan */




/* 
|--------------------------------------------------------------------------
| Loan Routes
|--------------------------------------------------------------------------
*/
Route::get('/supplier', array('as'=>'supplier' , 'uses'=>'SupplierController@index' ));
Route::get('/supplier/{id}', array('as'=>'supplier.show' , 'uses'=>'SupplierController@show' ));
Route::get('/supplier/delete/{id}', array('as'=>'supplier.destroy' , 'uses'=>'SupplierController@destroy' ));

Route::post('/supplier', array('as'=>'supplier.store' , 'uses'=>'SupplierController@store' ));
Route::post('/supplier/{id}', array('as'=>'supplier.update' , 'uses'=>'SupplierController@update' ));
/*End Loan */



/* 
|--------------------------------------------------------------------------
| PDF Routes
|--------------------------------------------------------------------------
*/
Route::get('/pdf', 'PDFController@locationReportPDF');
Route::get('/payroll-pdf', 'PDFController@payrollReportPDF');
Route::get('/sell-ticket-pdf', 'PDFController@sellTicketReportPDF');
Route::get('/delivery-pdf', 'PDFController@deliveryReportPDF');
Route::get('/agency-sale-pdf', 'PDFController@agencySaleReportPDF');
Route::get('/other-sale-pdf', 'PDFController@sellOtherReportPDF');

/*End PDF */






/* 
|--------------------------------------------------------------------------
| Excel Routes
|--------------------------------------------------------------------------
*/
Route::get('/excel', 'ExcelController@locationReportExcel');
/*End Excel */






/* 
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
*/
Route::get('/website', array('as'=>'website' , 'uses'=>'websiteController@index' ));

/*End website */





