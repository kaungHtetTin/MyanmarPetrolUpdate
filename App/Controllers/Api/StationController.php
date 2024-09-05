<?php

namespace App\Controllers\Api;

use Core\Controller;
use Core\Util;
use Core\Request;

use App\Models\Station;
use App\Models\Phone;
use App\Models\Information;
use App\Models\Company;
use App\Models\OilType;
use App\Models\Township;

class StationController extends Controller {
    
    public function index()
    {
        $Station =new Station;
        $Information = new Information;
        $Phone = new Phone();
        $Company = new Company;
        $OilType = new OilType;
        $Township = new Township;

        $Util = new Util();

        $stations = $Station->select([
            'stations.*',
            'companies.company',
            'companies.logo',
            'townships.township'
        ])
        ->join('companies','companies.id=company_id')
        ->join('townships','townships.id=township_id')
        ->get();


        $informations = $Information->select([
            'informations.*',
            'oil_types.type'
        ])
        ->join('oil_types','oil_types.id=oil_type_id')
        ->orderBy(['station_id'=>'ASC'])
        ->get();
        
        $phones= $Phone->orderBy(['station_id'=>'ASC'])->get();

        foreach($stations as $key=>$station){
            $id = $station['id'];
            $stations[$key]['phones']=$Util->binarySearchForMultipleObjectInSortedOrder($phones,$id,'station_id');
            $stations[$key]['informations']=$Util->binarySearchForMultipleObjectInSortedOrder($informations,$id,'station_id');
        }
        
        $companies = $Company->all();
        $oil_types = $OilType->all();
        $townhips = $Township->all();

        $response['stations']=$stations;
        $response['companies'] = $companies;
        $response['oil_types']=$oil_types;
        $response['townships']=$townhips;

        $this->response($response);
    }   

    public function store()
    {
        $req = new Request();
        $validate = $req->validate([
            'company_id'=>'required|numeric',
            'township_id'=>'required|numeric',
            'address'=>'required',
            'remark'=>'required',
        ]);
        if(!$validate) return $this->error($req->errors());

        $Station = new Station();
        $result = $Station->create($_POST);
        if($result) return $this->success();
        else return $this->error("Unexpected Error");

    }

    public function show($id)
    {
        $Station =new Station;
        $station = $Station->find($id);
        $station['company'] = $Station->company($station);

        return $this->formatPrint($station);
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
