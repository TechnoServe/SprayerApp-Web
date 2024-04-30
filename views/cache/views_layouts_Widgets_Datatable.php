<link href="<?php echo asset('vendors/query-builder/css/query-builder.default.min.css') ?>" rel="stylesheet">
<link href="<?php echo asset('vendors/query-builder/css/jquery-ui.min.css') ?>" rel="stylesheet">


<script src="<?php echo asset('vendors/query-builder/js/query-builder.standalone.js') ?>"></script>
<script src="<?php echo asset('vendors/query-builder/i18n/query-builder.pt-PT.js') ?>"></script>

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
                                        <label class="custom-control-label" for="save-query-switch">Salvar Pesquisa</label>
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
            <table id="result" class="table table-striped table-bordered table-condensed table-sm dt-responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Sprayer name</th>
                        <th>Administrative posts</th>
                        <th>District</th>
                        <th>Province</th>
                        <th>Trees sprayed</th>
                        <th>Farmers assisted</th>
                        <th>Phone number</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th class="filterFooter">Sprayer name</th>
                        <th class="filterFooter">Administrative posts</th>
                        <th class="filterFooter">District</th>
                        <th class="filterFooter">Province</th>
                        <th class="filterFooter">Trees sprayed</th>
                        <th class="filterFooter">Farmers assisted</th>
                        <th class="filterFooter">Phone number</th>
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