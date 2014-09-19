<?php

class Schedule_Active extends PVA_Controller
{
    protected $_table_name = 'schedules_active';
    protected $_order_by = 'icao';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('callback_model');
        $this->load->model('my_model/schedule_m');
        
        /* Set Sidebar */
        $this->data['sidebar'] = 'admin/schedule/sidebar';
    }
    
    public function index()
    {        
        $this->load->model('my_model/airport_m');
        $this->load->model('my_model/airline_m');
        $this->load->model('my_model/regional_m');
        $this->load->model('my_model/aircraft_m');
        
        $airports = $this->airport_m->get_dropdown();
        $airlines = $this->airline_m->get_dropdown();
        $regionals = $this->regional_m->get_dropdown();
        $aircraft = $this->aircraft_m->get_dropdown();
        
        $crud = new grocery_CRUD();
        $crud->set_table($this->_table_name);
        $crud->set_subject('Schedule');
        $crud->set_theme(config_item('grocery_theme'));
        
        // jQuery Scripts
        $crud->unset_jquery_ui();
        $crud->unset_jquery();
        
        // Remove functions
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();          
         
        // Setup main table columns
        $crud->columns('airline', 'flight_num', 'reg_airline', 'dep_icao', 'arr_icao', 'aircraft_id', 'flight_type', 'enabled');
                
        // Unset fields
        //$crud->unset_fields('');
        
        // Required fields
        $crud->required_fields('airline', 'flight_num', 'dep_icao', 'arr_icao', 'aircraft_id', 'flight_type');
        
        // Field Rules
        $crud->set_rules('flight_num', 'Flight Number', 'required|trim|xss_clean|alpha-numeric');
        $crud->set_rules('flight_level', 'Flight Level', 'trim|xss_clean|numeric');
        $crud->set_rules('price_first', 'First Class Ticket', 'trim|xss_clean|numeric');
        $crud->set_rules('price_business', 'Business Class Ticket', 'trim|xss_clean|numeric');
        $crud->set_rules('price_economy', 'Economy Class Ticket', 'trim|xss_clean|numeric');
        $crud->set_rules('price_cargo', 'Cargo Price', 'trim|xss_clean|numeric');
        $crud->set_rules('dep_time', 'Departure Time', 'trim|xss_clean');
        $crud->set_rules('arr_time', 'Arrival Time', 'trim|xss_clean');
        $crud->set_rules('flight_time', 'Flight Time', 'trim|xss_clean');
        $crud->set_rules('ver', 'Version', 'trim|xss_clean|alpha-numeric');
        
        // Load model to handle dates
        $crud->set_model('MY_grocery_Model');
                
        $crud->order_by('airline asc, flight_num asc', '');
        
        // Set field types
        $crud->field_type('enabled', 'true_false');
                
        // Relationships
        $crud->field_type('airline','dropdown', $airlines);
        $crud->field_type('reg_airline','dropdown', $regionals);       
        $crud->field_type('flight_type','dropdown', config_item('flight_type'));
        $crud->field_type('dep_icao','dropdown', $airports);
        $crud->field_type('arr_icao','dropdown', $airports);
        $crud->field_type('aircraft_id','dropdown', $aircraft);
        
        // Set field callbacks
        $crud->callback_field('flight_level',array($this->callback_model,'_add_feet_flightlevel'));
        $crud->callback_field('distance',array($this->callback_model,'_add_miles_distance'));
        $crud->callback_field('flight_time',array($this->callback_model,'_add_hours_flighttime'));
        $crud->callback_field('dep_time',array($this->callback_model,'_add_gmt_deptime'));
        $crud->callback_field('arr_time',array($this->callback_model,'_add_gmt_arrtime'));
        $crud->callback_field('price_first',array($this->callback_model,'_dollar_pax_first'));
        $crud->callback_field('price_business',array($this->callback_model,'_dollar_pax_business'));
        $crud->callback_field('price_economy',array($this->callback_model,'_dollar_pax_economy'));
        $crud->callback_field('price_cargo',array($this->callback_model,'_dollar_cargo'));
        $crud->callback_field('days_of_week',array($this->callback_model,'_callback_daysofweek'));
        $crud->callback_field('times_flown',array($this->callback_model,'_callback_times_flown'));
        $crud->callback_field('created',array($this->callback_model,'_readonly'));
        $crud->callback_field('modified',array($this->callback_model,'_readonly'));
        
        
        // Display As
        $crud->display_as('airline', 'Airline');
        $crud->display_as('flight_num', 'Flight Number');
        $crud->display_as('reg_airline', 'Regional Airline');
        $crud->display_as('dep_icao', 'Departure');
        $crud->display_as('arr_icao', 'Arrival');
        $crud->display_as('flight_level', 'Flight Level');
        $crud->display_as('dep_time', 'Departure Time');
        $crud->display_as('arr_time', 'Arrival Time');
        $crud->display_as('days_of_week', 'Weekly Schedule');
        $crud->display_as('price_first', 'First Class Ticket');
        $crud->display_as('price_business', 'Business Class Ticket');
        $crud->display_as('price_economy', 'Economy Class Ticket');
        $crud->display_as('price_cargo', 'Cargo Price');
        $crud->display_as('aircraft_id', 'Aircraft');
        $crud->display_as('flight_type', 'Flight Type');
        $crud->display_as('ver', 'Version');
        
        // Callback prior to inserting/updating
        $crud->callback_before_insert(array($this->callback_model,'_callback_insert'));
        $crud->callback_before_update(array($this->callback_model,'_callback_update'));
        $crud->callback_column('enabled', array($this->callback_model, '_callback_enabled'));
        
        $crud->unset_texteditor('notes','full_text');
        $crud->field_type('route','text');
        $crud->unset_texteditor('route','full_text');
        
        $crud->add_action('Archive Schedule', base_url('images/icons/success_16x16.png'), 'admin/schedule_active/move_active', '');
        
        // Create the table
        $output = $crud->render();

        $this->data['output'] = $output->output;
        $this->data['js_files'] = $output->js_files;
        $this->data['css_files'] = $output->css_files;
        $this->data['subview'] = 'admin/schedule/active';
        $this->load->view('admin/_layout_main', $this->data);
    }
        
    
    
    public function move_active($id)
    {
        $_id = $this->uri->segment(4);
        
        if($this->schedule_m->move_active($_id))
        {
            $this->session->set_flashdata('alert_message', 'Schedule Archived!');
            $this->session->set_flashdata('alert_type', 'success');
        }
        else
        {
            $this->session->set_flashdata('alert_message', 'Schedule was NOT Archived!');
            $this->session->set_flashdata('alert_type', 'error');
        }  
        redirect('admin/schedule_active');
    }
}