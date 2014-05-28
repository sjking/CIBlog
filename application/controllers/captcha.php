<?php

/**
 * Captcha
 *
 * Verify that the commenter is not a robot
 */
class Captcha extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('captcha');
        $this->load->model('captcha_model');
        $this->load->helper('form');
    }

    /**
     * Creates the captcha image
     * @return array
     */
    public function generate_captcha()
    {
        $form_data = $this->session->flashdata('form_data');
        
        $vals = array (
            'img_path' => $_SERVER["DOCUMENT_ROOT"] . '/captcha/',
            'img_url' => base_url() . 'captcha/',
            'font_path' => $_SERVER["DOCUMENT_ROOT"] . '/fonts/DejaVuSansMono.ttf',
            'img_width' => '200',
            'img_height' => '60',
            'expiration' => 10
        );
        
        $cap = create_captcha($vals);

        $data['cap'] = $cap;
        $data['main_content'] = 'robot_filter';
        $data['title'] = 'Robot Filter';
        $data['form_data'] = array (
            'name' => 'captcha',
            'id' => 'captcha',
            'value' => '',
            'maxlength' => '8'
        );
        // set the navigation
        $this->config->load('navigation', TRUE);
        $data['navigation'] = $this->config->item('navigation');

        $cap_data = array (
            'captcha_time' => $cap['time'],
            'ip_address' => $form_data['ip_address'],
            'word' => $cap['word']
        );
        
        $this->captcha_model->insert_data($cap_data);

        // retain the data
        $this->session->keep_flashdata('form_data');
        
        // call the view
        $this->load->view('includes/template', $data);
    }

    /**
     * Checks the user input against the captcha in database
     */
    public function check()
    {
        $form_data = $this->session->flashdata('form_data');
        
        $data = array (
            'word' => $this->input->post('captcha'), 
            'ip_address' => $this->input->ip_address() 
        );

        // check if they got the right jumbled code...
        if ($this->captcha_model->is_valid($data))
        {
            // now we can input the stuff into the database...
            $this->load->model('news_model');
            $this->news_model->insert_comment($form_data);

            // redirect to the newly inserted comment...
            // find out the page number of comments to go to
            $num_comments = $this->news_model->get_num_rows_with_id(
                'news_comment', $form_data['news_id']);
            $this->config->load('comment_pagination', TRUE);
            $config = $this->config->item('comment_pagination');
            $page_num = ceil($num_comments / $config['per_page']);

            // get the news slug, and create the uri string
            $slug = $this->news_model->get_slug_for_id($form_data['news_id']);

            echo '$slug: ' . $slug . '<br />';
            echo '$page_num: ' . $page_num . '<br />'; 
            redirect('/news/' . $slug . '/' . ($page_num > 1 ? $page_num : '') . '#comment_end');
        }
        else
        {
            $this->session->keep_flashdata('form_data');
            // reload the robot filter
            redirect('robot-filter');
        }
    }
}
