{% extends views/layouts/Default.php %}

{% block content %}

<input type="hidden" id="url" data-url="{{ route('') }}">

<div class="row mb-2 mt-3" id="loadFilter">
	<!--Filter widget-->
</div>

<div class="row">
	<div class="col-md-4 mt-2 mb-2" id="activeSprayersUsers"></div>
	<div class="col-md-4 mt-2 mb-2" id="registeredFarmers"></div>
	<div class="col-md-4 mt-2 mb-2" id="assistedFarmers"></div>
</div>

<div class="row mb-2">
	<div class="col-md-5 mt-2 mb-2" id="numberOfTreesSprayedPerApplication"></div>
	<div class="col-md-7 mt-2 mb-2" id="chemicalProvenance"></div>
</div>

<div class="row mb-2">
	<div class="col-md-4 mt-2 mb-2" id="rcnCollected"></div>
	<div class="col-md-4 mt-2 mb-2" id="rcnCollectedPerSprayers"></div>
	<div class="col-md-4 mt-2 mb-2" id="netIncomePerSprayers"></div>
</div>

<div class="mb-5" id="loadDataTable">
	<link href="{{ asset('vendors/query-builder/css/query-builder.default.min.css') }}" rel="stylesheet">
	<link href="{{ asset('vendors/query-builder/css/jquery-ui.min.css') }}" rel="stylesheet">


	<script src="{{ asset('vendors/query-builder/js/query-builder.standalone.js') }}"></script>

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

<script src="{{ asset('scripts/dashboard.js') }}"></script>

{% endblock %}