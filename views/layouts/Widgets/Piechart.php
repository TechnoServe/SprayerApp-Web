<script src="{{ asset('vendors/ploty/js/plotly-2.9.0.min.js') }}"></script>
<div id="myPlot1" class="shadow-lg mb-2"></div>
<script>
    {%
        $metrics = array_column($numberOfTreesSprayedPerSprayer, "numeroDePlantasTratadas");
        $metrics = implode($metrics, ",");

        $dimensions = array_column($numberOfTreesSprayedPerSprayer, "numeroDaAplicacao");
        $dimensions = implode($dimensions, ","); 
    %}
    
    var xArray = [{{
            $dimensions;
        }}];
        
    var yArray = [{{
            $metrics;
        }}];

    var data = [{
        labels: xArray,
        values: yArray,
        type: "pie"
    }];

    var config = {
        responsive: true
    }

    var layout = {
        title: "Number of trees sprayed per application",
    };

    Plotly.newPlot("myPlot1", data, layout, config);
</script>