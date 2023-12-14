{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/profiles') }}">Profiles</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="userAlert"></div>

<form name="saveProfile" id="saveProfile" class="">
	<div class="row g-3">
		<div class="col-sm-12">
			<label for="name" class="form-label">Name</label>
			<input type="text" class="form-control" id="name" value="{{$profile['name'] ?? 'N/A';}}">
		</div>

		<div class="col-12">
			<label for="province" class="form-label">Visibility</label>
			<select class="form-select form-select-lg select2" id="visibility">
				<option option="-1" disabled>Inform the province</option>
				<option value="district" {{$profile['visibility'] == 'district' ? 'selected' : '';}}>District</option>
				<option value="province" {{$profile['visibility'] == 'province' ? 'selected' : '';}}>Province</option>
				<option value="national" {{$profile['visibility'] == 'national' ? 'selected' : '';}}>National</option>
			</select>
		</div>
		<div class="col-12">
			<div class="mb-3 form-check form-switch">
				<input class="form-check-input input-lg" type="checkbox" role="switch" id="webAccess" {{$profile['web_access'] == 1 ? 'checked' : '';}}>
				<label class="form-check-label" for="webAccess">Web access</label>
			</div>
			<div class="mb-3 form-check form-switch">
				<input class="form-check-input" type="checkbox" role="switch" id="mobileAccess" {{$profile['mobile_acess'] == 1 ? 'checked' : '';}}>
				<label class="form-check-label" for="mobileAccess">Mobile access</label>
			</div>	
			<div class="mb-3 form-check form-switch">
				<input class="form-check-input" type="checkbox" role="switch" id="deleted_at" <?php echo ($profile['deleted_at'] == NULL ? 'checked' : ''); ?>>
				<label class="form-check-label" for="deleted_at">Disable / Activate</label>
			</div>
		</div>
	</div>
	<button class="btn btn-primary btn-lg mt-3" id="btnSaveProfile" type="submit">Save</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		$("#btnSaveProfile").click(function(e) {
			e.preventDefault();

			let _this = $(this);
			let oldText = _this.text();

			let form = $("form[name='saveProfile']");
			let name = $("#name").val();
			let visibility = $("#visibility").val();
			let webAccess = $("#webAccess").is(":checked") ? 1 : 0;
			let mobileAccess = $("#mobileAccess").is(":checked") ? 1 : 0;
			let deletedAt = $("#deleted_at").is(":checked") ? 1 : 0;

			$.ajax({
				url: "{{ route('/profiles/save/'.$profile['id']) }}",
				method: "POST",
				dataType: "JSON",
				data: {
					name: name,
					visibility: visibility,
					web_access: webAccess,
					mobile_access: mobileAccess,
					deleted_at : deletedAt,
				},
				success: function(response) {
						let alertType = response.status == "success" ? "success" : "danger";
						$("#userAlert").empty().html(`<div class="alert alert-${alertType} alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						_this.text("Save").removeClass("disabled");

				},
				error : function(error){
					console.log(error);
				},
				beforeSend: function() {
					$("#userAlert").empty();
					_this.text("Please wait").addClass("disabled");
				},
				complete: function(XHR, status){
					_this.text(`${oldText} `).removeClass("disabled");					
				}
			})
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