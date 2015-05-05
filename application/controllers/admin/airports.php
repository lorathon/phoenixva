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
    
    public function create_airframe($id = NULL)
    {		
	$this->load->library('form_validation'); 
	$this->load->helper('url');
	
	$this->data['title'] = 'Edit Airframe';
	
	if(is_null($id))
	    $this->data['title'] = 'Create Airframe';
	
        $aircraft = new Airframe($id);
                
        $this->form_validation->set_rules('id', 'ID', '');
        $this->form_validation->set_rules('name', 'Name', 'alpha-numberic|trim|required|xss_clean');
        $this->form_validation->set_rules('pax_first', '1st Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_business', 'Business Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('pax_economy', 'Economy Class Seats', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('payload', 'Payload Capacity', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('max_range', 'Maximum Range', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('oew', 'Operating Empty Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mzfw', 'Maximum Zero Fuel Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mlw', 'Maximum Landing Weight', 'integer|trim|xss_clean');
	$this->form_validation->set_rules('mtow', 'Meximum Take Off Weight', 'integer|trim|xss_clean');
	
	$this->data['aircraft_cat'] = $this->config->item('aircraft_cat');
                
        if ($this->form_validation->run() == FALSE)
	{             
            $this->data['errors'] = validation_errors();;  
            $this->data['record'] = $aircraft;
            $this->_render('admin/airframe_form');
	}
	else
	{
            $aircraft->id	    = $this->input->post('id', TRUE);
            $aircraft->name	    = $this->form_validation->set_value('name');
	    $aircraft->category	    = $this->input->post('category', TRUE);
	    $aircraft->regional	    = $this->input->post('regional', TRUE);
	    $aircraft->turboprop    = $this->input->post('turboprop', TRUE);
	    $aircraft->jet	    = $this->input->post('jet', TRUE);
	    $aircraft->widebody	    = $this->input->post('widebody', TRUE);
	    $aircraft->helicopter   = $this->input->post('helicopter', TRUE);
	    $aircraft->pax_first    = $this->form_validation->set_value('pax_first');
	    $aircraft->pax_business = $this->form_validation->set_value('pax_business');
	    $aircraft->pax_economy  = $this->form_validation->set_value('pax_economy');
	    $aircraft->payload	    = $this->form_validation->set_value('payload');
	    $aircraft->max_range    = $this->form_validation->set_value('max_range');
	    $aircraft->oew	    = $this->form_validation->set_value('oew');
	    $aircraft->mzfw	    = $this->form_validation->set_value('mzfw');
	    $aircraft->mlw	    = $this->form_validation->set_value('mlw');
	    $aircraft->mtow	    = $this->form_validation->set_value('mtow');
                
            $aircraft->save();
	    
	    $this->_alert('Airframe - Record Saved', 'success', FALSE);
	    $this->index();
	}        
    }
}
