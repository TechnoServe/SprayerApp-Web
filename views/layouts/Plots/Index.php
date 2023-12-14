{% extends views/layouts/Default.php %}

{% block content %}
<input type="hidden" id="url" data-url="{{ route('') }}">
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/plots') }}">Plots</a>
		</li>
	</ol>
</nav>
<div class="row mb-2 mt-3" id="loadFilter">
	<!--Filter widget-->
</div>

<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Farmer Name</th>
				<th>Plot Id</th>
				<th>Plot Name</th>
				<th>Province</th>
				<th>District</th>
				<th>Administrative Post</th>
				<th>Mobile</th>
				<!-- <th>Gender</th> -->
				<th>Last Sync At</th>
				<th>Campaign</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Farmer Name</th>
				<th class="filterFooter">Plot Id</th>
				<th class="filterFooter">Plot Name</th>
				<th class="filterFooter">Province</th>
				<th class="filterFooter">District</th>
				<th class="filterFooter">Administrative Post</th>
				<th class="filterFooter">Mobile</th>
				<!-- <th class="filterFooter">Gender</th> -->
				<th class="filterFooter">Last Sync At</th>
				<th class="filterFooter">Campaign</th>
				<th>Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
<!-- <script src="{{ asset('scripts/location.js') }}"></script> -->
<script src="{{ asset('scripts/plot.js') }}"></script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNzbw5cYdq47c_ZaC7I1mwE9CujmQ1dlw&callback=initMap&libraries=&v=weekly"
  defer
></script>


<style type="text/css">    
  #map {
	height: 450px;
  }
</style>

<div class="card card-primary card-outline"> 
    <div class="card-header border-0 pb-0 mb-2">
        <div class="d-flex justify-content-between">
            <p class="text-sm text-black"><b>Geo Data</b></p>
        </div>
    </div>
    <div class="card-body pt-0">
        <div id="map"></div>
    </div>
</div>

<script>

  function initMap() {
		const map = new google.maps.Map(document.getElementById("map"), {
		  zoom: 5,
		  center: { lat: -18.6696553, lng: 35.5273354 },
		});
		const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		const markers = locations.map((location, i) => {
		  return new google.maps.Marker({
			position: location,
			label: labels[i % labels.length],
		  });
		});
		new MarkerClusterer(map, markers, {
		  imagePath:            "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
		});
	  }
	  if (typeof variable === 'undefined') {
		  let locations;	  
	  }
	  locations = [];
	  locations = [
		{% foreach($chartValue as $coords): %}
			{ lat: {{ $coords["latitude"]; }} , lng: {{ $coords["longitude"]; }}  },
		{% endforeach; %}
	  ];
  
	$(document).ready(function() {
		$("#createPlot").click(function(e) {
			e.preventDefault();

			let _this = $(this);
			let oldText = _this.text();

			$.ajax({
				url: "{{ route('/plots/create'); }}",
				method: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == "success") {
						let url = '{{ route("/plots/save/") }}' + "/" + response.id;
						$(location).attr("href", url);
					}
				},
				error:function(obj,error){
					alert("Unexpected error occoured: "+error);
					console.log(obj, error);
				},
				beforeSend: function() {
					_this.text("Please wait").addClass("disabled");
				},
				complete: function(){
					_this.text(oldText).removeClass("disabled");

				}
			})
		});
	});


	function deletePlot(obj){

			let _this = $(obj);
			let oldText = obj.innerHTML;
			let conf  = window.confirm(`Deleting the plot with id: ${obj.dataset.uid} of farmer: ${obj.dataset.fullname}.\nAre you sure?`);
			if(conf){
				$.ajax({
					url: "{{ route('/plots/delete/'); }}"+ "/" + obj.id,
					method: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status == "success") {
							alert(response.message)
							let url = '{{ route("/plots") }}';
							$(location).attr("href", url);
						}
					},
					error:function(obj,error){
						alert("Unexpected error occoured: "+error);
					},
					beforeSend: function() {
						_this.innerHTML = "Please wait";
						_this.addClass("disabled");
					},
					complete: function(){
						_this.innerHTML = oldText;
						_this.removeClass("disabled");
					}
				})
			}
	}
</script>
{% endblock %}