<script src="{{ asset('vendors/ploty/js/plotly-2.9.0.min.js') }}"></script>
<div id="{{$id;}}" class="shadow-lg mb-2"></div>
<script>
    var labs = <?php echo json_encode($labels); ?>;
    var vals = <?php echo json_encode($values); ?>;
    var data = [{
        values: vals,
        labels: labs,
        type: 'pie'
    }];

    var layout = {
        height: 450,
        width: 500,
        title: "{{$title;}}"
    };

    Plotly.newPlot("{{$id;}}", data, layout, {
        responsive: true
    });
</script>