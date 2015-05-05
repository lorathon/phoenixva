<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Airports extends PVA_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Datatables', 'table'));
        $this->load->helper('url');
        $this->output->enable_profiler(FALSE);
        
        $this->data['stylesheets'][] = "//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css";
        $this->data['stylesheets'][] = "//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css";
        $this->data['scripts'][] = "//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js";
        $this->data['scripts'][] = "//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js";
        
    }
    
    public function index()
    {	
        //set table id in table open tag
        $tmpl = array('table_open' => '<table id="airports" name="airports" class="table table-striped table-bordered table-hover table-condensed" width="100%">');
        $this->table->set_template($tmpl);
 
        $this->table->set_heading('FS', 'IATA', 'ICAO', 'Name', 'City', 'State', 'Country', 'UTC', 'Elevation', 'Class', 'Active', 'Type', 'Edit');
        
        $this->_render('admin/airports');
    }

    //function to handle callbacks
    public function datatable_airports()
    {
        $airport = new Airport();
        return $airport->datatable();
    }
}
