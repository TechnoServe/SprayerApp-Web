{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/agreements') }}">Agreements</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="agreementAlert"></div>

<form name="formSaveAgreement" class="" id="formSaveAgreement">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="farmer_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="farmer_uid" class="form-control" id="farmer_uid" value="{{$agreement['farmer_uid'];}}" readonly="readonly">
			<input type="text" name="farmer_name" class="form-control" id="farmer_name" value="{{$farmer['first_name'].' '.$farmer['last_name'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="payment_aggreement_uid" class="form-label">Agreement id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="payment_aggreement_uid" id="payment_aggreement_uid" value="{{$agreement['payment_aggreement_uid'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="payment_type" class="form-label">Agreement Type <span class="text-danger">*</span></label>
			<select class="form-select" id="payment_type" name="payment_type">
				{% foreach([ 'Mzn', 'Kg',] as $type): %}
				<option value="{{$type}}" {{$agreement['payment_type'] == $type ? 'selected' : ''}}>{{$type}}</option>
				{% endforeach; %}
			</select>
		</div>

		<div class="col-sm-6">
			<label for="aggreed_payment" class="form-label">Agreed payment <span class="text-danger">*</span></label>
			<input type="number" step="any" class="form-control" name="aggreed_payment" id="aggreed_payment" value="{{$agreement['aggreed_payment'];}}">
		</div>
		<div class="col-sm-6">
			<label for="aggreed_trees_to_spray" class="form-label">Trees to spray <span class="text-danger">*</span></label>
			<input type="number" step="any" class="form-control" name="aggreed_trees_to_spray" id="aggreed_trees_to_spray" value="{{$agreement['aggreed_trees_to_spray'];}}">
		</div>

		<div class="col-sm-6">
			<label for="number_of_applications" class="form-label">Number of applications</label>
			<input type="number" min="1" step="1" class="form-control" name="number_of_applications" id="number_of_applications" value="{{$agreement['number_of_applications'];}}">
		</div>
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveAgreement" type="submit">Save</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		triggerChangeProvince();

		function triggerChangeProvince() {
			$("#province").trigger("change");

			$("#district").val("{{$agreement['district']}}");

			$("#district").trigger("change");

			$("#administrativePost").val("{{$agreement['administrative_post']}}")
		}

		$("#btnSaveAgreement").click(function(e) {
			try{
						e.preventDefault();
						let formData = $("#formSaveAgreement").serialize();
						let _this = $(this);
						let oldText = _this.text();
						$.ajax({
							url: "{{ route('/agreements/save/'.$agreement['id']) }}",
							method: "POST",
							dataType: "JSON",
							data: formData,
							success: function(response) {
								if (response.status == "success") {
									_this.text("Save").removeClass("disabled");
									$("#agreementAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Agreement saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
								}else{
									$("#agreementAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
								}
							},
							error : function(error, status){
								console.log('Error:',error.status, error.statusText);
							},
							beforeSend: function() {
								$("#agreementAlert").empty();
								_this.text("Please wait").addClass("disabled");
							},
							complete: function(XHR, status){
								_this.text(`${oldText} `).removeClass("disabled");					
							}
						})
					}catch(error){
						$("#agreementAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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