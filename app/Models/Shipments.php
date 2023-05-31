<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipments extends Model
{
    use HasFactory;

    protected $fillable = [
		'origin',
		'destination',
		'vessel',
		'tracking_number',
		'type',
		'exporter',
		'importer'
	];

    public function items()
	{
        return $this->hasMany(ShipmentItems::class);
	}
}
