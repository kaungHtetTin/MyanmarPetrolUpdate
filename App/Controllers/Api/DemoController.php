<?php

namespace App\Controllers\Api;

use Core\Request;
use Core\Controller;


class DemoController extends Controller {
    
    public function index()
    {
        
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
        
    }

    public function update($id)
    {
        $request = new Request();

        // $validated = $request->validate([
        //     'town'=>'required'
        // ]);

        // if(!$validated){
        //     return $this->error($request->errors());
        // }
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
