{% extends views/layouts/Default.php %}

{% block content %}
<input type="hidden" id="url" data-url="{{ route('') }}">
<!-- <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
	<a class="btn btn-primary btn-lg" href="#" id="createFarmer">Create farmer</a>
</div>
 -->
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/farmers') }}">Farmers</a>
		</li>
	</ol>
</nav>
<div class="row mb-2 mt-3" id="loadFilter">
	<!--Filter widget-->
</div>

<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Name</th>
				<th>Province</th>
				<th>District</th>
				<th>Administrative Post</th>
				<th>Mobile</th>
				<th>Gender</th>
				<th>Last Sync Date</th>
				<th>Campaign</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Name</th>
				<th class="filterFooter">Province</th>
				<th class="filterFooter">District</th>
				<th class="filterFooter">Administrative Post</th>
				<th class="filterFooter">Mobile</th>
				<th class="filterFooter">Gender</th>
				<th class="filterFooter">Campaign</th>
				<th class="filterFooter">Last Sync Date</th>
				<th>Actions</th>
			</tr>
		</tfoot>
	</table>
</div>

<!-- <script src="{{ asset('scripts/location.js') }}"></script> -->
<script src="{{ asset('scripts/farmer.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#createFarmer").click(function(e) {
			e.preventDefault();

			let _this = $(this);
			let oldText = _this.text();

			$.ajax({
				url: "{{ route('/farmers/create'); }}",
				method: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == "success") {
						let url = '{{ route("/farmers/save/") }}' + "/" + response.id;
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

	function deleteFarmer(obj){

			let _this = $(obj);
			let oldText = obj.innerHTML;
			let conf  = window.confirm(`Deleting the farmer: ${obj.dataset.fullname} with id: ${obj.id}.\nAre you sure?`);
			if(conf){
				$.ajax({
					url: "{{ route('/farmers/delete/'); }}"+ "/" + obj.id,
					method: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status == "success") {
							alert(response.message)
							let url = '{{ route("/farmers") }}';
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