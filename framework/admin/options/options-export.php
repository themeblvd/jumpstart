<?php echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n"; ?>
<settings id="<?php echo $this->id; ?>" themeblvd="options" url="<?php echo esc_url(site_url()); ?>" template="<?php echo esc_attr(get_template()); ?>">
<?php foreach( $settings as $id => $setting ) : ?>
	<setting>
		<id><?php echo $id; ?></id>
		<value><![CDATA[<?php echo base64_encode(maybe_serialize($setting)); ?>]]></value>
	</setting>
<?php endforeach; ?>
</settings>
