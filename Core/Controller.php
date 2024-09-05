<?php
// File: /core/Controller.php

namespace Core;

class Controller {
    // Render a view
    public function render($view, $data = []) {
        extract($data);
        require_once "../app/views/{$view}.php";
    }

    public function response($data){
        echo json_encode($data);
    }

    public function success(){
        $response['status']="success";
        echo json_encode($response);
    }

    public function error($message){
        $response['status']="fail";
        $response['error']=$message;
        echo json_encode($response);
    }

    public function formatPrint($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}
