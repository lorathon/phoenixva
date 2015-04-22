<style>
    .ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  float: left;
  display: none;
  min-width: 160px;
  _width: 160px;
  padding: 4px 0;
  margin: 2px 0 0 0;
  list-style: none;
  background-color: #ffffff;
  border-color: #ccc;
  border-color: rgba(0, 0, 0, 0.2);
  border-style: solid;
  border-width: 1px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
  -moz-background-clip: padding;
  background-clip: padding-box;
  *border-right-width: 2px;
  *border-bottom-width: 2px;
 
  .ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
 
    &.ui-state-hover, &.ui-state-active {
      color: #ffffff;
      text-decoration: none;
      background-color: #0088cc;
      border-radius: 0px;
      -webkit-border-radius: 0px;
      -moz-border-radius: 0px;
      background-image: none;
    }
  }
}
</style>

<div class="container">
    <div class="row">
	<div class="form-group">
	    <label class="col-md-3 control-label">Name</label>
	    <div class="col-md-6"><input type="text" id="dep_airport_name" value=""/></div>
	</div>
	<div class="form-group">
	    <label class="col-md-3 control-label">ID</label>
	    <div class="col-md-6"><input type="text" id="dep_airport_id" value="" readonly/></div>
	</div>
	<div class="form-group">
	    <label class="col-md-3 control-label">ICAO</label>
	    <div class="col-md-6"><input type="text" id="dep_airport_icao" value=""/></div>
	</div>
	<div class="form-group">
	    <label class="col-md-3 control-label">IATA</label>
	    <div class="col-md-6"><input type="text" id="dep_airport_iata" value=""/></div>
	</div>
    </div>
</div>

