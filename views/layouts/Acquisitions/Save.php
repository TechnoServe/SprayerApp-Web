{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/acquisitions') }}">Acquisitions</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="equipmentAlert"></div>

<form name="saveAcquisition" id="saveAcquisition" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="user_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="user_uid" class="form-control" id="user_uid" value="{{$acquisitions['user_uid'];}}">
			<input type="text" name="user_name" class="form-control" id="user_name" value="{{$user['first_name'].' '.$user['last_name'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="chemical_acquisition_uid" class="form-label">Acquisition id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="chemical_acquisition_uid" id="chemical_acquisition_uid" value="{{$acquisitions['chemical_acquisition_uid'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="chemical_name" class="form-label">Chemical Name <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="chemical_name" name="chemical_name">
				{% foreach($chemicals as $chemical): %}
				<option value="{{$chemical}}" {{$acquisitions['chemical_name'] == $chemical ? 'selected' : ''}}>{{$chemical}}</option>
				{% endforeach; %}
			</select>
		</div>

		<div class="col-sm-6">
			<label for="chemical_quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
			<input type="number" min="0" step="any" class="form-control" name="chemical_quantity" id="chemical_quantity" value="{{$acquisitions['chemical_quantity'];}}">
		</div>

		<div class="col-sm-6">
			<label for="chemical_price" class="form-label">Price <span class="text-danger">*</span></label>
			<input type="number" min="0" step="any" class="form-control" name="chemical_price" id="chemical_price" value="{{$acquisitions['chemical_price'];}}">
		</div>
		
		<div class="col-sm-6">
			<label for="acquired_at" class="form-label">Acquisition Date </label>
			<input type="date" class="form-control" name="acquired_at" id="acquired_at" value="{{$acquisitions['acquired_at'];}}">
		</div>

		<div class="col-sm-6">
			<label for="chemical_acquisition_mode" class="form-label">Acquisition Mode <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="chemical_acquisition_mode" name="chemical_acquisition_mode">
				{% foreach($modes as $mode): %}
				<option value="{{$mode}}" {{$acquisitions['chemical_acquisition_mode'] == $mode ? 'selected' : ''}}>{{$mode}}</option>
				{% endforeach; %}
			</select>
		</div>

		<div class="col-sm-6">
			<label for="chemical_name" class="form-label">Supplier <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="chemical_supplier" name="chemical_supplier">
				{% foreach($suppliers as $supplier): %}
				<option value="{{$supplier}}" {{$acquisitions['chemical_supplier'] == $supplier ? 'selected' : ''}}>{{$supplier}}</option>
				{% endforeach; %}
			</select>
		</div>
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveAcquisition" type="submit">Save</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$("#btnSaveAcquisition").click(function(e) {
			try{
				e.preventDefault();
				let formData = $("#saveAcquisition").serialize();
				// console.log(formData); return;
				let _this = $(this);
				let oldText = _this.text();
				$.ajax({
					url: "{{ route('/acquisitions/save/'.$acquisitions['id']) }}",
					method: "POST",
					dataType: "JSON",
					data: formData,
					success: function(response) {
						if (response.status == "success") {
							_this.text("Save").removeClass("disabled");
							$("#equipmentAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Acquisition saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						}else{
							$("#equipmentAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						}
					},
					error : function(error, status){
						console.log('Error:',error.status, error.statusText);
					},
					beforeSend: function() {
						$("#equipmentAlert").empty();
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status){
						_this.text(`${oldText} `).removeClass("disabled");					
					}
				})
			}catch(error){
				$("#equipmentAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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