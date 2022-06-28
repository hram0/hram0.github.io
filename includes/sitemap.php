<?php
if (!defined("HRAM0_INIT")) die();

function echo_url($url, $date, $change, $priority) {
	echo "\n<url><loc>{$url}</loc><lastmod>{$date}</lastmod><changefreq>{$change}</changefreq><priority>{$priority}</priority></url>";
}

$base_url = get_base_url() . Config::Root;
$lastBuildDate = gmdate("c", $Blog->pages[0]->timestamp);

header("Content-Type: text/xml;");
echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo_url($base_url, $lastBuildDate, "weekly", "1.0");

foreach ($Blog->pages as $key => $page) {
	echo_url("{$base_url}?page={$page->slug}", gmdate("c", $page->edited), "monthly", "0.5");
}

echo "\n</urlset>";
