<?php

namespace App\Http\Controllers;

use App\Models\ShipmentItems;
use App\Models\Shipments;
use Illuminate\Http\Request;

class ShipmentItemsController extends Controller
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
        $shipmentItems = $request->all();

        if(is_array($shipmentItems) && !empty($shipmentItems)) {
            $this->validate($request, [
                'items' => 'required|array',
                'items.*.item_name' => 'required|string',
                'items.*.quantity' => 'required|string',
                'items.*.manufacturer' => 'required|string',
                'items.*.price' => 'required|string',
            ]);

            $shipments = Shipments::find($shipmentItems['items'][0]['shipment_id']);

            foreach ($shipmentItems['items'] as $key => $item) {  
                $shipmentItem = new ShipmentItems();
    
                $shipmentItem->item_name = $item['item_name'];
                $shipmentItem->quantity = $item['quantity'];
                $shipmentItem->manufacturer = $item['manufacturer'];
                $shipmentItem->price = $item['price'];	
                $shipmentItem->shipment()->associate($shipments);
        
                try{
                    $shipmentItem->save();
                } catch(\Exception $e) {
                    \Log::error($e->getMessage());
        
                    return response()->json($e->getMessage(), 200); 
                }
            }

            return response()->json("Success", 200); 
        }
        else {
            return response()->json("Cannot submit empty items", 500    ); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipmentItems $shipmentItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentItems $shipmentItems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'item_name' => 'required', 
            'quantity' => 'required',      
            'manufacturer' => 'required',
            'price' => 'required'
        ]);

        $shipmentItems = ShipmentItems::findOrFail($request->id);

        $shipmentItems->item_name = $request->item_name;
        $shipmentItems->quantity = $request->quantity;
        $shipmentItems->manufacturer = $request->manufacturer;
        $shipmentItems->price = $request->price;	

        try{
            $shipmentItems->save();
            return response()->json($shipmentItems, 200); 
        } catch(\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json($e->getMessage(), 200); 
        }
    }

    public function shipmentItems($shipmentId) {
        $columns = ['id', 'item_name', 'quantity', 'manufacturer', 'price'];
        $shipmentItems = shipmentItems::where('shipment_id', $shipmentId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($shipmentItems, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentItems $shipmentItems)
    {
        //
    }
}
