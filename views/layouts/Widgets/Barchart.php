<script src="{{ asset('vendors/ploty/js/plotly-2.9.0.min.js') }}"></script>
<div id="{{ $id }}" class="shadow-lg mb-2"></div>

<script>

    var trace1 = {
        x: [{{ $trace1["dimensions"] }}],
        y: [{{ $trace1["metrics"] }}],
        type: 'bar'
    };

    var data = [trace1];

    var config = {
        responsive: false
    }

    var layout = {
        title: "{{ $title; }}",
        showlegend: false,
    };

    Plotly.newPlot("{{ $id }}", data, layout, config);
</script>