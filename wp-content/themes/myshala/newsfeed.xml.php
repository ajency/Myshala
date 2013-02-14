<?php 
header('Content-Type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

$path = $_SERVER['DOCUMENT_ROOT'];
$mypath = '';//'/greekconnect'; //change this later.
$path = $path.$mypath;

require_once $path . '/wp-config.php';

$posts = get_posts(array('numberposts' => 10, 'order'=> 'ASC', 'category_name' => 'announcements'));

echo '<newslist title="Announcements">';

$colors = array('red','green','blue','yellow');

$i = 0;

foreach($posts as $post): setup_postdata($post); 

$i = ($i >= 4)?0:$i;

//trim post title
 	$post_title = (strlen(get_the_title(get_the_ID())) > 40) ? substr(get_the_title(get_the_ID()),0,37) . '[...]' : get_the_title(get_the_ID());

echo '<news category="'.$colors[$i].'" url="'.get_permalink(get_the_ID()).'" date="'.$post->post_date.'">';

echo '<headline>'.$post_title.'</headline>';

echo '<detail>'.get_the_excerpt(get_the_ID()).'</detail>';

echo '</news>';

$i++;

endforeach;

echo '</newslist>';

