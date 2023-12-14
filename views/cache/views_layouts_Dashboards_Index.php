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
				

<input type="hidden" id="url" data-url="<?php echo route('') ?>">

<div class="row mb-2 mt-3" id="loadFilter">
	<!--Filter widget-->
</div>

<div class="row">
	<div class="col-md-4 mt-2 mb-2" id="activeSprayersUsers"></div>
	<div class="col-md-4 mt-2 mb-2" id="registeredFarmers"></div>
	<div class="col-md-4 mt-2 mb-2" id="assistedFarmers"></div>
</div>

<div class="row mb-2">
	<div class="col-md-6 mt-2 mb-2" id="numberOfTreesSprayedPerApplication"></div>
	<div class="col-md-6 mt-2 mb-2" id="genderDistribution"></div>
</div>

<div class="row mb2">
	<div class="col-md-12 mt-2 mb-2" id="chemicalProvenance"></div>
</div>

<div class="row mb-2">
	<div class="col-md-4 mt-2 mb-2" id="rcnCollected"></div>
	<div class="col-md-4 mt-2 mb-2" id="rcnCollectedPerSprayers"></div>
	<div class="col-md-4 mt-2 mb-2" id="netIncomePerSprayers"></div>
</div>

<div class="mb-5" id="loadDataTable">
	<link href="<?php echo asset('vendors/query-builder/css/query-builder.default.min.css') ?>" rel="stylesheet">
	<link href="<?php echo asset('vendors/query-builder/css/jquery-ui.min.css') ?>" rel="stylesheet">


	<script src="<?php echo asset('vendors/query-builder/js/query-builder.standalone.js') ?>"></script>

	<div class="card card-cover h-100 overflow-hidden text-black rounded-5 shadow-lg">
		<div class="card-body">
			<div class="accordion mb-5" id="accordionExample">
				<div class="accordion-item">
					<h4 class="accordion-header" id="headingOne">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
							Advanced query
						</button>
					</h4>
					<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
						<div class="accordion-body">
							<div class="row">
								<div class="mb-2 col-lg-12">
									<div class="mb-10">
										<div class="custom-control custom-switch ml-auto">
											<input type="checkbox" class="custom-control-input form-check-input" id="save-query-switch">
											<label class="custom-control-label" for="save-query-switch">Save query</label>
										</div>
										<div id="builder-basic"></div>
										<button class="btn btn-info btn-md btn-get mt-3" id="btn-get">Filter parameters</button>
										<button class="btn btn-success btn-md btn-get mt-3" id="btn-save-query" style="display: none">Save query</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive table-responsive-lg">
				<table id="result" class="table table-striped table-condensed table-sm nowrap table-bordered" style="width: 100%;">
					<thead>
						<tr>
							<th rowspan="2">Sprayer name</th>
							<th rowspan="2">Gender</th>
							<th rowspan="2">Phone number</th>
							<th rowspan="2">Administrative posts</th>
							<th rowspan="2">District</th>
							<th rowspan="2">Province</th>
							<th rowspan="2">Equipments</th>
							<th colspan="3" class="text-center">Trees sprayed</th>
							<th rowspan="2">Farmers assisted</th>
							<th rowspan="2">RCN Collected (KG)</th>
							<th rowspan="2">Cash Collected (MZN)</th>
							<th rowspan="2">Fuel Cost (MZN)</th>
							<th rowspan="2">Employee Costs (MZN)</th>
							<th rowspan="2">Chemical Cost (MZN)</th>
							<th rowspan="2">Other Costs (MZN)</th>
							<th rowspan="2">Last sync. Date</th>
						</tr>
						<tr>
							<th>1st application</th>
							<th>2nd application</th>
							<th>3rd application</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th class="filterFooter">Sprayer name</th>
							<th class="filterFooter">Gender</th>
							<th class="filterFooter">Phone number</th>
							<th class="filterFooter">Administrative posts</th>
							<th class="filterFooter">District</th>
							<th class="filterFooter">Province</th>
							<th class="filterFooter">Equipments</th>
							<th class="">1st application</th>
							<th class="">2nd application</th>
							<th class="">3rd application</th>
							<th class="">Farmers assisted</th>
							<th class="">RCN Collected (KG)</th>
							<th class="">Cash Collected (MZN)</th>
							<th class="">Fuel Costs (MZN)</th>
							<th class="">Employee Costs (MZN)</th>
							<th class="">Chemical Cost (MZN)</th>
							<th class="">Other Costs (MZN)</th>
							<th class="">Last sync. Date</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	<script></script>

	<style>
		div.dataTables_wrapper div.dataTables_processing {
			position: absolute;
			width: 100% !important;
			height: 100% !important;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			opacity: 0.9;
			margin: 0 !important;
		}
	</style>
</div>

<script src="<?php echo asset('scripts/dashboard.js') ?>"></script>


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

