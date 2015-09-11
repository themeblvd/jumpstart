<?php
/**
 * The template for displaying Comments.
 *
 * @package Theme Blvd WordPress Framework
 */
?>
<?php if ( themeblvd_show_comments() ) : ?>

	<?php if ( post_password_required() ) : ?>
		<div id="comments" class="nopassword">
			<p><?php echo themeblvd_get_local('comments_no_password'); ?></p>
		</div><!-- #comments  (end) -->
		<?php return; ?>
	<?php endif; ?>

	<div id="comments">

		<?php if ( have_comments() ) : ?>

			<!-- COMMENTS (start) -->

			<h2 id="comments-title">
				<?php
					printf( _n( themeblvd_get_local('comments_title_single'), themeblvd_get_local('comments_title_multiple'), get_comments_number() ),
						number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
				?>
			</h2>
			<ol class="commentlist">
				<?php wp_list_comments( themeblvd_get_comment_list_args() ); ?>
			</ol>
			<?php if ( get_comment_pages_count() > 1 && get_option('page_comments') ) : // are there comments to navigate through ?>
				<nav id="comment-nav-below">
					<h1 class="assistive-text"><?php echo themeblvd_get_local('comment_navigation' ); ?></h1>
					<div class="nav-previous"><?php previous_comments_link( themeblvd_get_local('comments_older') ); ?></div>
					<div class="nav-next"><?php next_comments_link( themeblvd_get_local('comments_newer') ); ?></div>
				</nav>
			<?php endif; ?>

			<!-- COMMENTS (end) -->

		<?php endif; // end if has_comments() ?>

		<?php if ( comments_open() ) : ?>

			<!-- COMMENT FORM (start) -->

			<div class="comment-form-wrapper">
				<div class="comment-form-inner">
					<?php comment_form( themeblvd_get_comment_form_args() ); ?>
				</div><!-- .comment-form-inner (end) -->
			</div><!-- .comment-form-wrapper (end) -->

			<!-- COMMENT FORM (end) -->

		<?php else : ?>

			<p class="nocomments"><?php echo themeblvd_get_local('comments_closed'); ?></p>

		<?php endif; ?>

	</div><!-- #comments -->

<?php endif; ?>
