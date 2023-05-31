<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Shipments;
use App\Models\ShipmentTracker;
use App\Models\ShipmentItems;
use Illuminate\Http\Request;

class ShipmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $columns = ['id','tracking_number', 'origin', 'destination'];
        $shipments = Shipments::select($columns)->get();

        return response()->json($shipments, 200); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'shipment_details.origin' => 'required', 
            'shipment_details.destination' => 'required',      
            'shipment_details.vessel' => 'required',
            'shipment_details.type' => 'required',
            'shipment_details.exporter' => 'required',
            'shipment_details.importer' => 'required'  
        ]);

        $shipment = new Shipments();

        $shipment->origin = $request->input('shipment_details.origin');
        $shipment->destination = $request->input('shipment_details.destination');
        $shipment->vessel = $request->input('shipment_details.vessel');
        $shipment->tracking_number = Str::upper(Str::random(10));;
        $shipment->type = $request->input('shipment_details.type');
        $shipment->exporter = $request->input('shipment_details.exporter');
        $shipment->importer = $request->input('shipment_details.importer');
        $shipment->status = "PENDING";

        try{
            $shipment->save();

            $this->updateTracker($shipment);       
            
            return response()->json($shipment, 200); 
        } catch(\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json($e->getMessage(), 200); 
        }
    }

    public function updateTracker($shipment) {
        $tracker = new ShipmentTracker();
        $tracker->status = "PENDING"; 
        $tracker->custodian = $shipment->exporter;
        $tracker->level = 0;
        

        $tracker->shipment()->associate($shipment);
        $tracker->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($trackingNumber)
    {
        $shipment = Shipments::where('tracking_number', $trackingNumber)->first();

        return response()->json($shipment, 200); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipments $shipments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'origin' => 'required', 
            'destination' => 'required',      
            'vessel' => 'required',
            'tracking_number' => 'required|unique:shipments,tracking_number,'.$request->id,
            'type' => 'required',
            'exporter' => 'required',
            'importer' => 'required'  
        ]);

        $shipment = Shipments::findOrFail($request->id);

        $shipment->origin = $request->origin;
        $shipment->destination = $request->destination;
        $shipment->vessel = $request->vessel;	
        $shipment->tracking_number = $request->tracking_number;
        $shipment->type = $request->type;
        $shipment->exporter = $request->exporter;
        $shipment->importer = $request->importer;
        $shipment->status = $request->status;

        try{
            $shipment->save();
            return response()->json($shipment, 200); 
        } catch(\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json($e->getMessage(), 200); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipments $shipments)
    {
        //
    }
}
