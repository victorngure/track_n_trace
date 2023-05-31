<?php

namespace App\Http\Controllers;

use App\Models\ShipmentTracker;
use App\Models\Shipments;
use Illuminate\Http\Request;

class ShipmentTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'status' => 'required', 
            'custodian' => 'required',
            'level' => 'required',
            'shipment_id' => 'required|exists:shipments,id'
        ]);

        $shipmentTracker = new ShipmentTracker();
        $shipments = Shipments::find($request->shipment_id);

        $shipmentTracker->status = $request->status;
        $shipmentTracker->custodian = $request->custodian;	
        $shipmentTracker->level = $request->level;
        $shipmentTracker->shipment()->associate($shipments);

        try{
            $shipmentTracker->save();
            $this->updateShipment($shipments, $shipmentTracker);

            return response()->json($shipmentTracker, 200); 
        } catch(\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json($e->getMessage(), 200); 
        }
    }

    public function updateShipment($shipments, $shipmentTracker) {
        $shipments->status = $shipmentTracker->status;
        $shipments->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($shipmentId)
    {
        $tracker = ShipmentTracker::where('shipment_id', $shipmentId)->orderBY('created_at', 'desc')->get();

        return response()->json($tracker, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentTracker $shipmentTracker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'status' => 'required', 
            'custodian' => 'required'
        ]);

        $shipmentTracker = ShipmentTracker::findOrFail($request->id);

        $shipmentTracker->location = $request->location;
        $shipmentTracker->custodian = $request->custodian;	

        try{
            $shipmentTracker->save();
            return response()->json($shipmentTracker, 200); 
        } catch(\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json($e->getMessage(), 200); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentTracker $shipmentTracker)
    {
        //
    }
}
