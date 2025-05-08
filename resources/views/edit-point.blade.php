@extends('layout.template')

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
<div id="map"> </div>

<!-- Modal Edit Point -->
<div class="modal fade" id="editpointmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Point</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{route('points.store')}}" enctype="multipart/form-data">
        <div class="modal-body">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name Point</label>
                    <input type="text" class="form-control" id="name"  name="name" placeholder="Fill name point">
                  </div>
                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="image" class="form-label">Photo</label>
                      <input type="file" class="form-control" id="image-point"  name="image"
                      onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                      <img src="" alt="" id="preview-image-point" class="img-thumbnail" width="500">
                    </div>
                  <div class="mb-3">
                    <label for="geom_point" class="form-label">Geometry</label>
                    <textarea class="form-control" id="geom_point" name="geom_point" rows="3"></textarea>
                  </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
      </div>
    </div>
  </div>


@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
    var map = L.map('map').setView([-7.770984541591112, 110.37764084233453], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


        /* Digitize Function */
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
	draw: false,
	edit: {
		featureGroup: drawnItems,
		edit: true,
		remove: false
	}
});

map.addControl(drawControl);

map.on('draw:edited', function(e) {
	var layers = e.layers;

	layers.eachLayer(function(layer) {
		var drawnJSONObject = layer.toGeoJSON();
		console.log(drawnJSONObject);

		var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
		console.log(objectGeometry);

		// layer properties
		var properties = drawnJSONObject.properties;
		console.log(properties);

		drawnItems.addLayer(layer);

        // menampilkan data kedalam modal
        $('#name').val(properties.name);
        $('#description').val(properties.description);
        $('#geom_point').val(objectGeometry);
        $('#preview-image-point').attr('src', "{{ asset('storage/images')}}/" + properties.image);


        // menampilkan modal edit
        $('#editpointmodal').modal('show');
	});
});

    //Geojson Point
    var point = L.geoJson(null, {
				onEachFeature: function (feature, layer) {

                    // memasukkan layer point kedalam drawnItems
                    drawnItems.addLayer(layer);

                    var properties = feature.properties;
                    var objectGeometry = Terraformer.geojsonToWKT(feature.geometry);

					layer.on({
						click: function (e) {
                            // menampilkan data kedalam modal
                            $('#name').val(properties.name);
                            $('#description').val(properties.description);
                            $('#geom_point').val(objectGeometry);
                            $('#preview-image-point').attr('src', "{{ asset('storage/images')}}/" + properties.image);
                            // menampilkan modal edit
                            $('#editpointmodal').modal('show');
						},
					});
				},
			});
			$.getJSON("{{ route('api.point', $id) }}", function (data) {
				point.addData(data);
				map.addLayer(point);
                map.fitBounds(point.getBounds(), {
                    padding: [100, 100]
                });
			});


    </script>
@endsection
