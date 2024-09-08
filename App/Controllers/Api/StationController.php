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
            'phone'=>'required',
        ]);

        
        if(!$validate) return $this->error($req->errors());

        $Station = new Station();
        $data = [
            'company_id'=>$req->input('company_id'),
            'township_id'=>$req->input('township_id'),
            'address'=>$req->input('address'),
            'remark'=>$req->input('remark')
        ];
        $station = $Station->create($data);
        $station_id = $station['id'];

        $Phone = new Phone();
        $Phone->create(['station_id'=>$station_id,'phone'=>$req->input('phone')]);

        $OilType = new OilType;
        $oil_types = $OilType->all();

        $Information = new Information;

        foreach($oil_types as $type){
            $oil_type_id = $type['id'];
            $Information->create(['station_id'=>$station_id,'oil_type_id'=>$oil_type_id]);
        }

        return $this->success();

    }

    public function show($id)
    {
        $Station =new Station;
        $station = $Station->find($id);
        
        $station['company'] = $Station->company($station);

        $Information = new Information;
        $information = $Information->select([
            'informations.*',
            'oil_types.type'
        ])
        ->join('oil_types','oil_types.id=oil_type_id')
        ->where(['station_id'=>$id])
        ->get();

        $station['information'] = $information;
        $station['phones'] = $Station->phones($id);
        $station['township'] = $Station->township($station);

        $visit = $station['visit'];
        $visit++;
        $Station->update(['id'=>$id],['visit'=>$visit]);

        return $this->response($station);
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
