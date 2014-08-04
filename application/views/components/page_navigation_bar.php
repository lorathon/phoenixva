<!-- START: components/page_navigation_bar.php -->

<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a>
            <div class="nav-collapse collapse navbar-responsive-collapse">
                <ul class="nav">
                    <li class="active">
                        <a href="<?php echo site_url('')?>">Home</a>
                    </li>
                    <li>
                        <a href="#">Link</a>
                    </li>
                    <li>
                        <a href="#">Link</a>
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown<strong class="caret"></strong></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#">Action</a>
                            </li>
                            <li>
                                <a href="#">Another action</a>
                            </li>
                            <li>
                                <a href="#">Something else here</a>
                            </li>
                            <li class="divider"></li>
                            <li class="nav-header">
                                Nav header
                            </li>
                            <li>
                                <a href="#">Separated link</a>
                            </li>
                            <li>
                                <a href="#">One more separated link</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav pull-right">
                    <li>
                        <a href="#">Link</a>
                    </li>
                    <li class="divider-vertical">
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown<strong class="caret"></strong></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#">Action</a>
                            </li>
                            <li>
                                <a href="#">Another action</a>
                            </li>
                            <li>
                                <a href="#">Something else here</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">Separated link</a>
                            </li>
                            <li>
                                <a href="#">Separated link</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <?php if($this->tank_auth->is_logged_in()) : ?>
                            <?php echo anchor('auth/logout', 'Logout'); ?>
                        <?php else : ?>
                            <?php echo anchor('auth/login', 'Login'); ?>
                        <?php endif; ?>
                    </li>
                    <?php if($this->tank_auth->is_logged_in()) : ?>
                    <?php if($userdata['admin'] > 1) : ?>
                    <li>
                        <?php echo anchor('admin/dashboard', 'Admin'); ?>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>                
        </div>
    </div>
</div>
<!-- END: components/page_navigation_bar.php -->    
