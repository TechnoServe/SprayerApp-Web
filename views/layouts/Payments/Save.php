{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/payments') }}">Payments</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="paymentAlert"></div>

<form name="formSavePayment" class="" id="formSavePayment">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="farmer_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="farmer_uid" class="form-control" id="farmer_uid" value="{{$payment['farmer_uid'];}}" readonly="readonly">
			<input type="text" name="farmer_name" class="form-control" id="farmer_name" value="{{$farmer['first_name'].' '.$farmer['last_name'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="payment_uid" class="form-label">Payment id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="payment_uid" id="payment_uid" value="{{$payment['payment_uid'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
			<select class="form-select" id="payment_type" name="payment_type">
				{% foreach([ 'Mzn', 'Kg',] as $type): %}
				<option value="{{$type}}" {{$payment['payment_type'] == $type ? 'selected' : ''}}>{{$type}}</option>
				{% endforeach; %}
			</select>
		</div>

		<div class="col-sm-3">
			<label for="paid" class="form-label">Amount <span class="text-danger">*</span></label>
			<input type="number" step="any" class="form-control" name="paid" id="paid" value="{{$payment['paid'];}}">
		</div>

		<div class="col-sm-3">
			<label for="discount" class="form-label">Discount</label>
			<input type="number" step="any" class="form-control" name="discount" id="discount" value="{{$payment['discount'];}}">
		</div>

		<div class="col-sm">
			<label for="description" class="form-label">Deescription</label>
			<textarea class="form-control" name="description" id="description">{{$payments['description'];}}</textarea>
		</div>
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSavePayment" type="submit">Save</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		triggerChangeProvince();

		function triggerChangeProvince() {
			$("#province").trigger("change");

			$("#district").val("{{$payment['district']}}");

			$("#district").trigger("change");

			$("#administrativePost").val("{{$payment['administrative_post']}}")
		}

		$("#btnSavePayment").click(function(e) {
			try{
						e.preventDefault();
						let formData = $("#formSavePayment").serialize();
						let _this = $(this);
						let oldText = _this.text();
						$.ajax({
							url: "{{ route('/payments/save/'.$payment['id']) }}",
							method: "POST",
							dataType: "JSON",
							data: formData,
							success: function(response) {
								if (response.status == "success") {
									_this.text("Save").removeClass("disabled");
									$("#paymentAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Payment saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
								}else{
									$("#paymentAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
								}
							},
							error : function(error, status){
								console.log('Error:',error.status, error.statusText);
							},
							beforeSend: function() {
								$("#paymentAlert").empty();
								_this.text("Please wait").addClass("disabled");
							},
							complete: function(XHR, status){
								_this.text(`${oldText} `).removeClass("disabled");					
							}
						})
					}catch(error){
						$("#paymentAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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