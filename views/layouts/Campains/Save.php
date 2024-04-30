{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/campains') }}">Campaigns</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="campainAlert"></div>

<form name="saveCampain" id="saveCampain" class="">
	<div class="row g-3">
		<input type="hidden" id="id" value="{{$campain['id'] ?? ''}}">
		<div class="col-sm-12">
			<label for="opening" class="form-label">Starting Date <span class="text-danger">*</span></label>
			<input type="date" class="form-control" name="opening" id="opening" value="{{$campain['opening'] ?? ''}}">
		</div>
		<div class="col-sm-12">
			<label for="clossing" class="form-label">End Date <span class="text-danger">*</span></label>
			<input type="date" class="form-control" name="clossing" id="clossing" value="{{$campain['clossing'] ?? ''}}">
		</div>
		<div class="col-sm-12">
			<label for="description" class="form-label">Description  </label>
			<textarea class="form-control" name="description" id="description">{{$campain['description'] ?? ''}}</textarea>
		</div>
		<div class="col-sm-12">
			<label for="profileId" class="form-label">Activate/Disable</label><br />

			<label class="control-label switch">
			  <input type="checkbox" name="deleted_at" id="deleted_at" <?php echo ($campain['deleted_at'] == NULL ? 'checked' : ''); ?>>
			  <span class="slider round"></span>
			</label>
		</div>
	</div>
	<button class="btn btn-primary btn-lg mt-3" id="btnSaveCampain" type="submit">Save</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		$("#btnSaveCampain").click(function(e) {
			try{
				e.preventDefault();

				let _this = $(this);
				let oldText = _this.text();
				let formData =$("#saveCampain").serialize();
				$.ajax({
					url: "{{ route('/campains/save/'.$campain['id']) }}",
					method: "POST",
					dataType: "JSON",
					data: formData,
					success: function(response) {
						let alertType = response.status == "success" ? "success" : "danger";
						$("#campainAlert").empty().html(`<div class="alert alert-${alertType} alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						_this.text("Save").removeClass("disabled");

					},
					error : function(error){
						console.log(error);
					},
					beforeSend: function() {
						$("#campainAlert").empty();
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status){
						_this.text(`${oldText} `).removeClass("disabled");					
					}
				})
			}catch(error){
				$("#campainAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Error: <span>Unexpected error occured while updating the campagin data.</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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