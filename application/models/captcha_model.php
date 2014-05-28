<?php
class Captcha_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Inserts captcha data into database
     * @param array
     */
    public function insert_data($data)
    {
        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);
    }

    /**
     * Checks whether the input matches the captcha in the db
     * @param array
     * @bool
     */
    public function is_valid($data)
    {
        $expiration = time() - 7200; // 2 hour lifespan
        
        // remove expired captchas from the filesystem done automagically
        // remove expired captchas from the database
        $this->db->where('captcha_time <', $expiration);
        $this->db->delete('captcha');

        // check if its in the database
        $this->db->where($data);
        $this->db->where('captcha_time >', $expiration);
        if ($this->db->count_all_results('captcha') == 0)
        { 
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }    
}
