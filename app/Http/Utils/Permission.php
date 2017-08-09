<?php namespace App\Http\Utils;

use DB;
use Request;
use Cookie;
use App\Http\Utils\Util;

class Permission {

	public static function checkDashboardPermission(){
		
		$profile = Util::getProfile();
		if($profile === null) {
			return FALSE;	
		}else{
			return $profile;
		}	
		
	}


	public static function checkSettingPermission(){
		$profile = Util::getProfile();
		if($profile === null || $profile->isSetting === 0) {
			return FALSE;
		}else{
			return $profile;
		}		
	}

	public static function checkOtherPermission(){
		$profile = Util::getProfile();
		if($profile === null || $profile->isOther === 0) {
			return FALSE;
		}else{
			return $profile;
		}		
	}	

	public static function checkIncomePermission(){
		$profile = Util::getProfile();
		if($profile === null || $profile->isOther === 0) {
			return FALSE;
		}else{
			return $profile;
		}		
	}	

	public static function checkExpensePermission(){
		$profile = Util::getProfile();
		if($profile === null || $profile->isExpense === 0) {
			return FALSE;
		}else{
			return $profile;
		}		
	}


	public static function checkHumanResourcePermission(){
		$profile = Util::getProfile();
		if($profile === null || $profile->isHumanResource === 0) {
			return FALSE;
		}else{
			return $profile;
		}		
	}	

	public static function checkReportPermission(){
		$profile = Util::getProfile();
		if($profile === null || $profile->isReport === 0) {
			return FALSE;
		}else{
			return $profile;
		}		
	}	
}