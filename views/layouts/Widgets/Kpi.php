<div class="card card-cover h-100 overflow-hidden text-white rounded-5 shadow-lg bg-info" data-toggle="tooltip" title="{{ $tooltipTitle; }}" data-placement="{{ $tooltipPosition; }}">
    <div class="d-flex flex-row h-100 p-1">
        <div class="text-black text-shadow-1 text-center d-inline-block align-middle">
            <span class="d-block display-6 fs-2 mb-3 kpi-title" id="">
                {{ $title; }}
            </span>
            <span class="d-block display-5 fw-bold kpi-agregation-value" id="">
                {{ $value; }}
            </span>

            {% if($progress[0] ?? null): %}
            <div class="progress">
                <div class="progress-bar  {{$progress[2]}} progress-bar-striped " style="width:{{$progress[1]}}%">{{$progress[1]}}%</div>
            </div>
            {% endif; %}
        </div>
    </div>
</div>
<style>

</style>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>