<?php

namespace App\Http\Controllers;

use App\Models\PolygonsModel;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{

    public function __construct()
    {
        $this->polygons =new PolygonsModel();
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
            'name' => 'required|unique:polygons,name',
            'description' => 'required',
            'geom_polygons' =>'required',
            'image' => 'image|mimes:png,jpg,jpeg,gif,tif,svg|max:10240',
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exist, You Must Change!!',
            'description.required' => 'Description is required!',
            'geom_polygons.required' => 'Geometry Polygons is required!',
            ]
        );

        //create image directory if not exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
         }

        // Get Image name file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygons." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
          } else {
            $name_image = null;
          }

        $data = [
            'geom' => $request->geom_polygons,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        //insert data
        if (!$this->polygons->create($data)){
            return redirect()->route('map')->with('error', 'Polygons failed to add');
        }

        //redirect to map
        return redirect()->route('map')->with('success', 'Polygons has been added');
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
