<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Models\Author;

class AuthorController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $authors = Author::paginate(10);
        return $this->successResponse($authors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //The rules
        $rules = [
            'name' => 'required|max:255',
            'gender' => 'required|in:male,female',
            'country' => 'required|max:255',
        ];

        //validate the request
       $this->validate($request, $rules);

        // $author = Author::create($request->all());
        // return $this->successResponse($author, Response::HTTP_CREATED);

        //instantiate the Author
        $author = new Author();
        $author->name    = $request->input('name');
        $author->gender  = $request->input('gender');
        $author->country = $request->input('country');
        //Save the author
        $author->save();
        //Return the new author
        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($author) {
        $author = Author::findOrFail($author);
        return $this->successResponse($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $author) {
        //The rules
        $rules = [
            'name' => 'max:255',
            'gender' => 'in:male,female',
            'country' => 'max:255',
        ];

        //validate the request
        $this->validate($request, $rules);

        //find the author using its id
        $author = Author::findOrFail($author);
        //Check if the request has name
        if ($request->has('name')) {
            $author->name    = $request->input('name');
        }
        //Check if the request has gender
        if ($request->has('gender')) {
            $author->gender    = $request->input('gender');
        }
        //Check if the request has name
        if ($request->has('country')) {
            $author->country    = $request->input('country');
        }
        //Check if anything changed in author
        if ($author->isClean()) {
            return $this->errorResponse('You must specify a new value to update', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //Save the author
        $author->save();
        //Return the new author
        return $this->successResponse($author, Response::HTTP_CREATED);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($author) {
        //find the author using its id
        $author = Author::findOrFail($author);
        $author->delete();
        //Return the new author
        return $this->successResponse($author);
    }

}
