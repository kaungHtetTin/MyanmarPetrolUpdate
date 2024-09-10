<?php

namespace App\Controllers\Api;

use Core\Request;
use Core\Controller;

use App\Models\Setting;

class SettingController extends Controller {
    
    public function index()
    {
        $Setting = new Setting;
        $settings = $Setting->all();
        return $this->response($settings);
    }

    public function store()
    {
        $request = new Request();

        // $validated = $request->validate([
        //     'town'=>'required'
        // ]);

        // if(!$validated){
        //     return $this->error($request->errors());
        // }
    }

    public function show($id)
    {
        if(!is_numeric($id)){
            return $this->error("Invalid parameter");
        }
    }

    public function update($id)
    {
        $request = new Request();
        $validated = $request->validate([
            'setting'=>'required|numeric'
        ]);
        if(!$validated){
            return $this->error($request->errors());
        }

        $setting = $request->input('setting');

        $Setting = new Setting;
        $result = $Setting->update(['id'=>$id],['setting'=>$setting]);
        if($result) return $this->success();
        else return $this->error("Unexpected Error");
    }

    public function destroy($id)
    {
        if(!is_numeric($id)){
            return $this->error("Invalid parameter");
        }
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
