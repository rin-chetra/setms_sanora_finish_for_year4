<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;


class Percentage extends Model{

	public function setSupplierId($value)
	{
		$this->attributes['supplierId'] = $value;
	}

	public function setSupplierName($value)
	{
		$this->attributes['supplierName'] = $value;
	}

	public function setTotal($value)
	{
		$this->attributes['total'] = $value;
	}

	public function setPercentage($value)
	{
		$this->attributes['percentage'] = $value;
	}
}