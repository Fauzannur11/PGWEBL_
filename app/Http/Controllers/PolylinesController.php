<?php

namespace App\Http\Controllers;

use App\Models\PolylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{

    public function __construct()
    {
        $this->polylines =new PolylinesModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'name' => 'required|unique:polylines,name',
            'description' => 'required',
            'geom_polyline' =>'required',
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exist, You Must Change!!',
            'description.required' => 'Description is required!',
            'geom_polyline.required' => 'Geometry Polylines is required!',
            ]
        );




        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
        ];

        //insert data
        if (!$this->polylines->create($data)){
            return redirect()->route('map')->with('error', 'Polyline failed to add');
        }

        //redirect to map
        return redirect()->route('map')->with('success', 'Polyline has been added');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
