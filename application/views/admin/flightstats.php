<header class="page-header">
        <h2>Flightstats Data</h2>
</header>

<!-- start: page -->
<div class="row">

    <div class="col-md-12">
        <div class="tabs tabs-primary">
                <ul class="nav nav-tabs">
                        <li class="active">
                                <a href="#general" data-toggle="tab" class="text-center" aria-expanded="true">General Information</a>
                        </li>
                        <li class="">
                                <a href="#airports" data-toggle="tab" class="text-center" aria-expanded="false">Airports</a>
                        </li>
                        <li class="">
                                <a href="#airlines" data-toggle="tab" class="text-center" aria-expanded="false">Airlines</a>
                        </li>
                        <li class="">
                                <a href="#schedules" data-toggle="tab" class="text-center" aria-expanded="false">Schedules</a>
                        </li>
                </ul>
                <div class="tab-content">
                    <div id="general" class="tab-pane active">
                                <p>Recent <code>.nav-tabs.nav-justified</code></p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                    </div>
                    <div id="airports" class="tab-pane">
                        
                            <?php $attributes = array('class' => 'form-horizontal form-bordered');
                            echo form_open('admin/flightstatsapt/getactive'); ?>
                        
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Get Active Airports</h2>

                                                    <p class="panel-subtitle">
                                                            Marks all airports as inactive, consumes API for airports matching filter and updates or inserts into database. Airports will not be deleted. They will remain inactive but available for stat purposes. This will create 1 hit to the API account.
                                                    </p>
                                            </header>
                                            <div class="panel-body">
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Flightstats Credentials</label>
                                                            <section class="col-sm-6 form-group-vertical">
                                                                <input class="form-control" type="text" name="appid" placeholder="App ID" value="f48100ea">
                                                                <input class="form-control last" type="text" name="appkey" placeholder="App Key" value="217ebf7797870e89ad05a5a69e4f4bf6">
                                                            </section>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Classification Limit</label>
                                                            <div class="col-sm-6">
                                                                    <select class="form-control mb-md" name="class">
                                                                            <option value="1">1 - Top 100 Airports</option>
                                                                            <option value="2">2 - Top 300 Airports</option>
                                                                            <option value="3">3 - Top 700 Airports</option>
                                                                            <option value="4" selected="true">4 - All Airports w/ history (approx 4,000)</option>
                                                                            <option value="5">5 - All Published Airports (approx 25,000)</option>
                                                                    </select>
                                                            </div>
                                                            <div class="col-sm-9 col-md-offset-3">
                                                                    <span class="help-block">Choose the amount of airports to process and insert into DB. Defaults to all airports with flight history in Flightstats.</span>
                                                            </div>
                                                    </div>
                                                    <footer class="panel-footer" align="center">
                                                            <button class="btn btn-primary">Submit</button>
                                                            <button type="reset" class="btn btn-default">Reset</button>
                                                    </footer>
                                            </div>
                                    </section>
                            <?php echo form_close(); ?>
                            
                            <?php $attributes = array('class' => 'form-horizontal form-bordered');
                            echo form_open('admin/flightstatsapt/writeJsonApt'); ?>
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Create Typeahead JSON File</h2>

                                                    <p class="panel-subtitle">
                                                            Create Typeahead JSON file for all the airports from active airports in the database. This is used to populate schedule search.
                                                    </p>
                                            </header>
                                            <div class="panel-body">
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Classification Limit</label>
                                                            <div class="col-sm-6">
                                                                    <select class="form-control mb-md" name="class">
                                                                            <option value="1">1 - Top 100 Airports</option>
                                                                            <option value="2">2 - Top 300 Airports</option>
                                                                            <option value="3">3 - Top 700 Airports</option>
                                                                            <option value="4" selected="true">4 - All Airports w/ history (approx 4,000)</option>
                                                                            <option value="5">5 - All Published Airports (approx 25,000)</option>
                                                                    </select>
                                                            </div>
                                                    </div>
                                                    <footer class="panel-footer" align="center">
                                                            <button class="btn btn-primary">Submit</button>
                                                            <button type="reset" class="btn btn-default">Reset</button>
                                                    </footer>
                                            </div>
                                    </section>
                            </form>
                    </div>
                    <div id="airlines" class="tab-pane">
                            <?php $attributes = array('class' => 'form-horizontal form-bordered');
                            echo form_open('admin/flightstatsairline/getactive'); ?>
                        
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Get Active Airlines</h2>

                                                    <p class="panel-subtitle">
                                                            Marks all airlines as inactive (except PVA*),consumes API for active airlines and updates or inserts into database. Airlines will not be deleted. They will remain inactive but available for stat purposes. This will create 1 hit to the API account.
                                                    </p>
                                            </header>
                                            <div class="panel-body">
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Flightstats Credentials</label>
                                                            <section class="col-sm-6 form-group-vertical">
                                                                <input class="form-control" type="text" name="appid" placeholder="App ID" value="f48100ea">
                                                                <input class="form-control last" type="text" name="appkey" placeholder="App Key" value="217ebf7797870e89ad05a5a69e4f4bf6">
                                                            </section>
                                                    </div>
                                                    <footer class="panel-footer" align="center">
                                                            <button class="btn btn-primary">Submit</button>
                                                            <button type="reset" class="btn btn-default">Reset</button>
                                                    </footer>
                                            </div>
                                    </section>
                            <?php echo form_close(); ?>
                        
                            <?php $attributes = array('class' => 'form-horizontal form-bordered');
                            echo form_open('admin/flightstatsairline/writeJsonAirline'); ?>
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Create Typeahead JSON File</h2>

                                                    <p class="panel-subtitle">
                                                            Create Typeahead JSON file for all the airlines from active airlines in the database. This is used to populate schedule search.
                                                    </p>
                                            </header>
                                            <div class="panel-body">
                                                    
                                                    <footer class="panel-footer" align="center">
                                                            <button class="btn btn-primary">Submit</button>
                                                            <button type="reset" class="btn btn-default">Reset</button>
                                                    </footer>
                                            </div>
                                    </section>
                            <?php echo form_close(); ?>
                    </div>
                    <div id="schedules" class="tab-pane">
                            <form id="form2" class="form-horizontal form-bordered">
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Get Schedules</h2>

                                                    <p class="panel-subtitle">
                                                            Consume Flightstats API for Schedules and insert into database. This will create several hits to the API account.
                                                    </p>
                                            </header>
                                            <div class="panel-body">
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Flightstats Credentials</label>
                                                            <section class="col-sm-6 form-group-vertical">
                                                                <input class="form-control" type="text" name="appid" placeholder="App ID" value="f48100ea">
                                                                    <input class="form-control last" type="text" name="appid" placeholder="App Key">
                                                            </section>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Classification Limit</label>
                                                            <div class="col-sm-6">
                                                                    <select class="form-control mb-md">
                                                                            <option value="1">1 - Top 100 Airports</option>
                                                                            <option value="2">2 - Top 300 Airports</option>
                                                                            <option value="3">3 - Top 700 Airports</option>
                                                                            <option value="4">4 - All Airports (~ 4,000)</option>
                                                                    </select>
                                                            </div>
                                                            <div class="col-sm-9 col-md-offset-3">
                                                                    <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
                                                            </div>
                                                    </div>
                                                    <footer class="panel-footer" align="center">
                                                            <button class="btn btn-primary">Submit</button>
                                                            <button type="reset" class="btn btn-default">Reset</button>
                                                    </footer>
                                            </div>
                                    </section>
                            </form>
                    </div>
                </div>
        </div>          
    </div>
</div>
    