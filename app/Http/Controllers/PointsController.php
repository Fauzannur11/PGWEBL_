<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function __construct()
    {
        $this->points =new PointsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'PETAKU',
        ];

        return view('map', $data);
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
            'name' => 'required|unique:points,name',
            'description' => 'required',
            'geom_point' =>'required',
            'image' => 'image|mimes:png,jpg,jpeg,gif,tif,svg|max:10240'
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exist, You Must Change!!',
            'description.required' => 'Description is required!',
            'geom_point.required' => 'Geometry Point is required!',
            ]
        );

        //create image directory if not exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
         }

        // Get Image name file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
          } else {
            $name_image = null;
          }


        $data = [
            'geom' => $request->geom_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        //insert data
        if (!$this->points->create($data)){
            return redirect()->route('map')->with('error', 'Point failed to add');
        }

        //redirect to map
        return redirect()->route('map')->with('success', 'Point has been added');
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
        $data = [
            'title' => 'Edit Point',
            'id' => $id,
        ];

        return view('edit-point', $data);
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
        $imagefile = $this->points->find($id)->image;
        if (!$this->points->destroy($id)){
            return redirect()->route('map')->with('error', 'Point failed to delete');

        }
        //delete image
        if ($imagefile!=null){
            if (file_exists('.storage/images/' . $imagefile)){
                unlink('.storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Point has been deleted');
    }
}
