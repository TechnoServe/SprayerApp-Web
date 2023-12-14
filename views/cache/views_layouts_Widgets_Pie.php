<script src="<?php echo asset('vendors/ploty/js/plotly-2.9.0.min.js') ?>"></script>
<div id="<?php echo $id; ?>" class="shadow-lg mb-2"></div>
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
        title: "<?php echo $title; ?>"
    };

    Plotly.newPlot("<?php echo $id; ?>", data, layout, {
        responsive: true
    });
</script>