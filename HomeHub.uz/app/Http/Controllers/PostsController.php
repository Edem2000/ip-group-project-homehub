<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

class PostsController extends Controller
{


   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        //File upload
        if($request->hasFile('cover_image')){

            $filenameEXT = $request->file('cover_image')->getClientOriginalName();
            //filename
            $filename = pathinfo($filenameEXT, PATHINFO_FILENAME);
            //extension
            $extesnion = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . $extesnion;
            //store file
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        }else{
            $fileNameToStore ='noimage.jpg';
        }

        $post = new Post;
        $post -> title = $request->input('title');
        $post -> body = $request->input('body');
        $post -> user_id = '1'; // change when auth implemented
        $post -> cover_image = $fileNameToStore;
        $post -> save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

       

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
            
        ]);


         //File upload
         if($request->hasFile('cover_image')){

            $filenameEXT = $request->file('cover_image')->getClientOriginalName();
            //filename
            $filename = pathinfo($filenameEXT, PATHINFO_FILENAME);
            //extension
            $extesnion = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . $extesnion;
            //store file
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        }

        //File upload
   
        $post = Post::find($id);
        $post -> title = $request->input('title');
        $post -> body = $request->input('body');
        if($request->hasFile('cover_image')){
            $post-> cover_image = $fileNameToStore;
        }
        $post -> save();

        return redirect('/')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
       

        if($post->cover_image != 'noimage.jpg'){
            Storage::delete('public/cover_images/'.$post->cover_image);
        }

        $post -> delete(); 
        return redirect('/')->with('success', 'Post Removed');
    }
}
