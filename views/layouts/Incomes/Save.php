{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/incomes') }}">Expenses or Incomes</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="incomeAlert"></div>

<form name="saveIncome" id="saveIncome" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="user_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="user_uid" class="form-control" id="user_uid" value="{{$incomes['user_uid'];}}">
			<input type="text" name="user_name" class="form-control" id="user_name" value="{{$user['first_name'].' '.$user['last_name'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="expenses_income_uid" class="form-label">Income id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="expenses_income_uid" id="expenses_income_uid" value="{{$incomes['expenses_income_uid'];}}" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="category" class="form-label">Category <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="category" name="category">
				{% foreach($categories as $category): %}
				<option value="{{$category}}" {{$incomes['category'] == $category ? 'selected' : ''}}>{{$category}}</option>
				{% endforeach; %}
			</select>
		</div>

		<div class="col-sm-6">
			<label for="expenses_income_type" class="form-label">Income Type <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="expenses_income_type" name="expenses_income_type">
				{% foreach($types as $type): %}
				<option value="{{$type}}" {{$incomes['expenses_income_type'] == $type ? 'selected' : ''}}>{{$type}}</option>
				{% endforeach; %}
			</select>
		</div>



		<div class="col-sm-6">
			<label for="expenses_income_payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="expenses_income_payment_type" name="expenses_income_payment_type">
				{% foreach($payments as $payment): %}
				<option value="{{$payment}}" {{$incomes['expenses_income_payment_type'] == $payment ? 'selected' : ''}}>{{$payment}}</option>
				{% endforeach; %}
			</select>
		</div>

		<div class="col-sm-6">
			<label for="price" class="form-label">Price <span class="text-danger">*</span></label>
			<input type="number" min="0" step="any" class="form-control" name="price" id="price" value="{{$incomes['price'];}}">
		</div>

		<div class="col-sm">
			<label for="description" class="form-label">Deescription</label>
			<textarea class="form-control" name="description" id="description">{{$incomes['description'];}}</textarea>
		</div>
		
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveIncome" type="submit">Save</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$("#btnSaveIncome").click(function(e) {
			try{
				e.preventDefault();
				let formData = $("#saveIncome").serialize();
				// console.log(formData); return;
				let _this = $(this);
				let oldText = _this.text();
				$.ajax({
					url: "{{ route('/incomes/save/'.$incomes['id']) }}",
					method: "POST",
					dataType: "JSON",
					data: formData,
					success: function(response) {
						if (response.status == "success") {
							_this.text("Save").removeClass("disabled");
							$("#incomeAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Income or expense saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						}else{
							$("#incomeAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						}
					},
					error : function(error, status){
						console.log('Error:',error.status, error.statusText);
					},
					beforeSend: function() {
						$("#incomeAlert").empty();
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status){
						_this.text(`${oldText} `).removeClass("disabled");					
					}
				})
			}catch(error){
				$("#incomeAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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