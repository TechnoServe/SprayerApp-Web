<script src="{{ asset('vendors/ploty/js/plotly-2.9.0.min.js') }}"></script>
<div id="{{ $id }}" class="shadow-lg mb-2"></div>
<script>
var dataSourceChemical = [];
{% 
    foreach($source as $values):
%}
    dataSourceChemical.push({
       x: [{{ $values["Chemical Name"] }}],
        y: [{{ $values["Quantity"] }}],
        name: '{{ $values["Chemical Supplier"] }}',
        type: 'bar' 
    });
{% $i++; endforeach; %}

    var layout = {
        title: "{{ $title; }}",
        barmode: 'stack'
    };

    Plotly.newPlot('{{ $id }}', dataSourceChemical, layout);
</script>