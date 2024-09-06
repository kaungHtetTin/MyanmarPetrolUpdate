<?php

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;
use App\Models\Township;

class TownshipController extends Controller {
    
    public function index()
    {
        $Township = new Township;
        $towns = $Township->all();
        $response['townships'] = $towns;
        $this->response($response);
    }

    public function store()
    {

        $request = new Request();

        $validated = $request->validate([
            'township'=>'required'
        ]);

        if(!$validated){
            return $this->error($request->errors());
        }
        $town = $request->input('township');
        $Township = new Township;
        $township = ['township'=>$town];
        $result = $Township->create($township);
        if($result) return $this->success();
        else return $this->error("Unexpected Error");

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
