<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentItems extends Model
{
    use HasFactory;

    protected $fillable = [
		'item_name',
		'quantity',
		'manufacturer',
		'price'
	];


    public function shipment()
	{
        return $this->belongsTo(Shipments::class);
	}
}
