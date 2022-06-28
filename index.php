<?php

define("HRAM0_INIT", "true");

// Make sure these files exist before making this blog public facing.
require("includes/config.php");
include("includes/blog.class.php");
include("includes/entry.class.php");

// Parsedown is optional, it adds support for markdown blog pages, but also adds 40kb to the installation
if (using_parsedown()) {
	include("includes/parsedown.php");
}

/*
 *   BLOG ENGINE STARTING
 */

$page_slug = "";
$pagination = 0;
$tag = "";

function not_blank($var) {
	return (isset($_GET[$var]) && $_GET[$var] !== "");
}

// Requesting a blog page
$format = "";
if (not_blank('page')) {
	$page_slug = $_GET['page'];
	if (not_blank('raw')) {
	   $format = $_GET['raw'];
	   if ($format != 'markdown') {
	   	header("HTTP/1.0 404 Not Found");
		die("HTTP/1.0 404 Not Found");
	   }
	}
}
else {
    $page_slug = Config::HomePage;
}
// Initialise the blog
//

if (not_blank('rss')) {
	$rss = $_GET['rss'];
	$Blog = new Blog("", $pagination, $tag);	
	include("includes/rss.php");
	die();
}

if (not_blank('sitemap')) {
   $Blog = new Blog("", $pagination, $tag);
   include("includes/sitemap.php");
   die();
}

$Blog = new Blog($page_slug, $pagination, $tag);

if ($format == 'markdown') {
   header("Content-Type: text/markdown;");
   echo $Blog->pages[0]->raw;
   die();
}

// Now load the web interface
include("ui.php");

// Once the page has been output by the theme we can kill the process so no other processing gets run
// (may happen on free webhosts that put code at the end of your blog)
die();
