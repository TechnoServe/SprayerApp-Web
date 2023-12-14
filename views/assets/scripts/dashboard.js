$(document).ready(function() {
    'use strict';

    let overlayLoading = '<div class="overlay-wrapper"><div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2"> Loading... </div></div></div>';

    let url = $("#url").data("url");
    let totalRecordsFiltered = $("#totalRecordsFiltered");
    let lenghtReturned = 1000000;
    let table;
    loadFilter();
    init();
    dataTableData({});


    function init(data = {}) {
        activeSprayersUsers(data);
        registeredFarmers(data);
        assistedFarmers(data);
        rcnCollected(data);
        rcnCollectedPerSprayers(data);
        netIncomePerSprayers(data);
        numberOfTreesSprayedPerApplication(data);
        chemicalProvenance(data);
        genderDistribution(data);
    }

    
    function loadFilter() {
        callAjax('loadFilter', {}, {
            "container": "#loadFilter"
        });

    }

    function activeSprayersUsers(data = {}) {
        callAjax('activeSprayersUsers', data, {
            "container": "#activeSprayersUsers"
        });
    }

    function registeredFarmers(data = {}) {
        callAjax('registeredFarmers', data, {
            "container": "#registeredFarmers"
        });
    }

    function assistedFarmers(data = {}) {
        callAjax('assistedFarmers', data, {
            "container": "#assistedFarmers"
        });
    }

    function rcnCollected(data = {}) {
        callAjax('rcnCollected', data, {
            "container": "#rcnCollected"
        });
    }

    function rcnCollectedPerSprayers(data = {}) {
        callAjax('rcnCollectedPerSprayers', data, {
            "container": "#rcnCollectedPerSprayers"
        });
    }

    function netIncomePerSprayers(data = {}) {
        callAjax('netIncomePerSprayers', data, {
            "container": "#netIncomePerSprayers"
        });
    }

    function numberOfTreesSprayedPerApplication(data = {}) {
        callAjax('numberOfTreesSprayedPerApplication', data, {
            "container": "#numberOfTreesSprayedPerApplication"
        });
    }

    function chemicalProvenance(data = {}) {
        callAjax('chemicalProvenance', data, {
            "container": "#chemicalProvenance"
        });
    }

    
    function genderDistribution(data = {}) {
        callAjax('genderDistribution', data, {
            "container": "#genderDistribution"
        });
    }

    function callAjax(uri, data = {}, elements = {
        container: "",
    }) {

        let loading = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

        let container = $(elements.container);
        if(container === undefined) return;
        
        $.ajax({
            url: " " + url + "dashboards/" + uri + "",
            method: "POST",
            dataType: "JSON",
            data: data,
            success: function(response) {
                container.empty().html(response.output);
            },
            beforeSend: function() {
                container.empty().html(loading);
            },
        });
    }

    function exportData(e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function(e, s, data) {
            data.start = 0;
            data.length = lenghtReturned;
            dt.one('preDraw', function(e, settings) {
                if (button[0].className.indexOf('buttons-copy') >= 0) {
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                    $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                    $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                    $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-print') >= 0) {
                    $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function(e, s, data) {
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });
                //setTimeout(dt.ajax.reload, 0);
                return false;
            });
        });
        dt.ajax.reload();
    };

    function buildTitle(){
        let title = `Sprayer Performance`;
        let provinces = document.querySelector("#province");
        let districts = document.querySelector("#district");
        let dateFrom = document.querySelector("#dateFrom");
        let dateTo   = document.querySelector("#dateTo");
        title += provinces != null ? " "+( Array.isArray($("#province").val()) ?  $("#province").val().join(" and ") :  $("#province").val()) : "";
        title += (districts != null ? " "+( Array.isArray($("#district").val()) ?  $("#district").val().join(" and ") :  $("#district").val()) : "");
        title += " from "+(dateFrom != null && dateFrom.value != "" ? $("#dateFrom").val() : "2020-01-01");
        title += " to "+(dateTo != null  && dateTo.value != "" ? $("#dateTo").val() : (new Date).toISOString().substring(0,10));
        return title;
        
    }

    function dataTableData(request = {}) {
        let exportTitle = buildTitle();
        
        table = $('#result').DataTable({
            'language': {
                "sProcessing": overlayLoading,
                "sSearch": '<i class="icon-search"></i>',
            },
            "lengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "processing": true,
            "serverSide": true,
            "responsive": false,
            "dom": "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 mb-3'f><'col-sm-12 col-md-12'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Exportar em Excel',
                    className: 'btn btn-info btn-md mb-2',
                    action: exportData,
                    autoFilter: true,
                    sheetName: 'tabular_data',
                    title: exportTitle,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar em Pdf',
                    className: 'btn btn-info btn-md mb-2',
                    action: exportData,
                    title: exportTitle,
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Exportar em Csv',
                    className: 'btn btn-info btn-md mb-2',
                    action: exportData,
                    title: exportTitle,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Colunas',
                    className: 'btn btn-info btn-md mb-2',
                    visibility: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
            ],
            "ajax": {
                type: "POST",
                url: "" + url + "dashboards/sprayers/list-all",
                data: request,
            },
            "deferRender": true,
            drawCallback: function() {
                var api = this.api();
                var numRows = api.page.info().recordsTotal;
                var recordsDisplayed = api.page.info().recordsDisplay;
                lenghtReturned = recordsDisplayed;
                totalRecordsFiltered.text(recordsDisplayed.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
            }
        });

        $('#result tfoot th.filterFooter').each(function() {
            let $this = $(this);
            let title = $this.text();

            $this.html('<input type="text" class="form-control" placeholder="Search">');
        });

        table.columns().every(function() {
            let that = this;

            $('input', this.footer()).on("keyup change", function(e) {
                let keycode = e.which || e.keycode;
//                if (keycode == 13) {
                    if (this.value.length >= 0) {
                        if (that.search !== this.value) {
                            that.search(this.value).draw();
                        }
                    }
 //               }
            });
        });
    }

    $('#btn-get').on('click', function() {
        let result = $('#builder-basic').queryBuilder('getSQL');
        let selectedOption = [];
        let province = $("#province").val();
        let district = $("#district").val();
        let dateFrom = $("#dateFrom").val();
        let dateTo = $("#dateTo").val();

        $(".saved-query").each(function() {
            let $this = $(this);
            if ($this.is(":checked")) {
                selectedOption.push($this.val());
            }
        });

        if (result != null) {
            if (selectedOption.length > 0) {
                result.sql = result.sql + " AND " + selectedOption.join(" AND ");
            }

            result.province = province;
            result.district = district;
            result.dateFrom = dateFrom;
            result.dateTo = dateTo;

            table.destroy();
            dataTableData(result);
        } else if (result == null) {
            if (selectedOption.length > 0) {
                let _result = {
                    "sql": selectedOption.join(" AND "),
                    "province": province,
                    "district": district,
                    "dateFrom": dateFrom,
                    "dateTo": dateTo
                }

                table.destroy();
                dataTableData(_result);
            }
        }
    });

    $(document).on("click", "#filterButton", function(e) {
        e.preventDefault();

        let params = {
            "province": $("#province").val(),
            "district": $("#district").val(),
            "dateFrom": $("#dateFrom").val(),
            "dateTo": $("#dateTo").val(), "campaign": $("#campaign").val()
        };

        table.destroy();
        dataTableData(params);
        init(params);
    });

    $(document).on("click", "#resetFilterButton", function(e) {
        e.preventDefault();
        loadFilter();

        table.destroy();
        dataTableData({});
        init({});
    });

    //query builder
    $("#save-query-switch").change(function() {
        let btnSaveQuery = $("#btn-save-query");

        if ($(this).is(":checked")) {
            btnSaveQuery.css("display", "");
        } else {
            btnSaveQuery.css("display", "none");
        }
    });

    $('#builder-basic').queryBuilder({
        lang_code: "en-EN",
        filters: [{
                id: 'TreesSprayed',
                label: 'Trees sprayed',
                type: 'integer',
                operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal']
            },
            {
                id: 'FarmersAssisted',
                label: 'Farmers assisted',
                type: 'integer',
                operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal']
            },
        ]
    });
});