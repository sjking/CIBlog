<?php
class News extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        
    }

    /**
     * Load the navigation for the site
     * @return array
     */
    private function _load_navigation()
    {
        $this->config->load('navigation', TRUE);
        return $this->config->item('navigation');
    }

    /**
     * Loads the news feed
     * @param int
     */
    public function index($offset = 0)
    {
        // load up the pagination
        $this->load->library('pagination');
        $this->config->load('news_pagination', TRUE);
        $config = $this->config->item('news_pagination');

        $config['base_url'] = base_url() . '/news';
        $config['total_rows'] = $this->news_model->get_total_num_rows();
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);

        $this->load->library('String_Formatter');
        
        // calculate the offset from the page number
        if ($offset > 1)
        {
            $offset = ($offset - 1) * $config['per_page'];
        }

        $data['news_feed'] = $this->news_model->get_feed(
            'news', $config['per_page'], $offset);
        $data['title'] = 'News Feed';
        $data['main_content'] = 'news_feed';
        $data['preview_length'] = $config['preview_length'];
/*
        echo '<pre>';
        print_r($data['news_feed']);
        echo '</pre>';
 */        
        // set the properties for the feed item thumbnail images
        foreach ($data['news_feed'] as &$feed)
        {
            $feed['thumbnail_image'] = array (
                'src' => 'images/thumbnail/' . $feed['image_name'],
                'class' => 'feed_thumbnail');
        }

        // load the navigation
        $data['navigation'] = $this->_load_navigation();

//        $this->load->view('includes/banner', $data);    
        $this->load->view('includes/template', $data);
    }
    
    /**
     * Loads a specific news item
     *
     * @param string the news item slug
     * @param int the comment page
     * @param optional name for page link
     */
    public function load_slug($slug, $offset = 0) 
    {
        // check if the slug has #string on end (redirection to part of page)
        $slug = $this->_slugiffy($slug);

        $this->load->library('String_Formatter');
        $data['news_item'] = $this->news_model->get_news_item('news', $slug);
        $data['main_content'] = 'news_item';
        
        $data['title'] = $data['news_item']['news_title'];
        $data['images'] = $this->news_model->get_images_for_item(
            'news', $slug);

        // insert the <p> tags (also escapes html special chars)
        $data['news_item']['news_text'] = $this->string_formatter->html_paragraph(
            $data['news_item']['news_text']);

        // replace all {image} tags with their image tags
        $data['news_item']['news_text'] = $this->string_formatter->replace_image_tag(
            $data['images'], $data['news_item']['news_text']);

/*
        // set the header image
        $this->config->load('news_config');
        $width = $this->config->item('header_image_width');
        $header_image_name = $this->news_model->get_header_image_for_item('news', $data['news_item']['id']);
        $data['header_image']['src'] = base_url('images/news/' . $header_image_name);
        $data['header_image']['width'] = $width;
        $data['header_image']['height'] = $this->string_formatter->get_height_of_header_image(
            $header_image_name, $width);
        $data['header_image']['class'] = 'header_image';

        // set the anchor for the header
        $data['header_anchor']['uri'] = '/images/news/' . $header_image_name;
        $data['header_anchor']['attributes'] = array ('target' => '_blank');

 */
        // load and setup the pagination for the comments
        $this->load->library('pagination');
        $this->config->load('comment_pagination', TRUE);
        $config = $this->config->item('comment_pagination');
        $config['base_url'] = base_url() . '/news/' . $slug;
        $config['total_rows'] = $this->news_model->get_num_rows_with_id(
            'news_comment', $data['news_item']['id']);
        $config['use_page_numbers'] = TRUE;
        $this->pagination->initialize($config);
        
        // calculate the offset from the page number
        if ($offset > 1)
        {
            $offset = ($offset - 1) * $config['per_page'];
        }

        // load the comments for the current page
        $data['comments'] = $this->news_model->get_comments_for_item(
            'news', $data['news_item']['id'], $config['per_page'], $offset);
        foreach ($data['comments'] as &$comment)
        {
            $comment['comment_text'] = htmlspecialchars($comment['comment_text']);
        }

        // load the form library for the comments form
        $this->load->helper('form');

        // check to see if this was a failed comment submission (flash data)
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
        $data['navigation'] = $this->_load_navigation();

        $this->load->view('includes/template', $data);
    }

    /**
     * Converts the last uri segment back to slug if required
     * @param string the last uri segment
     * @return string the slugiffied uri segment
     */
    private function _slugiffy($uri)
    {
        if (preg_match('/([a-z0-9]+(-[a-z0-9]+)*)(#[a-z]+)/', $uri))
        {
            return preg_replace('/#[a-z]+/', '', $uri);
        }
        else
        {
            return $uri;
        }
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
        $text_data = array (
            'name' => 'text',
            'id' => 'text',
            'value' => ((isset ($data)) ? $data['text'] : set_value('text')),
            'rows' => '5',
            'cols' => '60'
        );
        $url_data = array (
            'name' => 'url',
            'id' => 'url',
            'value' => ((isset ($data)) ? $data['url'] : set_value('url')),
            'maxlength' => '100'
        );
        return array (
            'name_data' => $name_data, 
            'email_data' => $email_data, 
            'text_data' => $text_data, 
            'url_data' => $url_data
        );
    }

    /**
     * Submits a comment from a news item
     */
    public function submit_comment()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name', 
            'required|max_length[30]|xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email',
            'valid_email|max_length[128]|xss_clean|trim');
        $this->form_validation->set_rules('url', 'Website',
            'maxlength[128]|xss_clean|trim');
        $this->form_validation->set_rules('text', 'Comment',
            'maxlength[500]|xss_clean|trim');

        // check if form is valid 
        if (!$this->form_validation->run())
        {
            $form_data = $this->_get_form_data();
            $this->session->set_flashdata('form_data', $form_data);
            redirect('/news/' . $this->input->post('slug') . '#errors');
        }
        else
        {
            // redirect to captcha to stop manevolent robots
            $form_data = $this->_get_form_data(FALSE);
            $this->session->set_flashdata('form_data', $form_data);
            redirect('/robot-filter');
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
            'url' => $this->input->post('url'),
            'text' => $this->input->post('text'),
            'news_id' => $this->input->post('news_id')
        );

        if (!$validation_errors) // there was no validation errors 
        {
            $form_data['ip_address'] = $this->input->ip_address();
        }
        else // there was validation errors
        {
            $form_data['post_error'] = validation_errors();
        }
        return $form_data;
    }

}
