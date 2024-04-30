{% extends views/layouts/Default.php %}

{% block content %}

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="{{ route('/farmers') }}">Farmers</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">SMS Alert</li>
	</ol>
</nav>
<div id="smsFarmerAlert"></div>

<form name="sendSMSToFarmersForm" id="sendSMSToFarmersForm" class="">

	<div class="row g-3">
		<div class="col-sm-12">
			<div class="form-group">
				<label>Province</label>
				<select class="form-control {{ $_SESSION['user']['isSeller'] ? '' : 'select2'; }} province" name="province[]" id="province" style="width: 100%;" {{ $_SESSION['user']['isSeller'] ? '' : 'multiple'; }}>
					{%if($_SESSION['user']['isSeller']):%}
					<option value="{{$_SESSION['user']['province']}}" selected>{{$_SESSION['user']['province']}}</option>

					{%else:%}
					<option option="-1" disabled>Inform the province</option>
					<option value="Cabo Delgado">Cabo Delgado</option>
					<option value="Zambezia">Zambezia</option>
					<option value="Nampula">Nampula</option>
					{%endif;%}
				</select>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label>District</label>
				<select class="form-control   {{ $_SESSION['user']['isSeller'] ? '' : 'select2'; }} district" name="district[]" id="district" style="width: 100%;" {{ $_SESSION['user']['isSeller'] ? '' : 'multiple'; }}>
					{%if($_SESSION['user']['isSeller']):%}
					<option value="{{$_SESSION['user']['district']}}" selected>{{$_SESSION['user']['district']}}</option>

					{%else:%}
					<option option="-1" disabled>District</option>
					{%endif;%}

				</select>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label>Gender</label>
				<select class="form-control " name="gender" id="gender" style="width: 100%;">
					<option value=""></option>
					<option value="Female">Female</option>
					<option value="Male">Male</option>

				</select>
			</div>
		</div>
		<div class="col-sm-12">
			<label for="message" class="form-label">Message <span class="text-danger">*</span></label>
			<textarea class="form-control" name="message" id="message" maxlength="480" value=""></textarea>
			<small><span id="totalChs">0</span> / 480</small>
		</div>

	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-sm">
			<thead>
				<tr>
					<th>Check <input type="checkbox" id="checkAll" checked /></th>
					<th>Full Name</th>
					<th>Gender</th>
					<th>Phone Number</th>
					<th>Province</th>
					<th>District</th>
				</tr>
			</thead>
			<tbody id="resultFarmers">
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6" style="text-align: center;"><small>Please, note! Some farmers are not able to receive the sms due invalid phone numbers!</small></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnsendSMSToFarmers" type="submit">send</button>
</form>

<script src="{{ asset('scripts/location.js') }}" type="text/javascript"></script>


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
<script type="text/javascript">
	$(document).ready(function() {

		triggerChangeProvince();

		function triggerChangeProvince() {
			$("#province").trigger("change");

			$("#district").val("{{$farmer['district']}}");

			$("#district").trigger("change");

		}

		$("#district, #province, #gender").change(function(e) {
			e.preventDefault();
			getAllFarmers();
		});

		function getAllFarmers(event) {
			let _loading = '<i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse w3-center"></i>';
			let formData = new FormData(sendSMSToFarmersForm);
			let content = document.querySelector("#resultFarmers");
			content.innerHTML = _loading;
			let url = "{{ route('/farmers/sms/get-geo-farmers') }}";
			fetch(url, {
					method: "POST",
					body: formData,
				}).then(resp => resp.json())
				.then(resp => {
					content.innerHTML = "";
					if (resp.status == "error") {
						$("#smsFarmerAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${resp.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
					} else {
						let data = resp.data;
						data.forEach(farmer => {
							let prefixes = ["82", "83", "84", "85", "86", "87"];
							let validNumber = prefixes.indexOf(farmer.mobile_number.substr(0, 2)) >= 0 && farmer.mobile_number.length == 9;
							//Creating DOM Elements
							let tr = document.createElement("tr");
							let tdNumber = document.createElement("td");
							let tdFullname = document.createElement("td");
							tdFullname.innerHTML = farmer.fullname;
							let tdGender = document.createElement("td");
							tdGender.innerHTML = farmer.gender;
							let tdMobile = document.createElement("td");
							tdMobile.innerHTML = farmer.mobile_number;
							let tdProvince = document.createElement("td");
							tdProvince.innerHTML = farmer.province;
							let tdDistrict = document.createElement("td");
							tdDistrict.innerHTML = farmer.district;
							let input = document.createElement("input");
							input.type = "checkbox";
							input.name = "nrs[]";
							input.checked = true;
							input.classList.add("checkNr");
							input.value = farmer.mobile_number;
							tdNumber.append(validNumber ? input : "");
							tr.append(tdNumber);
							tr.append(tdFullname);
							tr.append(tdGender);
							tr.append(tdMobile);
							tr.append(tdProvince);
							tr.append(tdDistrict);
							content.appendChild(tr);
						});
					}
				}).catch((error) => {
					content.innerHTML = '';
					console.error(error)
				});
		}
		document.querySelector("#message").addEventListener("keyup", function(event) {
			totalCharsForSMS();
		})

		document.querySelector("#checkAll").addEventListener("click", function(event) {
			let element = event.target;
			let status = element.checked;
			document.querySelectorAll(".checkNr").forEach(check => {
				check.checked = status;
			})
		});

		function totalCharsForSMS() {
			let chars = document.querySelector("#message").value;
			document.querySelector("#totalChs").innerText = chars.length;
		}

		document.querySelector("#btnsendSMSToFarmers").addEventListener("click", function(event) {
			event.preventDefault();
			let _this = event.target;
			let oldText = _this.innerHtml;
			let formData = new FormData(sendSMSToFarmersForm);
			let url = "{{ route('/farmers/sms/send') }}";
			_this.innerHtml = "Please wait ...";
			_this.classList.add("disabled");
			fetch(url, {
					method: "POST",
					body: formData,
				}).then(resp => resp.json())
				.then(resp => {
					if (resp.status === "error") {
						$("#smsFarmerAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${resp.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
					} else {
						$("#smsFarmerAlert").empty().html(`<div class="alert alert-success alert-dismissible fade show" role="alert">Message: <span>${resp.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						sendSMSToFarmersForm.reset();
					}
				}).catch((error) => {
					$("#smsFarmerAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);

					console.error(error)
				}).finally(() => {
					_this.innerHtml = oldText;
					_this.classList.remove("disabled");
				})
		})
	});
</script>
{% endblock %}