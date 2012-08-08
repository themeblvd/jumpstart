<?php
/**
 * The template for displaying Comments.
 *
 * @package Theme Blvd WordPress Framework
 */

// Determine if comments should show. 
$show_comments = true;
if( is_single() ) {
	if( themeblvd_get_option( 'single_comments', null, 'show' ) == 'hide' )
		$show_comments = false;
	if( get_post_meta( $post->ID, '_tb_comments', true ) == 'hide' )
		$show_comments = false;
	else if( get_post_meta( $post->ID, '_tb_comments', true ) == 'show' )
		$show_comments = true;
}
// Setup $args for wp_list_comments.
$comment_list_args = array( 
	'avatar_size' => 48,
	'style' => 'ul',
	'type' => 'all',
	'reply_text' => themeblvd_get_local( 'reply' ),
	'login_text' => themeblvd_get_local( 'login_text' ),
	'callback' => null,
	'reverse_top_level' => null,
	'reverse_children' => false
);
?>

<?php if( $show_comments ) : ?>
	<div id="comments">
		<?php if ( post_password_required() ) : ?>
			<p class="nopassword"><?php echo themeblvd_get_local( 'comments_no_password' ); ?></p>
		</div><!-- #comments  (end) -->
		<?php
				/* Stop the rest of comments.php from being processed,
				 * but don't kill the script entirely -- we still have
				 * to fully load the template.
				 */
				return;
			endif;
		?>
		<?php if ( have_comments() ) : ?>
			<h2 id="comments-title">
				<?php
					printf( _n( themeblvd_get_local( 'comments_title_single' ), themeblvd_get_local( 'comments_title_multiple' ), get_comments_number(), 'themeblvd' ),
						number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
				?>
			</h2>
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav id="comment-nav-above">
				<h1 class="assistive-text"><?php echo themeblvd_get_local( 'comment_navigation' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( themeblvd_get_local( 'comments_older' )  ); ?></div>
				<div class="nav-next"><?php next_comments_link( themeblvd_get_local( 'comments_newer' ) ); ?></div>
			</nav>
			<?php endif; // check for comment navigation ?>
			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'themeblvd_comment_list', $comment_list_args ) ); ?>
			</ol>
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav id="comment-nav-below">
				<h1 class="assistive-text"><?php echo themeblvd_get_local( 'comment_navigation' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( themeblvd_get_local( 'comments_older' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( themeblvd_get_local( 'comments_newer' ) ); ?></div>
			</nav>
			<?php endif; // check for comment navigation ?>
		<?php else : // this is displayed if there are no comments so far ?>
			<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>
			<?php else : // or, if we don't have comments:
				/* If there are no comments and comments are closed,
				 * let's leave a little note, shall we?
				 * But only on posts! We don't want the note on pages.
				 */
				if ( ! comments_open() && ! is_page() ) :
				?>
				<p class="nocomments"><?php echo themeblvd_get_local( 'comments_closed' ); ?></p>
				<?php endif; // end ! comments_open() && ! is_page() ?>
			<?php endif; ?>
		<?php endif; ?>
		<?php
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$args = array(
			'fields' => array(
				'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'<label for="author">' . themeblvd_get_local( 'name' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label></p>',
				'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
							'<label for="email">' . themeblvd_get_local( 'email' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label></p>',
				'url'    => '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />' .
							'<label for="url">' .themeblvd_get_local( 'website' ) . '</label></p>'
			),
			'comment_field'			=> '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="10" aria-required="true"></textarea></p>',
			'title_reply'			=> themeblvd_get_local( 'title_reply' ),
			'title_reply_to'		=> themeblvd_get_local( 'title_reply_to' ),
			'cancel_reply_link'		=> themeblvd_get_local( 'cancel_reply_link' ),
			'label_submit'			=> themeblvd_get_local( 'label_submit' )
		);
		?>
		<div class="comment-form-wrapper">
			<div class="comment-form-inner">
				<?php comment_form( apply_filters( 'themeblvd_comment_form', $args ) ); ?>
				<?php //comment_form( $args ); ?>
			</div><!-- .comment-form-inner (end) -->
		</div><!-- .comment-form-wrapper (end) -->
	</div><!-- #comments -->
<?php endif; ?>