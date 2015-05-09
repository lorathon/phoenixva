<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
    #sortable li span { position: absolute; margin-left: -1.3em; }
</style>

<div class="container">
    <div class="row">
	<ul id="sortable">
	    <?php foreach($bids as $bid) : ?>
	    <li id="item-<?php echo $bid->id ?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $bid->get_flightnumber(). ' - '.$bid->id?></li>
	    <?php endforeach; ?>
	</ul>
    </div>
</div>

