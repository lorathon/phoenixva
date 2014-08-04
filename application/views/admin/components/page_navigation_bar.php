<!-- START: admin/components/page_navigation_bar -->

<div class="navbar navbar-static-top navbar-inverse">
     <div class="navbar-inner">
            <a class="brand" href="<?php echo site_url('')?>"><?php echo $meta_title?></a>
            <ul class="nav">
                <li class="active"><a href="<?php echo site_url('admin/dashboard')?>">Dashboard</a></li>
                <li><?php echo anchor('admin/page', 'Pages')?></li>
                <li><?php echo anchor('admin/article', 'News')?></li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Users<strong class="caret"></strong></a>
                    <ul class="dropdown-menu">
                        <li>
                            <li><?php echo anchor('admin/user', 'User Manager')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/user/new_users', 'New Users')?></li>
                        </li>
                        <li class="divider"></li>
                        <li class="nav-header">
                            User Items
                        </li>
                        <li>
                            <li><?php echo anchor('admin/rank', 'Ranks')?></li>
                        </li>                        
                        <li>
                            <li><?php echo anchor('admin/award', 'Awards')?></li>
                        </li>     
                    </ul>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Operations<strong class="caret"></strong></a>
                    <ul class="dropdown-menu">
                        <li>
                            <li><?php echo anchor('admin/aircraft', 'Aircraft')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/airline', 'Airlines')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/regional', 'Airlines Regional')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/airport', 'Airports')?></li>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Schedules<strong class="caret"></strong></a>
                    <ul class="dropdown-menu">
                        <li>
                            <li><?php echo anchor('admin/schedule_active', 'Active')?></li>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <li><?php echo anchor('admin/schedule_pending', 'Pending')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/schedule_archive', 'Archive')?></li>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Pireps<strong class="caret"></strong></a>
                    <ul class="dropdown-menu">
                        <li>
                            <li><?php echo anchor('admin/dashboard', 'All Pending Pireps')?></li>
                        </li>
                        <li class="divider"></li>
                        <li class="nav-header">
                            Crew Center Pireps
                        </li>
                        <li>
                            <li><?php echo anchor('admin/dashboard', 'EDDT Pireps')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/dashboard', 'EGLL Pireps')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/dashboard', 'KJFK Pireps')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/dashboard', 'KLAX Pireps')?></li>
                        </li>
                        <li>
                            <li><?php echo anchor('admin/dashboard', 'KSTL Pireps')?></li>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Finances<strong class="caret"></strong></a>
                    <ul class="dropdown-menu">
                      <li>
                        <li><?php echo anchor('admin/finance_prices', 'Pricing')?></li>
                      </li>
                      <li>
                        <li><?php echo anchor('admin/finance_pireps_fees', 'PIREP Fees')?></li>
                      </li>
                      <li>
                        <li><?php echo anchor('admin/finance_fuel_price', 'Fuel Price')?></li>
                      </li>
                    </ul>
                </li>
            </ul>
            <ul class="nav pull-right">
              <li>
                <a href="#">Settings</a>
              </li>
            </ul>
        </div>
    </div>
<!-- END: admin/components/page_navigation_bar -->