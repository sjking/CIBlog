<?php

class About extends CI_Controller
{
    /**
     * Displays the about page
     */
    public function index()
    {
        $data['title'] = 'About';
        $data['main_content'] = 'about';

        $this->config->load('navigation', TRUE);
        $data['navigation'] = $this->config->item('navigation');

        $this->load->view('includes/template', $data);
    }
}
