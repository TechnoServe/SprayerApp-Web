<script src="<?php echo asset('vendors/ploty/js/plotly-2.9.0.min.js') ?>"></script>
<div id="<?php echo $id ?>" class="shadow-lg mb-2"></div>
<script>
var dataSourceChemical = [];
<?php foreach($source as $values): ?>
    dataSourceChemical.push({
       x: [<?php echo $values["Chemical Name"] ?>],
        y: [<?php echo $values["Quantity"] ?>],
        name: '<?php echo $values["Chemical Supplier"] ?>',
        type: 'bar' 
    });
<?php $i++; endforeach; ?>

    var layout = {
        title: "<?php echo $title; ?>",
        barmode: 'stack'
    };

    Plotly.newPlot('<?php echo $id ?>', dataSourceChemical, layout);
</script>