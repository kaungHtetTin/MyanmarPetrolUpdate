<?php

namespace App\Controllers\Api;

use Core\Request;
use Core\Controller;

use App\Models\Post;


class PostController extends Controller {
    
    public function index()
    {
        $Post = new Post;
        $req = new Request;
        if($req->has('page')){
            $page = $req->input('page');
            $posts = $Post
                    ->paginate($page,30)
                    ->orderBy(['id'=>'DESC'])
                    ->get();
            $response['posts']=$posts;
        }else{
            $posts = $Post->all();
            $response['posts']=$posts;
        }

        return $this->response($response);
    }

    public function store()
    {
        $request = new Request();
        $validated = $request->validate([
            'body'=>'required'
        ]);
        if(!$validated){
            return $this->error($request->errors());
        }
        $body = $request->input("body");
        $timestamp = time()*1000;

        $image_url = "";

        if(isset($_FILES['myfile'])){
            $file=$_FILES['myfile']['name'];
            $file_loc=$_FILES['myfile']['tmp_name'];
            $folder="uploads/images/";
            if(move_uploaded_file($file_loc,$folder.$file)){
                $image_url = "uploads/images/".$file;
            }
        }

        $post = [
            'body'=>$body,
            'image_url'=>$image_url,
            'timestamp'=>$timestamp
        ];

        $Post = new Post;
        $result=$Post->create($post);
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
        $request = new Request();
        return $this->response(['method'=>'update']);

        // $validated = $request->validate([
        //     'town'=>'required'
        // ]);

        // if(!$validated){
        //     return $this->error($request->errors());
        // }
    }

    public function destroy($id)
    {
        $Post = new Post;
        $result = $Post->delete(['id'=>$id]);
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
