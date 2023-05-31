<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracker extends Model
{
    use HasFactory;

    protected $fillable = [
		'status',
		'level',
		'custodian'
	];


    public function shipment()
	{
        return $this->belongsTo(Shipments::class);
	}
}
