<div class="themeblvd-search">
    <form method="get" action="<?php echo home_url( '/' ); ?>">
		<div class="input-group">
			<input type="text" class="form-control search-input" name="s" placeholder="<?php echo themeblvd_get_local( 'search' ); ?>" />
			<span class="input-group-btn">
				<button class="<?php echo themeblvd_get_button_class( 'default' ); ?>" type="submit">
					<i class="icon-search"></i>
				</button>
			</span>
		</div>
	</form>
</div>