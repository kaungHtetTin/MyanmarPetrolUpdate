<?php

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;

use App\Models\Station;
use App\Models\Company;

class CompanyController extends Controller {
    
    public function index()
    {
        $Company = new Company;
        $companies = $Company->all();
        $response['companies'] = $companies;
        $this->response($response);
    }

    public function store()
    {
        $request = new Request();

        $validated = $request->validate([
            'company'=>'required',
        ]);
        if(!$validated){
            return $this->error($request->errors());
        }

        $company_name = $request->input('company');
        $logo = $request->input('logo');
        if($logo==null) $logo="";
        $company = [
            'company'=>$company_name,
            'logo'=>$logo
        ];
        $Company = new Company;
        $result = $Company->create($company);
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
