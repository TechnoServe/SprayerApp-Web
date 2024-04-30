{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/faqs') }}">Faqs</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="faqAlert"></div>

<form name="saveFaq" id="saveFaq" class="">
	<div class="row g-3">
		<input type="hidden" id="id" value="{{$faq['id'] ?? ''}}">
		<div class="col-sm-12">
			<label for="name" class="form-label">Title <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="title" id="title" value="{{$faq['title'] ?? ''}}">
		</div>
		<div class="col-sm-12">
			<label for="description" class="form-label">Description <span class="text-danger">*</span></label>
			<textarea class="form-control" name="description" id="description">{{$faq['description'] ?? ''}}</textarea>
		</div>
		<div class="col-sm-12">
			<label for="deleted_at" class="form-label">Activate/Disable</label><br />

			<label class="control-label switch">
				<input type="checkbox" name="deleted_at" id="deleted_at" <?php echo ($faq['deleted_at'] == NULL ? 'checked' : ''); ?>>
				<span class="slider round"></span>
			</label>
		</div>
	</div>
	<button class="btn btn-primary btn-lg mt-3" id="btnSaveFaq" type="submit">Save</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		$("#btnSaveFaq").click(function(e) {
			try {
				e.preventDefault();

				let _this = $(this);
				let oldText = _this.text();
				let title = $("#title").val();
				let description = $("#deleted_at").val();
				let deletedAt = $("#deleted_at").is(":checked") ? "true" : "false";
				$.ajax({
					url: "{{ route('/faqs/save/'.$faq['id']) }}",
					method: "POST",
					dataType: "JSON",
					data: {
						title: title,
						description: description,
						deleted_at: deletedAt
					},
					success: function(response) {
						let alertType = response.status == "success" ? "success" : "danger";
						$("#faqAlert").empty().html(`<div class="alert alert-${alertType} alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						_this.text("Save").removeClass("disabled");

					},
					error: function(error) {
						console.log(error);
					},
					beforeSend: function() {
						$("#faqAlert").empty();
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status) {
						_this.text(`${oldText} `).removeClass("disabled");
					}
				})
			} catch (error) {
				$("#faqAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Error: <span>Unexpected error occured while updating the faq data.</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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