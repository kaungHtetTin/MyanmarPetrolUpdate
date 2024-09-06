<?php

namespace App\Controllers\Api;
use Core\Controller;
use Core\Request;

use App\Models\Information;
use App\Models\Station;

class InformationController extends Controller {
    
    public function index()
    {
        $Information = new Information();
        $informations = $Information->all();
        $this->response($informations);
    }

    public function store()
    {
        
    }

    public function show($id)
    {
        $Information = new Information();
        $information = $Information->find($id);
        $information['oil_type']= $Information->oilType($information);

        $Station = new Station();
        $station = $Station->find($information['station_id']);
        $station['company']=$Station->company($station);
        $station['township']=$Station->township($station);

        $response['station']= $station;
        $response['information']=$information;
        $this->response($response);

    }

    public function update($id)
    {
        $request = new Request();

        $validated = $request->validate([
            'price'=>'required',
            'available'=>'required'
        ]);

        if(!$validated){
            return $this->error($request->errors());
        }

        $price = $request->input('price');
        $available = $request->input('available');

        $Information = new Information;
        $result = $Information->update(['id'=>$id],['price'=>$price,'available'=>$available]);
        if($result) return $this->success();
        else return $this->error("Unexpected Error");
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
