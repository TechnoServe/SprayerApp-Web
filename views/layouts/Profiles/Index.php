{% extends views/layouts/Default.php %}

{% block content %}
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/profiles') }}">Profiles</a>
		</li>
	</ol>
</nav>
<input type="hidden" id="url" data-url="{{ route('') }}">
<!-- <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
	<a class="btn btn-primary btn-lg" href="#" id="createProfile">Create profile</a>
</div> -->
<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Name</th>
				<th>Visibility</th>
				<th>Web access</th>
				<th>Mobile access</th>
				<th>Status</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Name</th>
				<th class="filterFooter">Visibility</th>
				<th class="">Web access</th>
				<th class="">Mobile access</th>
				<th class="filterFooter">Status</th>
				<th>Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
<script src="{{ asset('scripts/profile.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#createProfile").click(function(e) {
			e.preventDefault();

			let _this = $(this);

			$.ajax({
				url: "{{ route('/profiles/create'); }}",
				method: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == "success") {
						let url = '{{ route("/profiles/save/") }}' + "/" + response.id;
						$(location).attr("href", url);
					}
				},
				beforeSend: function() {
					_this.text("Please wait").addClass("disabled");
				}
			})
		});
	});
</script>
{% endblock %}