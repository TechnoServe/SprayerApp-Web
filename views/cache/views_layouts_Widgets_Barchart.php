<script src="<?php echo asset('vendors/ploty/js/plotly-2.9.0.min.js') ?>"></script>
<div id="<?php echo $id ?>" class="shadow-lg mb-2"></div>

<script>

    var trace1 = {
        x: [<?php echo $trace1["dimensions"] ?>],
        y: [<?php echo $trace1["metrics"] ?>],
        type: 'bar'
    };

    var data = [trace1];

    var config = {
        responsive: false
    }

    var layout = {
        title: "<?php echo $title; ?>",
        showlegend: false,
    };

    Plotly.newPlot("<?php echo $id ?>", data, layout, config);
</script>