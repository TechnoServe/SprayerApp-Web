{% extends views/layouts/Default.php %}

{% block content %}
<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a class="display-6" style="text-decoration: none;" href="{{ route('/requests') }}">Requests</a>
		</li>
	</ol>
</nav>
<input type="hidden" id="url" data-url="{{ route('') }}">
<div id="contentRequest"></div>
<div class="table-responsive table-responsive-lg">
	<table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Mobile number</th>
				<th>Province</th>
				<th>District</th>
				<th>Status</th>
				<th>Approved By</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<th class="filterFooter">Name</th>
				<th class="filterFooter">Email</th>
				<th class="filterFooter">Mobile number</th>
				<th class="filterFooter">Province</th>
				<th class="filterFooter">District</th>
				<th class="filterFooter">Status</th>
				<th class="filterFooter">Approved By</th>
				<th>Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
<script src="{{ asset('scripts/request.js') }}"></script>
<script>
	function updateApproved(id, status){
		let loading = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>';
		let url = "{{route('/requests/:id/approve')}}";
			url = url.replace(":id", id);
			contentRequest.innerHTML = loading;
			let params = {status:status};
			const formData = new FormData();
			formData.append("status", status)
		    fetch(url, {
		    	method: "POST",
		    	body: formData
		    }).then(resp => resp.json())
              .then(resp => {
                buildAlert(resp.status, resp.message);
                if(resp.status == "success"){
                 buildAlert(resp.status, resp.message);
                  location.href = "{{route('/requests')}}";
                }
              })
              .catch(error => {
                buildAlert(error.status, error.message);
              })
	}


        function buildAlert(type, message){
          let icon = '<i class="fa fa-check"></i>';
          switch(type){
            case 'success' : icon = '<i class="fa fa-2x fa-check-circle"></i>'; break;
            case 'error' : icon = '<i class="fa fa-2x fa-times-circle"></i>'; break;
            default: icon = '<i class="fa fa-2x fa-info-circle"></i>'; break;
          }
          let alertType = (type == 'error') ? 'danger' : type;
          let output =`<div class="alert alert-${alertType} row" text-left><div class="col-sm-2"><strong>${icon}</strong></div><div class="col-sm-8">${message}</div><div class="col-sm-2"><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>`;
          contentRequest.innerHTML = output;
        }
         
</script>
{% endblock %}