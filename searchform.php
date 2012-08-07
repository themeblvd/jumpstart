<?php $text = themeblvd_get_local( 'search' ); ?>
<div class="themeblvd-search">
    <form method="get" action="<?php echo home_url( '/' ); ?>">
		<div class="controls">
			<div class="input-append">
				<input type="text" class="search-input" name="s" placeholder="<?php echo $text; ?>" /><button class="btn btn-default" type="submit"><i class="icon-search"></i></button>
			</div>
		</div>
    </form>
</div>