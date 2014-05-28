<?php
/*
$str = "Hello {image1}, my name is {image2}.";
$num = 1;
$str = preg_replace("/{image" . $num  . "}/", 'Bob', $str);
echo $str;
 */
/*
// test getimagesize() function
$filename = "images/news/news_heading_13-ni13-2.jpg";
$size = getimagesize($filename);
print_r($size);
 */

$str = 'hypertext language programming is fun for the whole family.
{bob_ble-2}
You can program with grandma, grandpa, and little Timmy.';

$pattern = '/\n/';

$paragraphs = preg_split($pattern, $str);

$format_str = '';

$doc_tag = '/{[a-zA-Z0-9-_]+}/';

foreach ($paragraphs as $paragraph)
{ 
    if (!preg_match($doc_tag, $paragraph))
    {
        $paragraph = '<p>' . $paragraph . '</p>';
    }
    $format_str .= $paragraph;
}   

echo $format_str;

print_r($paragraphs);
?>
