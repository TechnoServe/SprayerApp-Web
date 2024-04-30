{% extends views/layouts/Default.php %}

{% block content %}
<input type="hidden" id="url" data-url="{{ route('') }}">
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/agreements') }}">Agreements</a>
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
				<th>Provider</th>
				<th>Farmer Name</th>
				<th>Mobile</th>
				<th>Agreement Id</th>
				<th>Payment Type</th>
				<th>Agreed payment</th>
				<th>Trees to spray</th>
				<th>Number of application</th>
				<th>Province</th>
				<th>District</th>
				<th>Last Sync At</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Provider</th>
				<th class="filterFooter">Farmer Name</th>
				<th class="filterFooter">Mobile</th>
				<th class="filterFooter">Agreement Id</th>
				<th class="filterFooter">Payment Type</th>
				<th class="filterFooter">Agreed payment</th>
				<th class="filterFooter">Trees to spray</th>
				<th class="filterFooter">Number of application</th>
				<th class="filterFooter">Province</th>
				<th class="filterFooter">District</th>
				<th class="filterFooter">Last Sync At</th>
				<th class="filterFooter">Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
<!-- <script src="{{ asset('scripts/location.js') }}"></script> -->
<script src="{{ asset('scripts/agreement.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#createAgreement").click(function(e) {
			e.preventDefault();

			let _this = $(this);
			let oldText = _this.text();

			$.ajax({
				url: "{{ route('/agreements/create'); }}",
				method: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == "success") {
						let url = '{{ route("/agreements/save/") }}' + "/" + response.id;
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


	function deleteAgreement(obj){

			let _this = $(obj);
			let oldText = obj.innerHTML;
			let conf  = window.confirm(`Deleting the agreement with id: ${obj.dataset.uid} of farmer: ${obj.dataset.fullname}.\nAre you sure?`);
			if(conf){
				$.ajax({
					url: "{{ route('/agreements/delete/'); }}"+ "/" + obj.id,
					method: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status == "success") {
							alert(response.message)
							let url = '{{ route("/agreements") }}';
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