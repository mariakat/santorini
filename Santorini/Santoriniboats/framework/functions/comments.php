<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

if ( ! function_exists( 'us_comment_start' ) ) {
	function us_comment_start( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$author_link_start = $author_link_end = '';
		$author_url = get_comment_author_url();
		if ( $author_url != '' ) {
			$author_link_start = '<a href="' . $author_url . '" target="_blank" rel="nofollow">';
			$author_link_end = '</a>';
		}
		?>
		<div <?php comment_class( 'w-comments-item' ) ?> id="comment-<?php comment_ID() ?>">
			<div class="w-comments-item-meta">
				<?php echo $author_link_start; ?>
				<div class="w-comments-item-icon">
					<?php echo get_avatar( $comment, $size = '50' ); ?>
				</div>
				<div class="w-comments-item-author"><span><?php echo get_comment_author(); ?></span></div>
				<?php echo $author_link_end; ?>
				<a class="w-comments-item-date smooth-scroll" href="#comment-<?php comment_ID() ?>"><?php echo get_comment_date() . ' ' . get_comment_time() ?></a>
			</div>
			<div class="w-comments-item-text">
				<?php if ( $comment->comment_approved == '0') { ?>
					<div class="w-message color_yellow"><?php echo us_translate( 'Your comment is awaiting moderation.' ) ?></div>
				<?php }
				comment_text(); ?>
			</div>
			<?php comment_reply_link(
				array_merge(
					$args,
					array( 'depth' => $depth )
				)
			) ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'us_comment_end' ) ) {
	function us_comment_end( $comment, $args, $depth ) {
		return;
	}
}
