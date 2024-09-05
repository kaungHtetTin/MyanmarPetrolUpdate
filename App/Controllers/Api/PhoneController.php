<?php

namespace App\Controllers\Api;

use Core\Controller;
use App\Models\Phone;
use App\Models\Station;
use Database\QueryRunner;

class PhoneController extends Controller {
    
    public function index()
    {
        $Phone = new Phone;
        $phones = $Phone->get();
        $response['phones']=$phones;

        $Station =new Station;
        $stations = $Station->all();
        $response['stations']=$stations;
        
        $this->response($response);
    }

    public function store()
    {
        
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
