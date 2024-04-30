$(document).ready(function() {
    'use strict';

    let overlayLoading = '<div class="overlay-wrapper"><div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2"> Loading... </div></div></div>';

    let url = $("#url").data("url");
    let totalRecordsFiltered = $("#totalRecordsFiltered");
    let lenghtReturned = 1000000;
    let table;

    dataTableData();

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
        let title = `FAQs list`;
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

        $.fn.dataTable.ext.errMode = 'none';
        table = $('#result')
                .on( 'error.dt', function ( e, settings, techNote, message ) {
                   let errorMessage = `There has been a system issue - please refresh your page.\nIf this persist, contact the administrator.`;
                    console.log( errorMessage, message );
                    alert(errorMessage);
                    return;
                }).DataTable({
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
            "responsive": true,
            "dom": "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 mb-3'f><'col-sm-12 col-md-12'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Export in Excel',
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
                    text: 'Export in Pdf',
                    className: 'btn btn-info btn-md mb-2',
                    action: exportData,
                    title: exportTitle,
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export in Csv',
                    className: 'btn btn-info btn-md mb-2',
                    action: exportData,
                    title: exportTitle,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Columns',
                    className: 'btn btn-info btn-md mb-2',
                    visibility: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
            ],
            "ajax": {
                type: "POST",
                url: "" + url + "faqs/show",
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
                //console.log(this.value);
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
});