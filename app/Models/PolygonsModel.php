<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
    protected $table = 'polygons';

    protected $guarded = ['id'];

    public function geojson_polygons()
    {
        $polygons = $this->select(DB::raw('st_asgeojson(geom) as geom, name, description, image, st_area(geom, true) as area_M2, st_area(geom, true)/1000000 as area_km2, created_at, updated_at'))
        ->get();



        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
        foreach ($polygons as $p) {
            $feature =[
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ],
            ];

                    array_push($geojson['features'], $feature);
            }
            return $geojson;
}
}
