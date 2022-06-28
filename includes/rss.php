<?php

if (!defined("HRAM0_INIT")) die();

function rss_xml($Blog) {
	$title = Config::Title;
	$url = get_base_url() . Config::Root;
	$lastBuildDate = gmdate(DATE_RFC2822, $Blog->posts[0]->timestamp);
	header("Content-Type: application/rss+xml;");
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
<channel><title>{$title}</title><atom:link href=\"{$url}?rss=xml\" rel=\"self\" type=\"application/rss+xml\" />
<description>{$title}</description><link>{$url}</link>
<lastBuildDate>{$lastBuildDate}</lastBuildDate>";
	foreach ($Blog->posts as $key => $post) {
		if (!in_array('invisible', $post->tags)) {
		   $pubDate = gmdate(DATE_RFC2822, $post->timestamp);
		   echo "<item>";
		   echo "<title>{$post->title}</title>";
		   echo "<pubDate>{$pubDate}</pubDate>";
		   echo "<link>{$url}?post={$post->slug}</link>";
		   echo "<guid>{$url}?post={$post->slug}</guid>";
		   if ($post->summary !== "") { echo "<description>{$post->summary}</description>"; }
		   foreach ($post->tags as $key => $category) {
		   	   echo "<category>{$category}</category>";
		   }
		   echo "</item>";
		}
	}
	echo "</channel></rss>";
}

switch ($rss) {
	case "xml":
		rss_xml($Blog);
		break;
	case "json":
		header("Content-Type: application/json;");
		$vis_posts = [];
		foreach ($Blog->posts as $post) {
			if (!in_array('invisible', $post->tags)) {
			   $vis_posts[] = $post;
			}
		}
		echo json_encode($vis_posts);
		break;
	default:
		header("HTTP/1.0 404 Not Found");
		die("HTTP/1.0 404 Not Found");
		break;
}
?>
