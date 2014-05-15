<?php echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n"; ?>
<settings id="<?php echo $this->id; ?>" themeblvd="options" url="<?php echo site_url(); ?>" template="<?php echo get_template(); ?>">
<?php foreach( $settings as $id => $setting ) : ?>
	<setting>
		<id><?php echo $id; ?></id>
		<value><![CDATA[<?php echo maybe_serialize($setting); ?>]]></value>
	</setting>
<?php endforeach; ?>
</settings>