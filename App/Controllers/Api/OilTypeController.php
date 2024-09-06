<?php

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;

use App\Models\OilType;
use App\Models\Station;
use App\Models\Information;

class OilTypeController extends Controller {
    
    public function index()
    {
        $OilType = new OilType();
        $oil_types = $OilType->all();
        $response['oil_types'] = $oil_types;
        return $this->response($response);
    }

    public function store()
    {
        $request = new Request();

        $validated = $request->validate([
            'type'=>'required'
        ]);

        if(!$validated){
            return $this->error($request->errors());
        }

        $oil_type = $request->input('type');
        $OilType = new OilType();
        $oilType = $OilType->create(['type'=>$oil_type]);
        $oil_type_id = $oilType['id'];

        $Information = new Information();

        $Station = new Station();
        $stations = $Station->all();
        foreach($stations as $station){
            $station_id = $station['id'];
            $Information->create(['station_id'=>$station_id,'oil_type_id'=>$oil_type_id]);
        }

        return $this->success();
    }

    public function show($id)
    {
        
    }

    public function update($id)
    {
        
    }

    public function destroy($id)
    {
       
    }

    // Optional methods
    public function edit($id)
    {
        // Handle edit if needed
    }

    public function create()
    {
        // Handle create if needed
    }

    public function showDeleted()
    {
       
    }
}
