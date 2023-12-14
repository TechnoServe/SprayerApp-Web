{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/plots') }}">Plots</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="plotAlert"></div>

<form name="savePlot" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="farmer_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="farmer_uid" class="form-control" id="farmer_uid" value="{{$plot['farmer_uid'];}}" readonly="readonly">
			<input type="text" name="farmer_name" class="form-control" id="farmer_name" value="{{$farmer['first_name'].' '.$farmer['last_name'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="plot_uid" class="form-label">Plot id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="plot_uid" id="plot_uid" value="{{$plot['plot_uid'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="name" class="form-label">Plot Name <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="name" id="name" value="{{$plot['name'];}}">
		</div>

		<div class="col-sm-6">
			<label for="number_of_trees" class="form-label">Number of trees <span class="text-danger">*</span></label>
			<input type="number" min="0" step="5" class="form-control" name="number_of_trees" id="number_of_trees" value="{{$plot['number_of_trees'];}}">
		</div>

		<div class="col-sm-4">
			<label for="province" class="form-label">Province <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="province">
				<option option="-1" disabled>Inform the province</option>
				{% foreach(["Cabo Delgado", "Zambezia", "Nampula"] as $province): %}
				<option value="{{$province}}" {{$plot["province"] == $province ? 'selected' : ''}}>{{$province}}</option>
				{% endforeach; %}
				
			</select>
		</div>

		<div class="col-sm-4">
			<label for="district" class="form-label">District <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="district"></select>
		</div>

		<div class="col-sm-4">
			<label for="administrative-post" class="form-label">Administrative post</label>
			<select class="form-select form-select-lg select2" id="administrativePost"></select>
		</div>

	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSavePlot" type="submit">Save</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		triggerChangeProvince();

		function triggerChangeProvince() {
			$("#province").trigger("change");

			$("#district").val("{{$plot['district']}}");

			$("#district").trigger("change");

			$("#administrativePost").val("{{$plot['administrative_post']}}")
		}

		$("#btnSavePlot").click(function(e) {
			try{
						e.preventDefault();

						let _this = $(this);
						let oldText = _this.text();
						$.ajax({
							url: "{{ route('/plots/save/'.$plot['id']) }}",
							method: "POST",
							dataType: "JSON",
							data: {
								farmer_uid: $("#farmer_uid").val(),
								plot_uid: $("#plot_uid").val(),
								name: $("#name").val(),
								number_of_trees: $("#number_of_trees").val(),
								province: $("#province").val(),
								district: $("#district").val(),
								administrative_post: $("#administrativePost").val(),
							},
							success: function(response) {
								if (response.status == "success") {
									_this.text("Save").removeClass("disabled");
									$("#plotAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Plot saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
								}else{
									$("#plotAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
								}
							},
							error : function(error, status){
								console.log('Error:',error.status, error.statusText);
							},
							beforeSend: function() {
								$("#plotAlert").empty();
								_this.text("Please wait").addClass("disabled");
							},
							complete: function(XHR, status){
								_this.text(`${oldText} `).removeClass("disabled");					
							}
						})
					}catch(error){
						$("#plotAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						console.log(error);
					}
				})
	});
</script>

<style>
	.select2-container--default .select2-selection--single {
		height: 38px;
		border: 1px solid #ced4da;
	}

	.select2-container--default .select2-selection--single .select2-selection__rendered {
		line-height: 38px;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow {
		top: 7px;
	}
</style>

{% endblock %}