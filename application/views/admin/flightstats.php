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
                        <section class="panel panel-featured">
                                <header class="panel-heading">
                                        <div class="panel-actions">
                                                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                                                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                                        </div>

                                        <h2 class="panel-title">Flightstats APIs General Information</h2>
                                        <p class="panel-subtitle">Introduction to the APIs used to gather real-world data and workflow guides</p>
                                </header>
                                <div class="panel-body">
                                        <p>We purchase active airline, airport, aircraft and schedule data from Flightstats Flex APIs. We have a pay-as-you-go commercial license 
                                            using the debit card attached to our PVA checking account. All queries require an application ID and application Key which are specific
                                            to our account. Flightstats bills monthly for each API query made using the APIs. Each query has a different cost associated with it. 
                                            All requests are made by using the forms in the following tabs above. A schedule version field is in the forms for specifying which 
                                            version you are creating (S15, W15/16, etc). Recommended work flow for pulling new data is below.</p>
                                        <h3>Airports</h3>
                                        <blockquote class="primary rounded b-thin">
                                        <p>The "Get Active Airports" form on the airports tab is built to get reference data for all currently active public-use airports. Flightstats
                                            classifies airports into 5 categories. 1 - Top 100 airports (by volume). 2 - Top 300 airports. 3 - Top 700 airports. 4 - Airports with flight
                                            history (approx 4,700). 5 - All active airports (approx 25,000). The form is defaulted to level 4. Submitting the form will mark all airports in 
                                            the DB as inactive, query Flightstats and insert or update the airports table with the new data. This could add some new airports or mark some as
                                            inactive. The list of active airports will be used to get schedules, so having too little or too many will affect schedule results. <strong>This 
                                            query costs $.003 (1/3 of a cent).</strong></p>
                                        <p>The "Create Type Json" form will go through the DB and create a JSON file listing all of the active airports with the classification selected. This
                                            is used for Typeahead on schedule searches or elsewhere. It will list the FS code along with the Airport name, city, state (if applicable) and country.
                                            This does not query Flightstats data and runs solely against the airports table. It saves the JSON to assets/data folder.</p>
                                        </blockquote>
                                        <h3>Airlines</h3>
                                        <blockquote class="primary rounded b-thin">
                                        <p>The "Get Active Airlines" form on the airlines tab is built to get reference data for all currently active airlines. It will mark all airlines as
                                            inactive, process the JSON and insert or update the airlines into the airlines table. Some new airlines may be added and some airlines may be marked
                                            as inactive. Schedule version is also present for marking the request (S15, W14/15, etc)<strong>This query costs $.003 (1/3 of a cent).</strong></p>
                                        <p>The "Create Type Json" form will go through the DB and create a JSON file listing all of the active airlines. This is used for Typeahead on schedule
                                            searches or elsewhere. It will list the FS code along with the Airline name. This does not query Flightstats data and runs solely against the airlines 
                                            table. It saves the JSON to assets/data folder.</p>
                                        </blockquote>
                                </div>
                        </section>
                    </div>
                    
                    
                    
                    <div id="airports" class="tab-pane">
                            <?php $attributes = array('class' => 'form-horizontal form-bordered');
                            echo form_open('admin/flightstats_airport/getactive'); ?>
                        
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
                                                    <div class="form-group">
                                                            <label class="col-md-3 control-label">Schedule Version</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="version" value="SU15">
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
                            echo form_open('admin/flightstats_airport/writeJsonApt'); ?>
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Create Typeahead JSON File</h2>

                                                    <p class="panel-subtitle">
                                                            Create Typeahead JSON file for all the active airports in the database. This is used to populate schedule search.
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
                            echo form_open('admin/flightstats_airline/getactive'); ?>
                        
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
                                                    <div class="form-group">
                                                            <label class="col-md-3 control-label">Schedule Version</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="version" value="SU15">
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
                            echo form_open('admin/flightstats_airline/writeJsonAirline'); ?>
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
                            <?php $attributes = array('class' => 'form-horizontal form-bordered');
                            echo form_open('admin/flightstats_schedules/aptsingle'); ?>
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Get Schedules for Single Airport</h2>

                                                    <p class="panel-subtitle">
                                                            Consume Flightstats API for Schedules and insert into database. This will create 28 hits to the API account. ($0.14)
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
                                                            <label class="col-md-3 control-label">Airport FS Code</label>
                                                            <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="apt">
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-md-3 control-label">Date of Schedule Pull</label>
                                                            <div class="col-md-6">
                                                                    <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                    <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                            <input type="text" name="date" data-plugin-datepicker class="form-control">
                                                                    </div>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-md-3 control-label">Schedule Version</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="version" value="SU15">
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
                            echo form_open('admin/flightstats_schedules/aptwhere'); ?>
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Get Schedules for Multiple Airports</h2>

                                                    <p class="panel-subtitle">
                                                            Consume Flightstats API for Schedules and insert into database. This will create several hits to the API account.
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
                                                            <label class="col-sm-3 control-label">Airport ID's to process</label>
                                                            <section class="col-sm-6 form-group-vertical">
                                                                <input class="form-control" type="text" name="idstart" placeholder="Starting ID">
                                                                <input class="form-control last" type="text" name="idstop" placeholder="Ending ID">
                                                            </section>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-md-3 control-label">Date of Schedule Pull</label>
                                                            <div class="col-md-6">
                                                                    <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                    <i class="fa fa-calendar"></i>
                                                                            </span>
                                                                            <input type="text" name="date" data-plugin-datepicker class="form-control">
                                                                    </div>
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-md-3 control-label">Schedule Version</label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="version" value="SU15">
                                                            </div>
                                                    </div>
                                                    <footer class="panel-footer" align="center">
                                                            <button class="btn btn-primary">Submit</button>
                                                            <button type="reset" class="btn btn-default">Reset</button>
                                                    </footer>
                                            </div>
                                    </section>
                            <?php echo form_close(); ?>
                    </div>
                </div>
        </div>          
    </div>
</div>
    