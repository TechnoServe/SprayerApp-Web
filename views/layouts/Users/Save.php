{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/users') }}">Users</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="profileAlert"></div>

<form name="saveUser" class="">
	<div class="row g-3">
		<div class="col-sm-4">
			<label for="firstName" class="form-label">First name</label>
			<input type="text" class="form-control" id="firstName" value="{% if(!empty($user['first_name'])): %}{{$user['first_name']}}{% endif; %}">
		</div>

		<div class="col-sm-4">
			<label for="lastName" class="form-label">Last name</label>
			<input type="text" class="form-control" id="lastName" value="{% if(!empty($user['last_name'])): %}{{$user['last_name']}}{% endif; %}">
		</div>

		<div class="col-sm-4">
			<label for="birth-date" class="form-label">Birth date</label>
			<input type="date" class="form-control" id="birthDate" value="{%if(!empty($user['birth_date'])):%}{{$user['birth_date']}}{% endif; %}">
		</div>

		<div class="col-sm-6">
			<label for="email" class="form-label">Email <span class="text-muted"></span></label>
			<input type="email" class="form-control" id="email" value="{% if(!empty($user['email'])): %}{{$user['email']}}{% endif; %}">
		</div>

		<div class="col-sm-6">
			<label for="mobileNumber" class="form-label">Mobile number <span class="text-muted">(username)</span></label>
			<input type="text" class="form-control" id="mobileNumber" value="{% if(!empty($user['mobile_number'])): %}{{$user['mobile_number']}}{% endif; %}">
		</div>

		<div class="col-sm-4">
			<label for="province" class="form-label">Province</label>
			<select class="form-select form-select-lg select2" id="province">
				<option option="-1" disabled>Inform the province</option>
				<option value="Cabo Delgado" {% if(!empty($user['province']) && $user['province'] == "Cabo Delgado"): %} {{ 'selected' }} {% endif; %}>Cabo Delgado</option>
				<option value="Zambezia" {% if(!empty($user['province']) && $user['province'] == "Zambezia"): %} {{ 'selected' }} {% endif; %}>Zambezia</option>
				<option value="Nampula" {% if(!empty($user['province']) && $user['province'] == "Nampula"): %} {{ 'selected' }} {% endif; %}>Nampula</option>
			</select>
		</div>

		<div class="col-sm-4">
			<label for="district" class="form-label">District</label>
			<select class="form-select form-select-lg select2" id="district"></select>
		</div>

		<div class="col-sm-4">
			<label for="administrative-post" class="form-label">Administrative post</label>
			<select class="form-select form-select-lg select2" id="administrativePost"></select>
		</div>

		<div class="col-sm-4">
			<label for="profileId" class="form-label">Profile</label>
			<select class="form-select form-select-lg select2" id="profileId">
				{% foreach($profiles as $profile): %}
					<option value="{{$profile['id']}}" {% if(!empty($profile['id']) && $profile['id'] == $user['profile_id']): %} {{ 'selected' }} {% endif; %}>{{$profile['name']}}</option>
				{% endforeach; %}
			</select>
		</div>
		<div class="col-sm-4">
			<label for="profileId" class="form-label">Activate/Disable</label><br />

			<label class="control-label switch">
			  <input type="checkbox" name="deleted_at" id="deleted_at" <?php echo ($user['deleted_at'] == NULL ? 'checked' : ''); ?>>
			  <span class="slider round"></span>
			</label>
		</div>	
	<button class="btn btn-primary btn-md mt-3" id="btnSaveUser" type="submit">Save</button>
</form>


	<button class="btn btn-danger btn-md mt-3" id="btnUpdatePassword" type="submit">New password</button>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		triggerChangeProvince();

		function triggerChangeProvince() {
			$("#province").trigger("change");

			$("#district").val("{{$user['district']}}");

			$("#district").trigger("change");

			$("#administrativePost").val("{{$user['administrative_post']}}");
		}

		$("#btnSaveUser").click(function(e) {
			try{
				e.preventDefault();

				let _this = $(this);
				let oldText = _this.text();

				let form = $("form[name='saveUser']");
				let firstName = $("#firstName").val();
				let lastName = $("#lastName").val();
				let birthDate = $("#birthDate").val();
				let email = $("#email").val();
				let mobileNumber = $("#mobileNumber").val();
				let province = $("#province").val();
				let district = $("#district").val();
				let administrativePost = $("#administrativePost").val();
				let profileId = $("#profileId").val();
				$.ajax({
					url: "{{ route('/users/save/'.$user['id']) }}",
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
						profile_id: profileId,
						deleted_at : deleted_at.checked,
					},
					success: function(response) {
						let alertType = response.status == "success" ? "success" : "danger";
						$("#profileAlert").empty().html(`<div class="alert alert-${alertType} alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						_this.text("Save").removeClass("disabled");

					},
					error : function(error){
						console.log(error);
					},
					beforeSend: function() {
						$("#profileAlert").empty();
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status){
						_this.text(`${oldText} `).removeClass("disabled");					
					}
				})
			}catch(error){
				$("#profileAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Error: <span>Unexpected error occured while updating the user data.</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
					console.log(error);
			}


		})
	});

	btnUpdatePassword.addEventListener("click", function(e){
		e.preventDefault();
		let _this = $(this);
		let conf = window.confirm("Are you sure?");
		let button = this;
		let oldText = button.innerHTML;
		let postURL = "{{ route('/users/reset-password/'.$user['id']) }}";
		if(conf){
			$.ajax({
					url: postURL,
					method: "POST",
					dataType: "JSON",
					success: function(response) {
						if (response.status == "success") {
							_this.text("Save").removeClass("disabled");
							$("#profileAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>User password reset successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						}else{
							$("#profileAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						}
						$('#element').toast('show')

					},
					error : function(error){
						console.log(error);
					},
					beforeSend: function() {
						
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status){
						_this.text(`${oldText} `).removeClass("disabled");					
					}
		})
		}
	})
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