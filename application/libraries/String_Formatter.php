<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * String Formatter
 *
 * @package Blog
 * @subpackage Libraries
 * @category Libraries
 * @author Steve King
 */
class String_Formatter 
{
    public function __construct()
    {
        //$this->$CI =& get_instance();
    }

    /**
     * Returns a substring from $str of length $len with ellipsis: '...'
     * 
     * @access public
     * @param string
     * @param int
     * @return string
     */
    public function ellipsis($str, $len)
    {
        if (strlen($str) > $len)
        {
            // remove any html tags (eg. <p>)
            $str = preg_replace('/(<\/*[a-z]+>)/', '', $str);
            // remove special {image*} tags
            $str = preg_replace('/{image[0-9]+}/', '', $str); 
            
            $str = substr($str, 0, $len) . '...';
        }
        return htmlspecialchars($str);
    }

    /**
     * Encloses all paragraphs inside of <p> tags
     * @param string the string to replace tags in
     * @return string
     */
    public function html_paragraph($str)
    {
        $str = htmlspecialchars($str);
        $doc_tag = '/{[a-zA-Z0-9-_]+}/'; // tags for {image} etc...
        $paragraphs = preg_split('/\n/', $str);
        $format_str = '';
        foreach ($paragraphs as $paragraph)
        {
            if (!preg_match($doc_tag, $paragraph))
            {
                $paragraph = '<p>' . $paragraph . '</p>';
            }
            $format_str .= $paragraph;
        }
        return $format_str;
    }     
    
    /**
     * Replaces any {image} tags with html tags 
     * @param array $images the array of images for the news item
     * @param string $str the string to replace tags in
     * @return string 
     */
    public function replace_image_tag($images, $str)
    {
        $CI =& get_instance(); 
        $CI->config->load('news_config');
        $width = $CI->config->item('image_width');
        
        foreach ($images as $img)
        {
//            $regex = "/{image" . ($img['image_id'] - 1) . "}/";
            $regex = "/{image" . $img['image_id'] . "}/";

            $img_width = strval($width);
            $img_height = $this->_calculate_resized_height($img['image_name'], $img_width);

/*            $img_link = base_url('images/news') . '/' . $img['image_name'];
            $size = getimagesize($img_link);
            $orig_width = $size[0];
            $orig_height = $size[1];
            $img_height = strval($this->_calculate_height($width, $orig_width, $orig_height));
            $img_width = strval($width);
*/
            $img_properties = array (
                'src' => 'images/news/' . $img['image_name'],
                'class' => 'news_image',
                'width' => $img_width,
                'height' => $img_height,
                'rel' => 'lightbox'
            );

            $tag = anchor('images/news/' . $img['image_name'], img($img_properties), 'target="_blank"');
            $tag = "<div id=\"news_image\">" . $tag . "</div>";
            $str = preg_replace($regex, $tag, $str); 
        }
        return $str;
    }

    /**
     * Calculate the height of image based on width
     * @param int $width width of image 
     * @param int $orig_width original width of image
     * @param int $orig_height original height of image
     * @return int $height new heigh of image
     */
/*    private function _calculate_height($width, $orig_width, $orig_height)
    {
        $factor = $width / $orig_width;
        $height = $orig_height * $factor;
        return round($height);
    }
*/            
    /**
     * Return a formatted date, eg: March 7th 2012
     * @param $string
     * @return $string
     */
    public function date_formatter($dateStr)
    {
        $date = new DateTime($dateStr);
        return $date->format('F jS Y');
    }

    /**
     * Returns the calculated height of image
     * @param string
     * @param int
     * @return int
     */
    public function get_height_of_header_image($img, $width)
    {
        return $this->_calculate_resized_height($img, $width);

    }

    /**
     * Calculate the resized height of image
     * @param string
     * @param int
     * @return int
     */
    private function _calculate_resized_height($img, $width)
    {
        //$img_link = base_url('images/news') . '/' . $img;
        $img_link = 'images/news' . '/' . $img;
        $size = getimagesize($img_link);
        $orig_width = $size[0];
        $orig_height = $size[1];

        $factor = $width / $orig_width;
        $height = $orig_height * $factor;
        return round($height);
    }
}
/* End of file StringFormatter.php */
