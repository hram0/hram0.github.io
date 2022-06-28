<?php


if (!defined("HRAM0_INIT")) die();
// First, get these variables purely for ease later on.
$root = Config::Root;
$title = Config::Title;
$footer = Config::Footer;

// Then set up the HTML document
?>
<!DOCTYPE html>
<html lang="en">
<title><?php echo $Blog->get_title(); ?></title>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="style/prism.css" rel="stylesheet"/>
<link href="style/theorems.css" rel="stylesheet"/>  
</head>
<style>
blockquote {
	font-style: italic;
	color: #555;
	padding-left: 32px;
	border-left: 3px solid <?php echo $colour; ?>;
}

body {
  margin: 40px;
  font-family: Arial, Helvetica, sans-serif;  
}

.menu {
    /*min-width: 170px;*/
    grid-area: menu;
   font-size: 1.25vw;
  }

  .content {
    grid-area: content;
  }

  .header {
    grid-area: header;
  }


  .wrapper {
    display: grid;
    grid-gap: 10px;
    grid-template-columns: 15%  85%;
    grid-template-areas:
               "....... header"
               "menu content";
    background-color: #ffffff;
    color: #000000;
  }
.box {
  background-color: #EEEEEE;
  color: #000000;
  border-radius: 5px;
  border-style: dashed;  
  padding: 1.25vw;
}

.header {
  background-color: #02994D;
  color: white;
  border-color: black;
}

ul.vertical {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 7vw;
    /*width: 170px;*/
    background-color: #f1f1f1;
}

ul.vertical li a {
    display: block;
    color: #000;
    padding: 0px 0 8px 0px;
    text-decoration: none;
    border-bottom: 2px solid #000000;
}

table {
  border-collapse: collapse;
}

tr.row td {
    border-bottom: 2px solid #000000;
}

pre {
    width: 76.5%;
    border: 2px solid #000000;
}

ul.vertical li a:hover:not(.active) {
    background-color: #555;
    color:white;
}

ul.vertical a.active {
    background-color: #02994D;
    color:white;
}



</style>
<script src="js/prism.js" defer></script>

<?php
echo "
<div class='wrapper'>
<div class='box header'><b>HRAM0</b> | Model and Toolchain for Research in Memory Safety</div>
<div class='box menu'>
<center><ul class='vertical'>";
$menu = Config::Menu;
foreach ($menu as $item) {
    $label = $item[0];
    $slug = $item[1];
    $url = "{$root}?page={$slug}";
    echo "<li><a ";
    if ($slug == $page_slug) {
        echo "class='active' ";
    }
    echo "href=\"{$url}\">{$label}</a></li>\n";
}

echo "</ul>\n</div>\n</center>\n";
echo "<div class='box content'>";
// Check what page we're on and display the relevant content.
if ($Blog->url === Url::Error404) {
	echo "<h1>Error 404: Page Not Found</h1>";
} else if (count($Blog->pages) === 0) {
	// Only display this if there are no pages in the pages directory
	echo "<h2>No Pages Found</h2>";
} else {
	// If there wasn't an error, loop through the pages we got.
	// If we're viewing a single page, there will be one element in the array.
	foreach ($Blog->pages as $entry) {

	    // Otherwise just include the page's featured image, title and content.
	    echo "<article style='margin-top: 32px;'>";
	    if ($entry->has_image()) {
	        echo "<img src='{$entry->image}' />";
	    }
	    echo "<h1>{$entry->title}</h1>";
	    echo "<u>Last Updated:</u> <time datetime={$entry->date_datetime()}>{$entry->date_pretty()}</time>";
	    echo $entry->content;
	    echo "</article>";
	    if (count($entry->updates) > 0) {
	        echo "<hr/><h3>Updates</h3>";
		$index = 1;
		foreach ($entry->updates as $update) {
			echo "<article style='margin-top: 32px;'>";
			echo "<h4>Update {$index}</h4>";
				   
		        echo "<time datetime={$update->date_datetime()}>{$update->date_pretty()}</time>";
			echo $update->content;
			echo "</article>";
			$index++;
		}
	    }

	}
}
echo "</div>
      </div>";
// Display the footer text at the bottom of each page.
echo "<footer style='margin-top:32px;'>{$footer}
</footer>";

?>
<!DOCTYPE html>
<script src="js/math_code.js"></script>
<script async
  src="//mathjax.rstudio.com/latest/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
<?php
echo "</html>";
