{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/farmers') }}">Farmers</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="farmerAlert"></div>

<form name="saveFarmer" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="firstName" class="form-label">First name <span class="text-danger">*</span></label>
			<input type="text" class="form-control" id="firstName" value="{% if(!empty($farmer['first_name'])): %}{{$farmer['first_name']}}{% endif; %}">
		</div>

		<div class="col-sm-6">
			<label for="lastName" class="form-label">Last name <span class="text-danger">*</span></label>
			<input type="text" class="form-control" id="lastName" value="{% if(!empty($farmer['last_name'])): %}{{$farmer['last_name']}}{% endif; %}">
		</div>

		<div class="col-sm-6">
			<label for="birth-date" class="form-label">Gender <span class="text-danger">*</span></label>
				<select class="form-select" id="gender">
					<option value="Female" {% if(!empty($farmer['gender']) && $farmer['gender'] == 'Female'): %} {{ 'selected' }} {% endif; %}>Female</option>
					<option value="Male" {% if(!empty($farmer['gender']) && $farmer['gender'] == 'Male'): %} {{ 'selected' }} {% endif; %}>Male</option>
			</select>
		</div>

		<div class="col-sm-6">
			<label for="birth-date" class="form-label">Birth date <span class="text-danger">*</span></label>
			<input type="date" class="form-control" id="birthDate" value="{% if(!empty($farmer['birth_date'])):%}{{$farmer['birth_date']}}{% endif; %}">
		</div>

		<div class="col-sm-4">
			<label for="province" class="form-label">Province <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="province">
				<option option="-1" disabled>Inform the province</option>
				{% foreach(["Cabo Delgado", "Zambezia", "Nampula"] as $province): %}
				<option value="{{$province}}" {% if(!empty($farmer['province']) && $farmer['province'] == $province): %} {{ 'selected' }} {% endif; %}>{{$province}}</option>
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

		<div class="col-sm-6">
			<label for="email" class="form-label">Email <span class="text-muted">(Optional)</span></label>
			<input type="email" class="form-control" id="email" value="{% if(!empty($farmer['email'])): %}{{$farmer['email']}}{% endif; %}">
		</div>

		<div class="col-sm-6">
			<label for="mobileNumber" class="form-label">Mobile number </label>
			<input type="text" class="form-control" id="mobileNumber" value="{% if(!empty($farmer['mobile_number'])): %}{{$farmer['mobile_number']}}{% endif; %}">
		</div>

	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveFarmer" type="submit">Save</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		triggerChangeProvince();

		function triggerChangeProvince() {
			$("#province").trigger("change");

			$("#district").val("{{$farmer['district']}}");
		}

		$("#btnSaveFarmer").click(function(e) {
			try{
						e.preventDefault();

						let _this = $(this);
						let oldText = _this.text();

						let form = $("form[name='saveFarmer']");
						let firstName = $("#firstName").val();
						let lastName = $("#lastName").val();
						let birthDate = $("#birthDate").val();
						let email = $("#email").val();
						let mobileNumber = $("#mobileNumber").val();
						let province = $("#province").val();
						let district = $("#district").val();
						let administrativePost = $("#administrativePost").val();
						let gender = $("#gender").val();
						$.ajax({
							url: "{{ route('/farmers/save/'.$farmer['id']) }}",
							method: "POST",
							dataType: "JSON",
							data: {
								first_name: firstName,
								last_name: lastName,
								birth_date: birthDate,
								email: email,
								mobile_number: mobileNumber,
								province: province,
								district: district,
								administrative_post: administrativePost,
								gender: gender
							},
							success: function(response) {
								if (response.status == "success") {
									_this.text("Save").removeClass("disabled");
									$("#farmerAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Farmer saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
								}else{
									$("#farmerAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
								}
							},
							error : function(error, status){
								console.log('Error:',error.status, error.statusText);
							},
							beforeSend: function() {
								$("#farmerAlert").empty();
								_this.text("Please wait").addClass("disabled");
							},
							complete: function(XHR, status){
								_this.text(`${oldText} `).removeClass("disabled");					
							}
						})
					}catch(error){
						$("#farmerAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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