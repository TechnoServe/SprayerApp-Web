{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/applications') }}">Applications</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="applicationAlert"></div>

<form name="saveApplication" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="farmer_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="farmer_uid" class="form-control" id="farmer_uid" value="{{$application['farmer_uid'];}}">
			<input type="text" name="farmer_name" class="form-control" id="farmer_name" value="{{$farmer['first_name'].' '.$farmer['last_name'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="chemical_application_uid" class="form-label">Application id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="chemical_application_uid" id="chemical_application_uid" value="{{$application['chemical_application_uid'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="application_number" class="form-label">Application Number <span class="text-danger">*</span></label>
			<input type="number" min="1" step="1" class="form-control" name="application_number" id="application_number" value="{{$application['application_number'];}}">
		</div>

		<div class="col-sm-6">
			<label for="number_of_trees_sprayed" class="form-label">Number of trees <span class="text-danger">*</span></label>
			<input type="number" min="1" step="5" class="form-control" name="number_of_trees_sprayed" id="number_of_trees_sprayed" value="{{$application['number_of_trees_sprayed'];}}">
		</div>

		<div class="col-sm-6">
			<label for="sprayed_at" class="form-label">Sprayer Date <span class="text-danger">*</span></label>
			<input type="datetime-local" class="form-control" name="sprayed_at" id="sprayed_at" value="{{$application['sprayed_at'];}}">
		</div>

		
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveApplication" type="submit">Save</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$("#btnSaveApplication").click(function(e) {
			try{
				e.preventDefault();

				let _this = $(this);
				let oldText = _this.text();
				$.ajax({
					url: "{{ route('/applications/save/'.$application['id']) }}",
					method: "POST",
					dataType: "JSON",
					data: {
						farmer_uid: $("#farmer_uid").val(),
						chemical_application_uid: $("#chemical_application_uid").val(),
						application_number: $("#application_number").val(),
						number_of_trees_sprayed: $("#number_of_trees_sprayed").val(),
						sprayed_at: $("#sprayed_at").val(),
					},
					success: function(response) {
						if (response.status == "success") {
							_this.text("Save").removeClass("disabled");
							$("#applicationAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Application saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						}else{
							$("#applicationAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						}
					},
					error : function(error, status){
						console.log('Error:',error.status, error.statusText);
					},
					beforeSend: function() {
						$("#applicationAlert").empty();
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status){
						_this.text(`${oldText} `).removeClass("disabled");					
					}
				})
			}catch(error){
				$("#applicationAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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