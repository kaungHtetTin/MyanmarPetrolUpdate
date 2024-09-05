<?php

namespace App\Controllers\Api;
use Core\Controller;

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
        $Station = new Station();
        $station = $Station->find($information['station_id']);

        $response['station']= $station;
        $response['information']=$information;
        $this->response($response);

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
