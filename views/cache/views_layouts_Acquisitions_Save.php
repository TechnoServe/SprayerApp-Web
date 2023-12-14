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
							<a class="nav-link" href="<?php echo route('/payments') ?>">
								<span data-feather="file-text"></span>
								Payments
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/agreements') ?>">
								<span data-feather="file-text"></span>
								Agreement
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo route('/dropdowns') ?>">
								<span data-feather="file-text"></span>
								Dropdowns
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
		<li class="breadcrumb-item"><a class="display-6" style="text-decoration: none;" href="<?php echo route('/acquisitions') ?>">Acquisitions</a></li>
		<li class="breadcrumb-item active display-6" aria-current="page">Save</li>
	</ol>
</nav>
<div id="equipmentAlert"></div>

<form name="saveAcquisition" id="saveAcquisition" class="">
	<div class="row g-3">
		<div class="col-sm-6">
			<label for="user_name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
			<input type="hidden" name="user_uid" class="form-control" id="user_uid" value="<?php echo $acquisitions['user_uid']; ?>">
			<input type="text" name="user_name" class="form-control" id="user_name" value="<?php echo $user['first_name'].' '.$user['last_name']; ?>" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="chemical_acquisition_uid" class="form-label">Acquisition id <span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="chemical_acquisition_uid" id="chemical_acquisition_uid" value="<?php echo $acquisitions['chemical_acquisition_uid']; ?>" readonly="readonly">
		</div>

		<div class="col-sm-6">
			<label for="chemical_name" class="form-label">Chemical Name <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="chemical_name" name="chemical_name">
				<?php foreach($chemicals as $chemical): ?>
				<option value="<?php echo $chemical ?>" <?php echo $acquisitions['chemical_name'] == $chemical ? 'selected' : '' ?>><?php echo $chemical ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="col-sm-6">
			<label for="chemical_quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
			<input type="number" min="0" step="any" class="form-control" name="chemical_quantity" id="chemical_quantity" value="<?php echo $acquisitions['chemical_quantity']; ?>">
		</div>

		<div class="col-sm-6">
			<label for="chemical_price" class="form-label">Price <span class="text-danger">*</span></label>
			<input type="number" min="0" step="any" class="form-control" name="chemical_price" id="chemical_price" value="<?php echo $acquisitions['chemical_price']; ?>">
		</div>
		
		<div class="col-sm-6">
			<label for="acquired_at" class="form-label">Acquisition Date </label>
			<input type="date" class="form-control" name="acquired_at" id="acquired_at" value="<?php echo $acquisitions['acquired_at']; ?>">
		</div>

		<div class="col-sm-6">
			<label for="chemical_acquisition_mode" class="form-label">Acquisition Mode <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="chemical_acquisition_mode" name="chemical_acquisition_mode">
				<?php foreach($modes as $mode): ?>
				<option value="<?php echo $mode ?>" <?php echo $acquisitions['chemical_acquisition_mode'] == $mode ? 'selected' : '' ?>><?php echo $mode ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="col-sm-6">
			<label for="chemical_name" class="form-label">Supplier <span class="text-danger">*</span></label>
			<select class="form-select form-select-lg select2" id="chemical_supplier" name="chemical_supplier">
				<?php foreach($suppliers as $supplier): ?>
				<option value="<?php echo $supplier ?>" <?php echo $acquisitions['chemical_supplier'] == $supplier ? 'selected' : '' ?>><?php echo $supplier ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<button class="btn btn-primary btn-md mt-3" id="btnSaveAcquisition" type="submit">Save</button>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$("#btnSaveAcquisition").click(function(e) {
			try{
				e.preventDefault();
				let formData = $("#saveAcquisition").serialize();
				// console.log(formData); return;
				let _this = $(this);
				let oldText = _this.text();
				$.ajax({
					url: "<?php echo route('/acquisitions/save/'.$acquisitions['id']) ?>",
					method: "POST",
					dataType: "JSON",
					data: formData,
					success: function(response) {
						if (response.status == "success") {
							_this.text("Save").removeClass("disabled");
							$("#equipmentAlert").empty().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><span>Acquisition saved successfully</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						}else{
							$("#equipmentAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert"><span>${response.message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
						}
					},
					error : function(error, status){
						console.log('Error:',error.status, error.statusText);
					},
					beforeSend: function() {
						$("#equipmentAlert").empty();
						_this.text("Please wait").addClass("disabled");
					},
					complete: function(XHR, status){
						_this.text(`${oldText} `).removeClass("disabled");					
					}
				})
			}catch(error){
				$("#equipmentAlert").empty().html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">Page error: <span>${error}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
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
</body>

</html>

