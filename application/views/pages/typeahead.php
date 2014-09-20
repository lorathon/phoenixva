<div role="main" class="main">

				<section class="page-top">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<ul class="breadcrumb">
									<li><a href="/zz_staging_ci/">Home</a></li>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h2>Type Ahead</h2>
							</div>
						</div>
					</div>
				</section>

				<div class="container">

				<form name="typeahead_search" id="typeahead_search" action="/zz_staging_ci/index.php/typeahead_results" method="post">
	
					<div class="row">
						<div class="col-md-4" align="right">
							<h2><strong>Departure</strong> Airport</h2>					
						</div>
						<div class="col-md-8">
							<div id="airports">
							  <input class="typeahead" name="departure_airport" type="text" placeholder="Search for Departure Airport">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4" align="right">
							<h2><strong>Arrival</strong> Airport</h2>					
						</div>
						<div class="col-md-8">
							<div id="airports">
							  <input class="typeahead" name="arrival_airport" type="text" placeholder="Search for Arrival Airport">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4" align="right">
							<h2>Search for <strong>Airline</strong></h2>					
						</div>
						<div class="col-md-8">
							<div id="airlines">
							  <input class="typeahead" name="airlines" type="text" placeholder="Search for Airline">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" align="center">
							<button type="submit" class="btn btn-primary btn-lg">Submit Search</button>
					</div>


				</form>
				
				</div>
			</div>				