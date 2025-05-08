<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class PolylinesModel extends Model
{
    protected $table = 'polylines';

    protected $guarded = ['id'];

    public function geojson_polylines()
    {
        $polylines = $this->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image, st_length(geom, true) as length_M, st_length(geom, true)/1000 as length_km, created_at, updated_at'))
        ->get();



        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
        foreach ($polylines as $p) {
            $feature =[
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
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

