<?php 
/**
 * Controller for sending email messages to webmaster
 */
class Contact extends CI_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('form');
    }
    
    /**
     * Displays the contact form
     */
    public function index()
    {
        $data['main_content'] = 'contact';
        $data['title'] = 'contact';

        // check to see if this was a failed submission (flash data)
        if ($form_data = $this->session->flashdata('form_data'))
        {
            $data['validation_errors'] = $form_data['post_error'];
            $data['form_data'] = $this->_set_form_vars($form_data);
        }
        else
        {
            $data['form_data'] = $this->_set_form_vars();
            $data['validation_errors'] = '';
        }

        // load the navigation
        $this->config->load('navigation', TRUE);
        $data['navigation'] = $this->config->item('navigation');
        
        $this->load->view('includes/template', $data);
    }

    /**
     * Submits a contact form and is emailed
     */
    public function submit()
    {
        $this->load->library('email');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name',
            'required|max_length[100]|xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email',
            'required|valid_email|max_length[128]|xss_clean|trim');
        $this->form_validation->set_rules('message', 'Message',
            'required|maxlength[500]|xss_clean|trim');
        
        // check if submission is valid
        if (!$this->form_validation->run())
        {
            $form_data = $this->_get_form_data();
            $this->session->set_flashdata('form_data', $form_data);
            redirect('/contact');
        }
        else // send the email
        {
            $form_data = $this->_get_form_data(FALSE);
            $message = '<ul>';
            foreach ($form_data as $key => $value)
            {
                if ($key != 'message')
                    $message .= '<li>' . $key .' : '. $value . '</li>';
            }
            $message .= '</ul><p>' . $form_data['message'] . '</p>';

            $headers = 'MIME-Version 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . $form_data['email'];

            mail("sking092@gmail.com", "Contact form", $message, $headers);

            redirect('/contact');

        }

    }

    /**
     * Returns the form data for retaining for next request
     * @param boolean
     * @return array
     */
    private function _get_form_data($validation_errors = TRUE)
    {
        $form_data = array (
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'message' =>$this->input->post('message')
        );

        if (!$validation_errors)
        {
            $form_data['ip_address'] = $this->input->ip_address();
        }
        else
        {
            $form_data['post_error'] = validation_errors();
        }
        return $form_data;
    }

    /**
     * Sets the variables for the comments form
     * @param the data from failed submission
     * @return array the form variable
     */
    private function _set_form_vars($data = NULL)
    {
        $name_data = array (
            'name' => 'name',
            'id' => 'name',
            'value' => ((isset ($data)) ? $data['name'] : set_value('name')),
            'maxlength' => '100'
        );
        $email_data = array (
            'name' => 'email',
            'id' => 'email',
            'value' => ((isset ($data)) ? $data['email'] : set_value('name')),
            'maxlength' => '100'
        );
        $message_data = array (
            'name' => 'message',
            'id' => 'message',
            'value' => ((isset ($data)) ? $data['message'] : set_value('message')),
            'rows' => '5',
            'cols' => '60'
        );
        return array (
            'name_data' => $name_data,
            'email_data' => $email_data,
            'message_data' => $message_data
        );
    }

}
