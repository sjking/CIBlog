<?php
class News_model extends CI_Model 
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_feed($table = 'news', $limit, $offset)
    {
        $this->db->order_by("{$table}_date", 'desc');
        $this->db->join("{$table}_thumbnail", "{$table}.id = {$table}_thumbnail.{$table}_id");
        $query = $this->db->get($table, $limit, $offset);
        return $query->result_array();
    }

    public function get_total_num_rows($table = 'news')
    {
        return $this->db->get($table)->num_rows();
    }

    /**
     * Returns the total number of rows with given news_id
     * @param string the table
     * @param int the id
     * @return int 
     */
    public function get_num_rows_with_id($table, $id)
    {
        $this->db->where('news_id', $id);
        return $this->db->get($table)->num_rows();
    }

    /**
     * Returns the news item based on the slug
     * @param string the table
     * @param string the news slug
     * @return string
     */
    public function get_news_item($table, $slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    /**
     * Returns an array of images associated with a table  item
     * @param string the table
     * @param string the slug
     * @return string
     */
    public function get_images_for_item($table, $slug)
    {
        $this->db->where("{$table}.slug", $slug);
        $this->db->join($table, "{$table}.id = {$table}_image.{$table}_id");
        $this->db->select('image_id, image_name');
        $query = $this->db->get("{$table}_image");
        return $query->result_array();
    }

    /**
     * Returns the header image for a news item
     * @param string the table
     * @param int the news id
     * @return string the slug for the image
     */
    public function get_header_image_for_item($table, $id)
    {
        $this->db->where("{$table}.id", $id);
        $this->db->join('header_image', "{$table}_image.id = header_image.{$table}_image_id");
        $this->db->join($table, "{$table}.id = header_image.{$table}_id");
        $this->db->select('image_name');
        $query = $this->db->get("{$table}_image");
        if ($row = $query->row())
            return $row->image_name;
        else
            return ''; // fix this later, should have no img tag if no header image
    }

    /**
     * Returns the comments for a news item
     * @param string $table the table to get the comments for
     * @param int the id of the news item
     * @param int the number of comments to retrieve
     * @param int the offset from the beginning
     * @return array the comments 
     */
    public function get_comments_for_item($table, $table_id, $limit, $offset)
    {
        $this->db->where("{$table}_comment.{$table}_id", $table_id);
        $this->db->select('id, comment_timestamp, comment_text, comment_name');
        $query = $this->db->get("{$table}_comment", $limit, $offset);
        return $query->result_array();
    }

    /**
     * Returns the slug for the id
     * @param int
     * @return string
     */
    public function get_slug_for_id($id)
    {
        $this->db->where('id', $id);
        $this->db->select('slug');
        $query = $this->db->get('news');
        return $query->row()->slug;
    }

    /**
     * Inserts a comment into the news_comment table
     * @param array
     */
    public function insert_comment($data)
    {
        $data = array (
            'comment_name' => $data['name'],
            'comment_email' => $data['email'],
            'comment_url' => $data['url'],
            'comment_text' => $data['text'],
            'ip_address' => $data['ip_address'],
            'news_id' => $data['news_id']
        );
        $this->db->insert('news_comment', $data);
    }

}
