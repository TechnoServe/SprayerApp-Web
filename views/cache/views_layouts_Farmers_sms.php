<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Sprayer app for private sector">
	<meta name="generator" content="Technoserve">
	<title>Sprayer App | <?php echo $title ?? "Home" ?></title>

	<!-- Bootstrap core CSS -->
	
	<link href="<?php echo asset('vendors/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset('vendors/fontawesome/css/font-awesome.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset('vendors/select2/css/select2.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset('vendors/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>" rel="stylesheet">
	<!-- Stylesheet for datatable -->
<!-- 	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-buttons/css/buttons.bootstrap4.css') ?>">
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-responsive/css/responsive.bootstrap4.min.css') ?>"> -->

	<!-- online cdn -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.6.2/css/colReorder.bootstrap4.min.css">


	<link rel="stylesheet" href="<?php echo asset('css/switch-style.css') ?>">

	<script src="<?php echo asset('vendors/jquery/jquery.min.js') ?>"></script>
	<style>
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
		}
		.select2{
			width: 100%;
		}

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
				font-size: 3.5rem;
			}
		}
		.pointer{
			cursor: pointer;
		}
		.activeLink, .activeGroup{
			color: blue;
			text-transform: capitalize;
		}
	</style>


	<!-- Custom styles for this template -->
	<link href="<?php echo asset('css/dashboard.css') ?>" rel="stylesheet">
</head>

<body>
	<?php
		$activeGroup = $_SESSION["activeGroup"] ?? "dashboard";
		$activeLink = $_SESSION["activeLink"] ?? "dashboard";
		$color = "blue";
	?>
	<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
		<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">
		<span class="fs-4"><?php echo $_SESSION["user"]["first_name"] ?></span><br />
			<span class="fs-6"><?php echo $_SESSION["user"]["profile"] ?></span>
		</a>
		<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<input class="form-control form-control-dark w-100" type="hidden" placeholder="Search" style="background-color:#e9ecef" aria-label="Search the Dashboard">
		<div class="navbar-nav">
			<div class="nav-item text-nowrap"><br /><br />
				<a class="nav-link mx-md-3 px-3" href="<?php echo route('/logout') ?>">Log Out</a>
			</div>
		</div>
	</header>
	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
				<div class="position-sticky pt-3 mt-5">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link" aria-current="page" href="<?php echo route('/') ?>" style="color:<?php echo $activeGroup=='dashboard' ? $color : ''; ?>">
								<span data-feather="home"></span>
								Report Dashboard
							</a>
						</li>
					</ul>

					<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted d-none">
						<span>Administration Dashboard</span>
						<a class="link-secondary" href="#" aria-label="Add a new report">
							<span data-feather="plus-circle"></span>
						</a>
					</h6>
					<ul class="nav flex-column mb-2">
						<li class="nav-item" >
							<a class="nav-link" href="#data_section" data-bs-toggle="collapse"  style="color:<?php echo $activeGroup=='data' ? $color : ''; ?>" ><strong>Data</i></strong></a>
							<ul class="nav-item collapse <?php echo in_array($activeGroup, ['finances','data']) ? 'show' : ''; ?>" id="data_section">
								<li data-route="<?php echo route('/farmers') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='farmers' ? $color : ''; ?>">Farmers</li>
								<li data-route="<?php echo route('/plots') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='plots' ? $color : ''; ?>">Plots</li>
								<li data-route="<?php echo route('/applications') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='applications' ? $color : ''; ?>">Applications</li>
								<li data-route="<?php echo route('/equipments') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='equipments' ? $color : ''; ?>">Equipments</li>
								<li data-route="<?php echo route('/acquisitions') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='acquisitions' ? $color : ''; ?>">Chemical acquisitions</li>

								<li class="nav-item">
									<a class="nav-link" href="#finances_section" data-bs-toggle="collapse" style="color:<?php echo $activeGroup=='finances' ? $color : ''; ?>"><strong>Finances</strong></a>
									<ul class="nav-item collapse <?php echo $activeGroup=='finances' ? 'show' : ''; ?>" id="finances_section">
										<li data-route="<?php echo route('/payments') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='payments' ? $color : ''; ?>">Payments</li>
										<li data-route="<?php echo route('/incomes') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='incomes' ? $color : ''; ?>">Expenses / Incomes</li>
										<li data-route="<?php echo route('/agreements') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='agreements' ? $color : ''; ?>">Agreements</li>
									</ul>
								</li>
								<li data-route="<?php echo route('/acquisitions') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='acquisitions' ? $color : ''; ?>">Chemical acquisitions</li>
							</ul>
						</li>
						<?php if(!($_SESSION['user']['isSeller'] ?? true)): ?>
						<li class="nav-item" >
							<a class="nav-link" href="#settings_section" data-bs-toggle="collapse"  style="color:<?php echo $activeGroup=='admin' ? $color : ''; ?>"><strong>Admin Features</i></strong></a>
							<ul class="nav-item collapse <?php echo $activeGroup=='admin' ? 'show' : ''; ?>" id="settings_section">
								<li data-route="<?php echo route('/users') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='users' ? $color : ''; ?>">Users</li>
								<li data-route="<?php echo route('/profiles') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='profiles' ? $color : ''; ?>">Profiles</li>
								<li data-route="<?php echo route('/dropdowns') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='dropdowns' ? $color : ''; ?>">Dropdowns</li>
								<li data-route="<?php echo route('/faqs') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='faqs' ? $color : ''; ?>">FAQs</li>
								<li data-route="<?php echo route('/logs') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='logs' ? $color : ''; ?>">Logs</li>
								<li data-route="<?php echo route('/requests') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='requests' ? $color : ''; ?>">Requests</li>
								<li data-route="<?php echo route('/campaigns') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='campaigns' ? $color : ''; ?>">Campaign Management</li>
								<li data-route="<?php echo route('/farmers/sms') ?>" class="nav-item pointer" style="color:<?php echo $activeLink=='smsalerts' ? $color : ''; ?>">SMS Alert</li>
							</ul>
						</li>
								<li class="nav-item"></li>
						<?php endif ?>
					</ul>
				</div>
			</nav>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="<?php echo route('/farmers') ?>">Farmers</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">SMS Alert</li>
	</ol>
</nav>
<div id="smsFarmerAlert"></div>

<form name="sendSMSToFarmersForm" id="sendSMSToFarmersForm" class="">

	<div class="row g-3">
		<div class="col-sm-12">
			<div class="form-group">
				<label>Province</label>
				<select class="form-control <?php echo $_SESSION['user']['isSeller'] ? '' : 'select2'; ?> province" name="province[]" id="province" style="width: 100%;" <?php echo $_SESSION['user']['isSeller'] ? '' : 'multiple'; ?>>
					<?php if($_SESSION['user']['isSeller']): ?>
					<option value="<?php echo $_SESSION['user']['province'] ?>" selected><?php echo $_SESSION['user']['province'] ?></option>

					<?php else: ?>
					<option option="-1" disabled>Inform the province</option>
					<option value="Cabo Delgado">Cabo Delgado</option>
					<option value="Zambezia">Zambezia</option>
					<option value="Nampula">Nampula</option>
					<?php endif; ?>
				</select>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<label>District</label>
				<select class="form-control   <?php echo $_SESSION['user']['isSeller'] ? '' : 'select2'; ?> district" name="district[]" id="district" style="width: 100%;" <?php echo $_SESSION['user']['isSeller'] ? '' : 'multiple'; ?>>
					<?php if($_SESSION['user']['isSeller']): ?>
					<option value="<?php echo $_SESSION['user']['district'] ?>" selected><?php echo $_SESSION['user']['district'] ?></option>

					<?php else: ?>
					<option option="-1" disabled>District</option>
					<?php endif; ?>

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

<script src="<?php echo asset('scripts/location.js') ?>" type="text/javascript"></script>


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

			$("#district").val("<?php echo $farmer['district'] ?>");

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
			let url = "<?php echo route('/farmers/sms/get-geo-farmers') ?>";
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
			let url = "<?php echo route('/farmers/sms/send') ?>";
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

			</main>
		</div>
	</div>
	<script src="<?php echo asset('vendors/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/select2/js/select2.full.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables/jquery.dataTables.min.js') ?>"></script>
	<!-- <script src="<?php echo asset('vendors/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/dataTables.buttons.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/buttons.bootstrap4.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/buttons.print.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/jszip.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/pdfmake.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/vfs_fonts.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/buttons.html5.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/dataTables.colReorder.min.js') ?>"></script> 
	<script src="<?php echo asset('vendors/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
	 -->
	<!-- online cdn -->
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.js" integrity="sha512-3FKAKNDHbfUwAgW45wNAvfgJDDdNoTi5PZWU7ak3Xm0X8u0LbDBWZEyPklRebTZ8r+p0M2KIJWDYZQjDPyYQEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
	<script>
		let pointers = document.querySelectorAll(".pointer");
		for(var i = 0; i < pointers.length;i++){
			let pointer = pointers[i];

			pointer.addEventListener("click", function(e){
				e.preventDefault();
				location.href= pointer.dataset.route;
			})
		}
		
		// let accordions = document.querySelectorAll(".accordion");
		// accordions.addEventListener("click", function(e){
		// 	e.preventDefault();
		// 	let icon = this.dataset.icon;
		// 	let eleIcon = document.querySelector("#"+icon);
		// 	eleIcon.classList.toggle("fa-minus");
		// })

	</script>
</body>

</html>

