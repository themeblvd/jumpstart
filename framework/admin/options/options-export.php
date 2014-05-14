<?php echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n"; ?>
<settings id="<?php echo $this->id; ?>" themeblvd="options" url="<?php echo site_url(); ?>" template="<?php echo get_template(); ?>">
<?php foreach( $settings as $id => $setting ) :

	if ( is_array( $setting ) ) :
		$type = 'array';
		$setting = serialize( $setting );
	else :
		$type = 'string';
	endif;
	?>
	<setting>
		<id><?php echo $id; ?></id>
		<type><?php echo $type; ?></type>
		<value><![CDATA[<?php echo $setting; ?>]]></value>
	</setting>
<?php endforeach; ?>
</settings>