<?php

if ( ! function_exists( 'ppo_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function ppo_post_nav() {
    // Don't print empty markup if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous ) {
        return;
    }
    ?>
    <div class="share-nav">
        <nav class="navigation post-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e( 'Post navigation', SHORT_NAME ); ?></h2>
            <div class="nav-links">
                <?php
                if ( is_attachment() ) :
                    previous_post_link( '%link', __( '<span class="meta-nav">Posted in:</span> %title', SHORT_NAME ) );
                else :
                    previous_post_link( '%link', __( '<span class="meta-nav">← </span> Prevous post', SHORT_NAME ) );
                    next_post_link( '%link', __( 'Next post <span class="meta-nav"> →</span>', SHORT_NAME ) );
                endif;
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
        
        <?php show_share_socials(); ?>
    </div>
    <?php
}
endif;

if ( ! function_exists( 'ppo_posted_on' ) ) :
/**
 * Print HTML with meta information for the current post-date/time and author.
 */
function ppo_posted_on() {
    if ( is_sticky() && is_home() && ! is_paged() ) {
        echo '<span class="featured-post">' . __( 'Sticky post', SHORT_NAME ) . '</span>';
    }

    // Set up and print post meta information.
    printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s"><i class="fa fa-calendar"></i> %3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author"><i class="fa fa-user"></i> %5$s</a></span></span>',
        esc_url( get_permalink() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        get_the_author()
    );
}
endif;

/**
 * Find out if blog has more than one category.
 *
 * @return boolean true if blog has more than 1 category
 */
function ppo_categorized_blog() {
    if (false === ( $all_the_cool_cats = get_transient('ppo_category_count') )) {
        // Create an array of all the categories that are attached to posts
        $all_the_cool_cats = get_categories(array(
            'hide_empty' => 1,
                ));

        // Count the number of categories that are attached to the posts
        $all_the_cool_cats = count($all_the_cool_cats);

        set_transient('ppo_category_count', $all_the_cool_cats);
    }

    if (1 !== (int) $all_the_cool_cats) {
        // This blog has more than 1 category so ppo_categorized_blog should return true
        return true;
    } else {
        // This blog has only 1 category so ppo_categorized_blog should return false
        return false;
    }
}

/**
 * Flush out the transients used in ppo_categorized_blog.
 */
function ppo_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'ppo_category_count' );
}
add_action( 'edit_category', 'ppo_category_transient_flusher' );
add_action( 'save_post',     'ppo_category_transient_flusher' );

if ( ! function_exists( 'ppo_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ...
 * and a Continue reading link.
 *
 * @param string $more Default Read More excerpt link.
 * @return string Filtered Read More excerpt link.
 */
function ppo_excerpt_more($more) {
    $link = sprintf('<a href="%1$s" class="more-link">%2$s</a>', esc_url(get_permalink(get_the_ID())),
        /* translators: %s: Name of current post */ 
        sprintf(__('Xem thêm <span class="meta-nav">&rarr;</span>', SHORT_NAME))
    );
    return ' &hellip; ' . $link;
}

add_filter( 'excerpt_more', 'ppo_excerpt_more' );
endif;
