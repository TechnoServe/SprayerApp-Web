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
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-buttons/css/buttons.bootstrap4.css') ?>">
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
	<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css"> -->


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
	</style>


	<!-- Custom styles for this template -->
	<link href="<?php echo asset('css/dashboard.css') ?>" rel="stylesheet">
</head>

<body>
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
			<div class="nav-item text-nowrap">
				<a class="nav-link px-3" href="<?php echo route('/logout') ?>">Log Out</a>
			</div>
		</div>
	</header>
	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
				<div class="position-sticky pt-3 mt-5">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link" aria-current="page" href="<?php echo route('/') ?>">
								<span data-feather="home"></span>
								Report Dashboard
							</a>
						</li>
					</ul>

					<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
						<span>Administration Dashboard</span>
						<a class="link-secondary" href="#" aria-label="Add a new report">
							<span data-feather="plus-circle"></span>
						</a>
					</h6>
					<ul class="nav flex-column mb-2">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/users') ?>">
								<span data-feather="users"></span>
								Users
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/profiles') ?>">
								<span data-feather="file-text"></span>
								Profiles
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/farmers') ?>">
								<span data-feather="file-text"></span>
								Farmers
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/plots') ?>">
								<span data-feather="file-text"></span>
								Plots
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/applications') ?>">
								<span data-feather="file-text"></span>
								Applications
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/equipments') ?>">
								<span data-feather="file-text"></span>
								Equipments
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/acquisitions') ?>">
								<span data-feather="file-text"></span>
								Acquisitions
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/incomes') ?>">
								<span data-feather="file-text"></span>
								Expenses / Incomes
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/logs') ?>">
								<span data-feather="file-text"></span>
								Logs
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				

<nav aria-label="breadcrumb" class="mt-2">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="<?php echo route('/farmers') ?>">Farmers</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="farmerAlert"></div>

<form name="saveFarmer" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="firstName" class="form-label">First name <span class="text-danger">*</span></label>
			<input type="text" class="form-control" id="firstName" value="<?php if(!empty($farmer['first_name'])): ?><?php echo $farmer['first_name'] ?><?php endif; ?>">
		</div>

		<div class="col-sm-6">
			<label for="lastName" class="form-label">Last name <span class="text-danger">*</span></label>
			<input type="text" class="form-control" id="lastName" value="<?php if(!empty($farmer['last_name'])): ?><?php echo $farmer['last_name'] ?><?php endif; ?>">
		</div>

		<div class="col-sm-6">
			<label for="birth-date" class="form-label">Gender <span class="text-danger">*</span></label>
				<select class="form-select" id="gender">
					<option value="Female" <?php if(!empty($farmer['gender']) && $farmer['gender'] == 'Female'): ?> <?php echo 'selected' ?> <?php endif; ?>>Female</option>
					<option value="Male" <?php if(!empty($farmer['gender']) && $farmer['gender'] == 'Male'): ?> <?php echo 'selected' ?> <?php endif; ?>>Male</option>
			</select>
		</div>

		<div class="col-sm-6">
			<label for="birth-date" class="form-label">Birth date <span class="text-danger">*</span></label>
			<input type="date" class="form-control" id="birthDate" value="<?php if(!empty($farmer['birth_date'])): ?><?php echo $farmer['birth_date'] ?><?php endif; ?>">
		</div>

		<div class="col-sm-4">
			<label for="province" class="form-label">Province <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="province">
				<option option="-1" disabled>Inform the province</option>
				<?php foreach(["Cabo Delgado", "Zambezia", "Nampula"] as $province): ?>
				<option value="<?php echo $province ?>" <?php if(!empty($farmer['province']) && $farmer['province'] == $province): ?> <?php echo 'selected' ?> <?php endif; ?>><?php echo $province ?></option>
				<?php endforeach; ?>
				
			</select>
		</div>

		<div class="col-sm-4">
			<label for="district" class="form-label">District <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="district"></select>
		</div>

		<div class="col-sm-4">
			<label for="administrative-post" class="form-label">Administrative post</label>
			<select class="form-select form-select-lg select2" id="administrativePost"></select>
		</div>

		<div class="col-sm-6">
			<label for="email" class="form-label">Email <span class="text-muted">(Optional)</span></label>
			<input type="email" class="form-control" id="email" value="<?php if(!empty($farmer['email'])): ?><?php echo $farmer['email'] ?><?php endif; ?>">
		</div>

		<div class="col-sm-6">
			<label for="mobileNumber" class="form-label">Mobile number </label>
			<input type="text" class="form-control" id="mobileNumber" value="<?php if(!empty($farmer['mobile_number'])): ?><?php echo $farmer['mobile_number'] ?><?php endif; ?>">
		</div>

	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveFarmer" type="submit">Save</button>
</form>

<script src="<?php echo asset('scripts/location.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {

		triggerChangeProvince();

		function triggerChangeProvince() {
			$("#province").trigger("change");

			$("#district").val("<?php echo $farmer['district'] ?>");
		}

		$("#btnSaveFarmer").click(function(e) {
			try{
						e.preventDefault();

						let _this = $(this);
						let oldText = _this.text();

						let form = $("form[name='saveFarmer']");
						let firstName = $("#firstName").val();
						let lastName = $("#lastName").val();
						let birthDate = $("#birthDate").val();
						let email = $("#email").val();
						let mobileNumber = $("#mobileNumber").val();
						let province = $("#province").val();
						let district = $("#district").val();
						let administrativePost = $("#administrativePost").val();
						let gender = $("#gender").val();
						$.ajax({
							url: "<?php echo route('/farmers/save/'.$farmer['id']) ?>",
							method: "POST",
							dataType: "JSON",
							data: {
								first_name: firstName,
								last_name: lastName,
								birth_date: birthDate,
								email: email,
								mobile_number: mobileNumber,
								province: province,
								district: district,
								administrative_post: administrativePost,
								gender: gender
							},
							success: function(response) {
								if (response.status == "success") {
									_this.text("Save").removeClass("disabled");
									$("#farmerAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Farmer saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
								}else{
									$("#farmerAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
								}
							},
							error : function(error, status){
								console.log('Error:',error.status, error.statusText);
							},
							beforeSend: function() {
								$("#farmerAlert").empty();
								_this.text("Please wait").addClass("disabled");
							},
							complete: function(XHR, status){
								_this.text(`${oldText} `).removeClass("disabled");					
							}
						})
					}catch(error){
						$("#farmerAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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


			</main>
		</div>
	</div>
	<script src="<?php echo asset('vendors/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/select2/js/select2.full.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
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
	<!-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/jszip.min.js') ?>"></script>
	<script src="<?php echo asset('vendors/datatables-buttons/js/pdfmake.min.js') ?>"></script> -->
</body>

</html>

