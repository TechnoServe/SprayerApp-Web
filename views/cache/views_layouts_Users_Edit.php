<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Sprayer app for private sector">
	<meta name="generator" content="Technoserve">
	<title>Sprayer app</title>

	<!-- Bootstrap core CSS -->
	<link href="<?php echo asset('vendors/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset('vendors/fontawesome/css/font-awesome.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset('vendors/select2/css/select2.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset('vendors/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-buttons/css/buttons.bootstrap4.css') ?>">
	<link rel="stylesheet" href="<?php echo asset('vendors/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

	<script src="<?php echo asset('vendors/jquery/jquery.min.js') ?>"></script>
	<style>
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
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
		<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Test user</a>
		<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
		<div class="navbar-nav">
			<div class="nav-item text-nowrap">
				<a class="nav-link px-3" href="<?php echo route('/logout') ?>">Log out</a>
			</div>
		</div>
	</header>
	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
				<div class="position-sticky pt-3">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link" aria-current="page" href="<?php echo route('/') ?>">
								<span data-feather="home"></span>
								Report Dashboard
							</a>
						</li>
					</ul>

					<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
						<span>User administration</span>
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
							<a class="nav-link" href="<?php echo route('/users/profiles') ?>">
								<span data-feather="file-text"></span>
								Profiles
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				

<link href="<?php echo asset('vendors/bootstrap/css/form-validation.css') ?>" rel="stylesheet">
<script src="<?php echo asset('vendors/bootstrap/js/form-validation.js') ?>"></script>

<h4 class="mt-3 mb-3">Fill the user information</h4>
<form class="needs-validation was-validated" novalidate="">
	<div class="row g-3">
		<div class="col-sm-4">
			<label for="firstName" class="form-label">First name</label>
			<input type="text" class="form-control" id="firstName" placeholder="" value="" required="">
			<div class="invalid-feedback">
				Valid first name is required.
			</div>
		</div>

		<div class="col-sm-4">
			<label for="lastName" class="form-label">Last name</label>
			<input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
			<div class="invalid-feedback">
				Valid last name is required.
			</div>
		</div>

		<div class="col-sm-4">
			<label for="birth-date" class="form-label">Birth date</label>
			<input type="date" class="form-control" id="birth-date" placeholder="" value="" required="">
			<div class="invalid-feedback">
				Valid birth date is required.
			</div>
		</div>

		<div class="col-6">
			<label for="email" class="form-label">Email <span class="text-muted">(Optional)</span></label>
			<input type="email" class="form-control" id="email" placeholder="you@example.com">
			<div class="invalid-feedback">
				Please enter a valid email address for shipping updates.
			</div>
		</div>

		<div class="col-6">
			<label for="contact" class="form-label">Contact <span class="text-muted">(username)</span></label>
			<input type="text" class="form-control" id="contact" placeholder="(+258) xx xx xx xxx" required="">
			<div class="invalid-feedback">
				Please enter your contact.
			</div>
		</div>

		<div class="col-md-4">
			<label for="province" class="form-label">Province</label>
			<select class="form-select" id="province" required="">
				<option value="">Choose...</option>
				<option>Maputo</option>
				<option>Gaza</option>
				<option>Inhembane</option>
			</select>
			<div class="invalid-feedback">
				Please select a valid province.
			</div>
		</div>

		<div class="col-md-4">
			<label for="district" class="form-label">District</label>
			<select class="form-select" id="district" required="">
				<option value="">Choose...</option>
				<option>Macomia</option>
			</select>
			<div class="invalid-feedback">
				Please provide a valid district.
			</div>
		</div>

		<div class="col-md-4">
			<label for="administrative-post" class="form-label">Administrative post</label>
			<select class="form-select" id="administrative-post" required="">
				<option value="">Choose...</option>
				<option>Macomia</option>
			</select>
			<div class="invalid-feedback">
				Please provide a valid Administrative post.
			</div>
		</div>

		<div class="col-md-4">
			<label for="profile" class="form-label">Profile</label>
			<select class="form-select" id="profile" required="">
				<option value="">Choose...</option>
				<option>Sprayer</option>
			</select>
			<div class="invalid-feedback">
				Please select a valid profile.
			</div>
		</div>

		<div class="col-4">
			<label for="password" class="form-label">Password</label>
			<input type="password" class="form-control" id="password" placeholder="">
			<div class="invalid-feedback">
				Please enter a valid password.
			</div>
		</div>

		<div class="col-4">
			<label for="confirm-password" class="form-label">Confirm Password</label>
			<input type="text" class="form-control" id="confirm-password" placeholder="" required="">
			<div class="invalid-feedback">
				The password does not match.
			</div>
		</div>
	</div>
	<button class="btn btn-primary btn-lg mt-3" type="submit">Save</button>
</form>


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
</body>

</html>


