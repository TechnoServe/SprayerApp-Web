<form name="filterForm" method="GET">
    <div class="card card-primary card-outline collapsed-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="campaign">Campaign</label>
                        <select class="form-control" name="campaign" id="campaign">
                            <option value="All">All</option>
                            <?php foreach($campaigns ?? [] as $campaign): ?>
                            <option value="<?php echo $campaign['opening'].'='.$campaign['closing']; ?>"><?php echo $campaign["description"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Province</label>
                        <select class="form-control <?php echo $_SESSION['user']['isSeller'] ? '' : 'select2'; ?> province" name="province[]" id="province" style="width: 100%;"  <?php echo $_SESSION['user']['isSeller'] ? '' : 'multiple'; ?>>
                            <?php if($_SESSION['user']['isSeller']): ?>
                            <option value="<?php echo $_SESSION['user']['province'] ?>" selected><?php echo $_SESSION['user']['province'] ?></option>

                            <?php else: ?>
                                <option option="-1" disabled>Inform the province</option>
                                <option value="Cabo Delgado">Cabo Delgado</option>
                                <option value="Zambezia">Zambezia</option>
                                <option value="Nampula">Nampula</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>District</label>
                        <select class="form-control   <?php echo $_SESSION['user']['isSeller'] ? '' : 'select2'; ?> district" name="district[]" id="district" style="width: 100%;"   <?php echo $_SESSION['user']['isSeller'] ? '' : 'multiple'; ?>>
                             <?php if($_SESSION['user']['isSeller']): ?>
                            <option value="<?php echo $_SESSION['user']['district'] ?>" selected><?php echo $_SESSION['user']['district'] ?></option>

                            <?php else: ?>
                                <option option="-1" disabled>District</option>
                            <?php endif; ?>

                        </select>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col col-lg-6">
                                <label>Date from:</label>
                                <input type="date" name="dateFrom" id="dateFrom" class="form-control" placeholder="" max="<?php echo date('Y-m-d'); ?>" format="dd/mm/yyyy">
                            </div>
                            <div class="col col-lg-6">
                                <label>Date to:</label>
                                <input type="date" name="dateTo" id="dateTo" class="form-control" placeholder="" max="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-info" id="filterButton">Search</button>
            <button type="reset" class="btn btn-danger" id="resetFilterButton">Clear fields</button>
        </div>
    </div>
</form>
<script src="<?php echo asset('scripts/location.js') ?>"></script>