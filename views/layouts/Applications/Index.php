{% extends views/layouts/Default.php %}

{% block content %}
<input type="hidden" id="url" data-url="{{ route('') }}">
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/applications') }}">Applications</a>
		</li>
	</ol>
</nav>
<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Farmer Name</th>
				<th>Application Id</th>
				<th>Number of Trees sprayed</th>
				<th>Application Number</th>
				<th>Sprayer Date</th>
				<th>Province</th>
				<th>District</th>
				<!-- <th>Administrative Post</th> -->
				<th>Last Sync At</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Farmer Name</th>
				<th class="filterFooter">Application Id</th>
				<th class="filterFooter">Number of Trees sprayed</th>
				<th class="filterFooter">Application Number</th>
				<th class="filterFooter">Sprayer Date</th>
				<th class="filterFooter">Province</th>
				<th class="filterFooter">District</th>
				<!-- <th class="filterFooter">Administrative Post</th> -->
				<th class="filterFooter">Last Sync At</th>
				<th>Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
<!-- <script src="{{ asset('scripts/location.js') }}"></script> -->
<script src="{{ asset('scripts/application.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#createApplication").click(function(e) {
			e.preventDefault();

			let _this = $(this);
			let oldText = _this.text();

			$.ajax({
				url: "{{ route('/applications/create'); }}",
				method: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == "success") {
						let url = '{{ route("/applications/save/") }}' + "/" + response.id;
						$(location).attr("href", url);
					}
				},
				error:function(obj,error){
					alert("Unexpected error occoured: "+error);
					console.log(obj, error);
				},
				beforeSend: function() {
					_this.text("Please wait").addClass("disabled");
				},
				complete: function(){
					_this.text(oldText).removeClass("disabled");

				}
			})
		});
	});


	function deleteApplication(obj){

			let _this = $(obj);
			let oldText = obj.innerHTML;
			let conf  = window.confirm(`Deleting the application with id: ${obj.dataset.uid} of farmer: ${obj.dataset.fullname}.\nAre you sure?`);
			if(conf){
				$.ajax({
					url: "{{ route('/applications/delete/'); }}"+ "/" + obj.id,
					method: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status == "success") {
							alert(response.message)
							let url = '{{ route("/applications") }}';
							$(location).attr("href", url);
						}
					},
					error:function(obj,error){
						alert("Unexpected error occoured: "+error);
					},
					beforeSend: function() {
						_this.innerHTML = "Please wait";
						_this.addClass("disabled");
					},
					complete: function(){
						_this.innerHTML = oldText;
						_this.removeClass("disabled");
					}
				})
			}
	}
</script>
{% endblock %}