<?php $text = themeblvd_get_local( 'search' ); ?>
<div class="themeblvd-search">
    <form method="get" action="<?php echo home_url( '/' ); ?>">
        <fieldset>
            <span class="input-wrap">
            	<input type="text" class="search-input" name="s" onblur="if (this.value == '') {this.value = '<?php echo $text; ?>';}" onfocus="if(this.value == '<?php echo $text; ?>') {this.value = '';}" value="<?php echo $text; ?>">
            </span>
            <span class="submit-wrap">
            	<input type="submit" class="submit" value="">
            </span>
        </fieldset>
    </form>
</div>
