{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/equipments') }}">Equipments</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="equipmentAlert"></div>

<form name="saveEquipment" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="user_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="user_uid" class="form-control" id="user_uid" value="{{$equipments['user_uid'];}}">
			<input type="text" name="user_name" class="form-control" id="user_name" value="{{$user['first_name'].' '.$user['last_name'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="equipments_uid" class="form-label">Equipment id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="equipments_uid" id="equipments_uid" value="{{$equipments['equipments_uid'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="name" class="form-label">Equipment Name <span class="text-danger">*</span></label>
			<input type="text"  class="form-control" name="name" id="name" value="{{$equipments['name'];}}">
		</div>

		<div class="col-sm-6">
			<label for="brand" class="form-label">Number of trees <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="brand" id="brand" value="{{$equipments['brand'];}}">
		</div>

		<div class="col-sm-6">
			<label for="model" class="form-label">Model</label>
			<input type="text" class="form-control" name="model" id="model" value="{{$equipments['model'];}}">
		</div>
		<div class="col-sm-6">
			<label for="province" class="form-label">Status</label>
			<select class="form-select form-select-lg select2" id="status" name="status">
				<option value="Operational" {{$equipments['status' == 'Operational' ? 'selected' : '']}}>Operational</option>
				<option value="Non operational" {{$equipments['status' == 'Non operational' ? 'selected' : '']}}>Non operational</option>
			</select>
		</div>
		
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveEquipment" type="submit">Save</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$("#btnSaveEquipment").click(function(e) {
			try{
				e.preventDefault();

				let _this = $(this);
				let oldText = _this.text();
				$.ajax({
					url: "{{ route('/equipments/save/'.$equipments['id']) }}",
					method: "POST",
					dataType: "JSON",
					data: {
						user_uid: $("#user_uid").val(),
						equipments_uid: $("#equipments_uid").val(),
						name: $("#name").val(),
						brand: $("#brand").val(),
						model: $("#model").val(),
						status: $("#status").val(),
					},
					success: function(response) {
						if (response.status == "success") {
							_this.text("Save").removeClass("disabled");
							$("#equipmentAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Equipment saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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