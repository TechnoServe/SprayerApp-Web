{% extends views/layouts/Default.php %}

{% block content %}
<input type="hidden" id="url" data-url="{{ route('') }}">
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/equipments') }}">Equipments</a>
		</li>
	</ol>
</nav>
<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Sprayer Name</th>
				<th>Equipment Id</th>
				<th>Name</th>
				<th>Model</th>
				<th>Brand</th>
				<th>Status</th>
				<th>Last Sync At</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Farmer Name</th>
				<th class="filterFooter">Equipment Id</th>
				<th class="filterFooter">Name</th>
				<th class="filterFooter">Model</th>
				<th class="filterFooter">Brand</th>
				<th class="filterFooter">Status</th>
				<th class="filterFooter">Last Sync At</th>
				<th>Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
<!-- <script src="{{ asset('scripts/location.js') }}"></script> -->
<script src="{{ asset('scripts/equipment.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#createEquipment").click(function(e) {
			e.preventDefault();

			let _this = $(this);
			let oldText = _this.text();

			$.ajax({
				url: "{{ route('/equipments/create'); }}",
				method: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == "success") {
						let url = '{{ route("/equipments/save/") }}' + "/" + response.id;
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


	function deleteEquipments(obj){

			let _this = $(obj);
			let oldText = obj.innerHTML;
			let conf  = window.confirm(`Deleting the equipment with id: ${obj.dataset.uid} of sprayer: ${obj.dataset.fullname}.\nAre you sure?`);
			if(conf){
				$.ajax({
					url: "{{ route('/equipments/delete/'); }}"+ "/" + obj.id,
					method: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status == "success") {
							alert(response.message)
							let url = '{{ route("/equipments") }}';
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