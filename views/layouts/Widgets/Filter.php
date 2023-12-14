<form name="filterForm" method="GET">
    <div class="card card-primary card-outline collapsed-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="campaign">Campaign</label>
                        <select class="form-control" name="campaign" id="campaign">
                            <option value="All">All</option>
                            {%foreach($campaigns ?? [] as $campaign):%}
                            <option value="{{$campaign['opening'].'='.$campaign['closing'];}}">{{$campaign["description"];}}</option>
                            {%endforeach;%}
                        </select>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Province</label>
                        <select class="form-control {{ $_SESSION['user']['isSeller'] ? '' : 'select2'; }} province" name="province[]" id="province" style="width: 100%;"  {{ $_SESSION['user']['isSeller'] ? '' : 'multiple'; }}>
                            {%if($_SESSION['user']['isSeller']):%}
                            <option value="{{$_SESSION['user']['province']}}" selected>{{$_SESSION['user']['province']}}</option>

                            {%else:%}
                                <option option="-1" disabled>Inform the province</option>
                                <option value="Cabo Delgado">Cabo Delgado</option>
                                <option value="Zambezia">Zambezia</option>
                                <option value="Nampula">Nampula</option>
                            {%endif;%}
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>District</label>
                        <select class="form-control   {{ $_SESSION['user']['isSeller'] ? '' : 'select2'; }} district" name="district[]" id="district" style="width: 100%;"   {{ $_SESSION['user']['isSeller'] ? '' : 'multiple'; }}>
                             {%if($_SESSION['user']['isSeller']):%}
                            <option value="{{$_SESSION['user']['district']}}" selected>{{$_SESSION['user']['district']}}</option>

                            {%else:%}
                                <option option="-1" disabled>District</option>
                            {%endif;%}

                        </select>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col col-lg-6">
                                <label>Date from:</label>
                                <input type="date" name="dateFrom" id="dateFrom" class="form-control" placeholder="" max="{{date('Y-m-d');}}" format="dd/mm/yyyy">
                            </div>
                            <div class="col col-lg-6">
                                <label>Date to:</label>
                                <input type="date" name="dateTo" id="dateTo" class="form-control" placeholder="" max="{{date('Y-m-d');}}">
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
<script src="{{ asset('scripts/location.js') }}"></script>