<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
$edit_page = TRUE;
?>

<div class="container">

    <?php if (isset($types)) : ?>
        <!-- Start: Event Types -->
        <div class="row">
    	<div class="col-md-12">
    	    <section class="panel">
    		<header class="panel-heading">
			<?php if ($show_admin) : ?>
			    <div class="panel-actions">
				<a href="<?php echo site_url('private/events/create-type/') ?>" class="fa fa-plus-square"></a>
			    </div>
			<?php endif; ?>
    		    <h2 class="panel-title">View All Event Types</h2>
    		</header>
    		<div class="panel-body">
    		    <div class="table-responsive">
    			<table class="table table-hover mb-none">
    			    <thead>
    				<tr>
    				    <th>ID</th>
    				    <th>Name</th>
    				    <th>Description</th>
    				    <th>Color</th>
					<?php if ($show_admin) : ?>
					    <th>Actions</th>
					<?php endif; ?>
    				</tr>
    			    </thead>
    			    <tbody>
				    <?php foreach ($types as $type): ?>
					<tr>
					    <td><?php echo $type->id ?></td>
					    <td><?php echo $type->name ?></td>
					    <td><?php echo $type->description ?></td>
					    <td><div class="text-<?php echo $calendar_colors[$type->color_id]; ?>"><?php echo ucfirst($calendar_colors[$type->color_id]); ?></div></td>
					    <?php if ($show_admin) : ?>
	    				    <td align="center">
						    <?php echo anchor('private/events/create-type/' . $type->id, '<i class="fa fa-pencil"></i> Edit', button('info')); ?>
						    <?php echo anchor('private/events/delete-type/' . $type->id, '<i class="fa fa-trash"></i> Delete', button_delete('danger')); ?>
	    				    </td>
					    <?php endif; ?>
					</tr>
				    <?php endforeach; ?>
    			    </tbody>
    			</table>
    		    </div>
    		</div>
    	    </section>
    	</div>
        </div>
        <!-- End: Event Types -->

    <?php elseif (isset($body)): ?>
        <!-- Start: Event Page -->
        <div class="row">
    	<ul class="nav nav-tabs">
    	    <li role="presentation" 
		<?php if (uri_string() == 'events/' . $id): ?>
			class="active"
		    <?php endif; ?>
    		>
			<?php echo anchor('/events/' . $id, 'Event Home'); ?>
    	    </li>
    	    <li role="presentaton"
		<?php if (uri_string() == 'events/' . $id . '/logbook'): ?>
			class="active"
		    <?php endif; ?>
    		>
			<?php echo anchor('/events/' . $id . '/logbook', 'Logbook'); ?>
    	    </li>
    	    <li role="presentaton"
		<?php if (uri_string() == 'events/' . $id . '/awards'): ?>
			class="active"
		    <?php endif; ?>
    		>
			<?php echo anchor('/events/' . $id . '/awards', 'Awards'); ?>
    	    </li>
    	    <li role="presentaton"
		<?php if (uri_string() == 'events/' . $id . '/participants'): ?>
			class="active"
		    <?php endif; ?>
    		>
			<?php echo anchor('/events/' . $id . '/participants', 'Participants'); ?>
    	    </li>
		<?php foreach ($pages as $slug => $page): ?>
		    <li role="presentation"
		    <?php if (uri_string() == "events/{$id}/" . substr($slug, 9)): ?>
	    		class="active"
			<?php endif; ?>
			>
			    <?php echo anchor('/events/' . $slug, $page); ?>
		    </li>
		<?php endforeach; ?>
		<?php if ($show_admin): ?>
		    <li role="presentation">
			<?php echo anchor("/private/events/create-page/{$id}", '<i class="fa fa-plus-square" title="Add Page"></i>'); ?>
		    </li>
		<?php endif; ?>
    	</ul>            
        </div>
        <div class="row">
    	<div class="col-md-8">
		<?php echo $body; ?>

		<!-- START: Awards View -->
		<?php if (isset($event_awards)) : ?>
		    <?php $edit_page = FALSE; ?>
		    <div class="table-responsive">
			<table class="table table-hover mb-none">
			    <thead>
				<tr>
				    <th>Award Name</th>
				    <th>Image</th>
				    <th>Bonus</th>
				    <th>Position</th>
				    <?php if ($show_admin) : ?>
	    			    <th>Actions</th>
				    <?php endif; ?>
				</tr>
			    </thead>
			    <tbody>
				<?php if ($event_awards) : ?>
				    <?php foreach ($event_awards as $event_award) : ?>
					<tr>
					    <td><?php echo award(new Award($event_award->award_id)); ?></td>
					    <td>IMAGE</td>
					    <td><?php echo $event_award->bonus_amount; ?></td>
					    <th><?php echo $event_award->position; ?></th>
					    <?php if ($show_admin) : ?>
					    <td>
						<?php echo anchor('private/events/create-award/' . $event_award->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>
						<?php echo anchor('private/events/delete-award/' . $event_award->id,'<i class="fa fa-trash"></i> Delete', button_delete('danger')); ?>
					    </td>
					    <?php endif; ?>
					</tr>			    
				    <?php endforeach; ?>
				<?php endif; ?>
			    </tbody>
			</table>
		    </div>
		    <!-- END: Awards View -->

		    <!-- START: Participant View -->
		<?php elseif (isset($event_participants)) : ?>
		    <?php $edit_page = FALSE; ?>
		    <div class="table-responsive">
			<table class="table table-hover mb-none">
			    <thead>
				<tr>
				    <th>Name</th>
				    <th>Result</th>
				    <th>Position</th>
				    <th>Actions</th>
				</tr>
			    </thead>
			    <tbody>
				<?php if ($event_participants) : ?>
				    <?php foreach ($event_participants as $participant) : ?>
					<tr>
					    <td><?php echo user(new User($participant->user_id)); ?></td>
					    <td><?php echo $participant->event_result; ?></td>
					    <td><?php echo $participant->position; ?></td>
					    <td>
						<?php if ($participant->user_id == $this->session->userdata('user_id')) : ?>
						    <?php echo anchor("/private/events/remove-user/{$id}", "<i class='fa fa-trash'></i> Remove", button_delete('danger')); ?>
						<?php endif; ?>
					    </td>
					</tr>			    
				    <?php endforeach; ?>
				<?php endif; ?>
			    </tbody>
			</table>
		    </div>

		<?php endif; ?>
    	    <!-- END: Participant View -->		

    	</div>
    	<div class="col-md-4">

    	    <!-- Start: User Options -->
    	    <div class="featured-box featured-box-green">
    		<div class="box-content">
    		    <h2>User Options</h2>
    		    <li role="presentation">
			    <?php echo anchor("/private/events/add-user/{$id}", 'Sign Up'); ?>
    		    </li>
    		</div>
    	    </div>
    	    <!-- End: user options -->

    	    <!-- Start: Admin Options-->
		<?php if ($show_admin): ?>
		    <div class="featured-box featured-box-red">
			<div class="box-content">
			    <h2>Event Admin</h2>
			    <ul class="nav nav-pills">
				<li role="presentation">
				    <?php echo anchor("/private/events/create-event/{$id}", 'Edit Event Details'); ?>
				</li>
				<?php if (uri_string() == 'events/' . $id . '/awards'): ?>
				<li role="presentation">
				    <a href="#" data-toggle="modal" data-target="#awardModal">Add Award</a>
				</li>
				<?php endif; ?>
				<?php if ($edit_page) : ?>
	    			<li role="presentation">
					<?php echo anchor("/private/events/edit-page/{$id}/{$page}", 'Edit This Page'); ?>
	    			</li>
				<?php endif; ?>
				<li role="presentation">
				    <?php echo anchor_delete("/private/events/delete-event/{$id}", 'Delete Event'); ?>
				</li>
			    </ul>
			</div>
		    </div>
		<?php endif; ?>
    	    <!-- End: Admin Options -->

    	    <!-- Start: Event Results (if completed) -->
		<?php if ($event->completed) : ?>
		    <div class="featured-box featured-box-quartenary">
			<div class="box-content">
			    <h2>Event Results</h2>
			    <table class="table table-hover table-condensed">

			    </table>
			</div>
		    </div>		    
		<?php endif; ?>
    	    <!-- End: Event Results -->

    	    <!-- Start: Event Details -->
    	    <div class="featured-box featured-box-blue">
    		<div class="box-content">
    		    <h2>Event Details</h2>
    		    <table class="table table-hover table-condensed">
    			<tr>
    			    <td>Event Type: </td>
    			    <td><?php echo $event_type; ?></td>
    			</tr>
    			<tr>
    			    <td>Start: </td>
    			    <td><?php echo date("m/d/Y", strtotime($event->time_start)); ?></td>
    			</tr>
    			<tr>
    			    <td>End: </td>
    			    <td><?php echo date("m/d/Y", strtotime($event->time_end)); ?></td>
    			</tr>

			    <?php if ($event->waiver_js) : ?>
				<tr>
				    <td>Jumpseat Waiver: </td>
				    <td><i class="fa fa-check"/></td>
				</tr>
			    <?php endif; ?>

			    <?php if ($event->waiver_cat) : ?>
				<tr>
				    <td>CAT Waiver: </td>
				    <td><i class="fa fa-check"/></td>
				</tr>
			    <?php endif; ?>

			    <?php if ($event->airline_id) : ?>
				<tr>
				    <td>Airline: </td>
				    <td><?php echo $airline; ?></td>
				</tr>
			    <?php endif; ?>

			    <?php if ($event->airport_id) : ?>
				<tr>
				    <td>Airport: </td>
				    <td><?php echo $airport; ?></td>
				</tr>
			    <?php endif; ?>					    

    			<tr>
    			    <td>A/C Category: </td>
    			    <td><?php echo $aircraft[$event->aircraft_cat_id]; ?></td>
    			</tr>					    

			    <?php if ($event->landing_rate != 0) : ?>
				<tr>
				    <td>Target Landing Rate: </td>
				    <td><?php echo $event->landing_rate; ?></td>
				</tr>
			    <?php endif; ?>

			    <?php if ($event->flight_time) : ?>
				<tr>
				    <td>Min Flight Time: </td>
				    <td><?php echo $event->flight_time; ?> Hours</td>
				</tr>
			    <?php endif; ?>
    		    </table>
    		</div>
    	    </div>
    	    <!-- End: Event Details -->    	    
    	</div>
	    <!-- Add Award Modal -->
	<div class="modal fade" id="awardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Add Award to Event</h4>
		    </div>
		    <div class="modal-body">
			<?php $this->load->library('form_validation'); ?>
			<?php echo form_open_multipart('private/events/create-award/'. $event->id); ?>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Award <span class="required">*</span></label>
                        <?php echo form_dropdown('award_id', $awards); ?>
                    </div>                    
		    </div>
		    <div class="modal-footer">
			<?php echo form_submit('save', 'Save / Edit', 'class = "btn btn-primary btn-block"'); ?>
			<?php echo form_close(); ?>
		    </div>
		</div>
	    </div>
	</div>
	<!-- Add Award Modal -->
        </div>
	
        <!-- End: Event Page -->

    <?php else: ?>

        <!-- Start: Event List -->
        <div class="row">
    	<div class="col-md-8">
    	    <p class="lead">
    	    </p>
    	    <p>Current or Upcoming Events.</p>
    	    <ul>
		<?php if($events) : ?>
		    <?php foreach ($events as $key => $value): ?>
			<li><?php echo anchor('events/' . $key, $value); ?></li>
		    <?php endforeach; ?>
		<?php else : ?>
			<li>No events scheduled</li>
		<?php endif; ?>
    	    </ul>
    	</div>
    	<div class="col-md-4">
    	    <!-- Start: Admin Options -->
		<?php if ($show_admin): ?>
		    <div class="featured-box featured-box-red">
			<div class="box-content">
			    <h2>Event Admin</h2>
			    <ul class="nav nav-pills">
				<li role="presentation">
				    <?php echo anchor("/private/events/create-event", 'Create New Event'); ?>
				</li>
				<li role="presentation">
				    <?php echo anchor("/private/event-types", 'Event Types'); ?>
				</li>
			    </ul>
			</div>
		    </div>
		<?php endif; ?>
    	    <!-- End: Admin Options -->
    	</div>
        </div>
        <div class="row">
    	<div class="col-md-12">
    	    <div id="calendar"></div>
    	</div>
        </div>
        <!-- End: Event List -->
    <?php endif; ?>	
</div>