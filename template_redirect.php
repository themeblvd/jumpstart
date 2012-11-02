<?php
/**
 * Template Name: Redirect
 *
 * WARNING: This template file is a core part of the 
 * Theme Blvd WordPress Framework. It is advised
 * that any edits to the way this file displays its
 * content be done with via hooks, filters, and
 * template parts.
 * 
 * USAGE INSTRUCTIONS:
 * 
 * 1. Create a new page.
 * 2. Add a title to the page.
 * 3. Add an URL to the content of the page (e.g. http://www.google.com OR google.com OR www.google.com)
 * 4. Select "Redirect" as the page template
 * 5. Publish!
 * 
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
?>
<?php if (have_posts()) : the_post(); ?>
<?php $URL = get_the_excerpt(); if (!preg_match('/^http:\/\//', $URL)) $URL = 'http://' . $URL; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Refresh" content="0; url=<?php echo $URL; ?>">
</head>

<body>
</body>
</html>

<?php endif; ?>