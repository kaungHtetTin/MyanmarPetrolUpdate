<?php

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;

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
        $req = new Request;
        $validated = $req->validate([
            'station_id'=>'required',
            'phone'=>'required'
        ]);

        if(!$validated){
            return $this->error($request->errors());
        }

        $station_id = $req->input('station_id');
        $phone = $req->input('phone');
        $Phone = new Phone;
        $phone = [
            'station_id'=>$station_id,
            'phone'=>$phone
        ];
        $result=$Phone->create($phone);
        if($result){
            $response['status']="success";
        }else{
            $response['status']="fail";
            $response['error']=$result;
        }
        $this->response($response);
    }

    public function show($id)
    {
        
    }

    public function update($id)
    {
        
    }

    public function destroy($id)
    {
        $Phone = new Phone;
        $result = $Phone->delete(['id'=>$id]);
        if($result) $this->success();
        else $this->error("Unexpected Error!");
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
