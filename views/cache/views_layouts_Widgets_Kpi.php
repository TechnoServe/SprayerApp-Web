<div class="card card-cover h-100 overflow-hidden text-white rounded-5 shadow-lg bg-info" data-toggle="tooltip" title="<?php echo $tooltipTitle; ?>" data-placement="<?php echo $tooltipPosition; ?>">
    <div class="d-flex flex-row h-100 p-1">
        <div class="text-black text-shadow-1 text-center d-inline-block align-middle">
            <span class="d-block display-6 fs-2 mb-3 kpi-title" id="">
                <?php echo $title; ?>
            </span>
            <span class="d-block display-5 fw-bold kpi-agregation-value" id="">
                <?php echo $value; ?>
            </span>

            <?php if($progress[0] ?? null): ?>
            <div class="progress">
                <div class="progress-bar  <?php echo $progress[2] ?> progress-bar-striped " style="width:<?php echo $progress[1] ?>%"><?php echo $progress[1] ?>%</div>
            </div>
            <?php endif; ?>
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