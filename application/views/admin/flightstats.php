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
                            <form id="form2" class="form-horizontal form-bordered">
                                    <section class="panel panel-featured">
                                            <header class="panel-heading">
                                                    <div class="panel-actions">
                                                            <a href="#" class="fa fa-caret-down"></a>
                                                            <a href="#" class="fa fa-times"></a>
                                                    </div>

                                                    <h2 class="panel-title">Get Active Airports</h2>

                                                    <p class="panel-subtitle">
                                                            Consume Flightstats API for active airports and insert into database. This will create 1 hit to the API account.
                                                    </p>
                                            </header>
                                            <div class="panel-body">
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Flightstats App ID</label>
                                                            <div class="col-sm-6">
                                                                    <input type="text" name="appid" class="form-control">
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-sm-3 control-label">Flightstats App Key</label>
                                                            <div class="col-sm-6">
                                                                    <input type="text" name="appkey" class="form-control">
                                                            </div>
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
                            
                            <form id="form2" class="form-horizontal form-bordered">
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
                                                                    <select class="form-control mb-md">
                                                                            <option value="1">1 - Top 100 Airports</option>
                                                                            <option value="2">2 - Top 300 Airports</option>
                                                                            <option value="3">3 - Top 700 Airports</option>
                                                                            <option value="4">4 - All Airports (~ 4,000)</option>
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
                                <p>Recent <code>.nav-tabs.nav-justified</code></p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                    </div>
                    <div id="schedules" class="tab-pane">
                            <form class="form-horizontal form-bordered" method="get">
                                    <div class="form-group">
                                            <label class="col-md-3 control-label" for="inputDefault">Default</label>
                                            <div class="col-md-6">
                                                    <input type="text" class="form-control" id="inputDefault">
                                            </div>
                                    </div>

                                    <div class="form-group">
                                            <label class="col-md-3 control-label" for="inputHelpText">Help text</label>
                                            <div class="col-md-6">
                                                    <input type="text" class="form-control" id="inputHelpText">
                                                    <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
                                            </div>
                                    </div>

                                    <div class="form-group">
                                            <label class="col-md-3 control-label" for="inputRounded">Rounded Input</label>
                                            <div class="col-md-6">
                                                    <input type="text" class="form-control input-rounded" id="inputRounded">
                                            </div>
                                    </div>
                            </form>
                    </div>
                </div>
        </div>          
    </div>
</div>
    