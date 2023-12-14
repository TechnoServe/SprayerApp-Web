{% extends views/layouts/Default.php %}

{% block content %}
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/users') }}">Users</a>
		</li>
	</ol>
</nav>
<input type="hidden" id="url" data-url="{{ route('') }}">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
	<a class="btn btn-primary btn-lg" href="#" id="createUser">Create user</a>
</div>
<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Name</th>
				<th>Administrative posts</th>
				<th>District</th>
				<th>Province</th>
				<th>Mobile number</th>
				<th>Profile</th>
				<th>Status</th>
				<th>Last sync. Date</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Name</th>
				<th class="filterFooter">Administrative posts</th>
				<th class="filterFooter">District</th>
				<th class="filterFooter">Province</th>
				<th class="filterFooter">Mobile number</th>
				<th class="filterFooter">Profile</th>
				<th class="filterFooter">Status</th>
				<th class="filterFooter">Last sync. Date</th>
				<th>Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
<script src="{{ asset('scripts/user.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#createUser").click(function(e) {
			e.preventDefault();

			let _this = $(this);
			let oldText = _this.text();
			$.ajax({
				url: "{{ route('/users/create'); }}",
				method: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == "success") {
						let url = '{{ route("/users/save/") }}' + "/" + response.id;
						$(location).attr("href", url);
					}
				},
				error : function(error, status, message){
					console.log(`Error calling the form to create users: `, status, message, error);
					_this.text(`${oldText} `).removeClass("disabled");
				},
				beforeSend: function() {
					_this.text("Please wait").addClass("disabled");
				}
			})
		});
	});
</script>
{% endblock %}