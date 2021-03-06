<?php
/**
 * Custom template tags.
 *
 * @package  WordPress
 * @subpackage  Boot
 */


/**
 * WP Bootstrap Breadcrumbs
 * @package WP-Bootstrap-Breadcrumbs
 *
 * Description: A custom WordPress nav walker class to implement the Bootstrap 4 breadcrumbs style in a custom theme using the WordPress.
 * Author: Dimox - @Dimox, Alexsander Vyshnyvetskyy - @alex-wdmg
 * Version: 1.1.0
 * Author URI: https://github.com/Dimox
 * Author URI: https://github.com/alex-wdmg
 * GitHub Gist URI: https://gist.github.com/alex-wdmg/21e150e00f327215ee3ad5d0ca669b17
 * License: MIT
 */

/**
 * Modified to be compatible with Bootstrap 5.
 */

/**
 * @param array $args
 */
function boot_breadcrumbs( $args = array() ) {

	$defaults = array(
		'wrap_before'    => '<ol class="breadcrumb" id="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">',
		'wrap_after'     => '</ol><!-- .breadcrumbs -->',
		'separator'      => '<span class="badge bg-white text-dark rounded-0 border-0 fw-bold pt-2">|</span>',
		'before'         => '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">',
		'before_active'  => '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">',
		'after'          => '</li>',
		'link'           => '<a href="%1$s" title="%2$s" itemscope itemtype="http://schema.org/Thing" itemprop="item" itemid="%1$s">%3$s</a>',
		'active'         => '<span itemscope itemtype="http://schema.org/Thing" itemprop="item" itemid="%1$s">%2$s</span>',
		'name'           => '<span itemprop="name">%1$s</span>',
		'position'       => '<meta itemprop="position" content="%1$s">',
		'show_on_home'   => false,
		'show_home_link' => true,
		'show_current'   => true,
		'show_last_sep'  => true,
		'text'           => array(
			'home'     => __( 'Home' ),
			'category' => '%s',
			'search'   => __( 'Search results for "%s"' ),
			'tag'      => __( 'Post by tag "%s"' ),
			'author'   => __( 'Posts by author %s' ),
			'404'      => __( 'Error 404' ),
			'page'     => __( 'Page %s' ),
			'cpage'    => __( 'Comments Page %s' )
		)
	);

	$args = wp_parse_args(
		$args,
		apply_filters( 'boot_breadcrumbs_defaults', $defaults )
	);

	global $post;
	$home_url  = home_url( '/' );
	$parent_id = ( $post ) ? $post->post_parent : '';

	$link      = $args['before'];
	$link      .= sprintf( $args['link'], $home_url, $args['text']['home'], sprintf( $args['name'], $args['text']['home'] ) );
	$link      .= sprintf( $args['position'], 1 );
	$link      .= $args['after'];
	$home_link = $link;

	if ( is_home() || is_front_page() ) {

		if ( $args['show_on_home'] ) {
			echo $args['wrap_before'] . $home_link . $args['wrap_after'];
		}

	} else {

		$position = 0;
		echo $args['wrap_before'];

		if ( $args['show_home_link'] ) {
			$position ++;
			echo $home_link;
		}

		if ( is_category() ) {
			$parents = get_ancestors( get_query_var( 'cat' ), 'category' );
			foreach ( array_reverse( $parents ) as $cat ) {

				$position ++;

				if ( $position > 1 ) {
					echo $args['separator'];
				}

				$link = $args['before'];
				$link .= sprintf( $args['link'], get_category_link( $cat ), get_cat_name( $cat ), sprintf( $args['name'], get_cat_name( $cat ) ) );
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $link;
			}
			if ( get_query_var( 'paged' ) ) {
				$position ++;
				$cat  = get_query_var( 'cat' );
				$link = $args['before'];
				$link .= sprintf( $args['link'], get_category_link( $cat ), get_cat_name( $cat ), sprintf( $args['name'], get_cat_name( $cat ) ) );
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $args['separator'] . $link;
				echo $args['separator'] . $args['before'] . sprintf( $args['text']['page'], get_query_var( 'paged' ) ) . $args['after'];
			} else {
				if ( $args['show_current'] ) {

					if ( $position >= 1 ) {
						echo $args['separator'];
					}

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['name'], sprintf( $args['text']['category'], single_cat_title( '', false ) ) ) ) . sprintf( $args['position'], $position ) . $args['after'];

				} else if ( $args['show_last_sep'] ) {
					echo $args['separator'];
				}
			}
		} else if ( is_search() ) {
			if ( get_query_var( 'paged' ) ) {

				$position ++;

				if ( $args['show_home_link'] ) {
					echo $args['separator'];
				}

				$link = $args['before'];
				$link .= sprintf(
					$args['link'],
					$home_url . '?s=' . get_search_query(),
					sprintf( $args['text']['search'], get_search_query() ),
					sprintf( $args['name'], sprintf( $args['text']['search'], get_search_query() ) )
				);
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $link;
				echo $args['separator'] . $args['before'] . sprintf( $args['text']['page'], get_query_var( 'paged' ) ) . $args['after'];

			} else {
				if ( $args['show_current'] ) {

					if ( $position >= 1 ) {
						echo $args['separator'];
					}

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['search'], get_search_query() ) ) . sprintf( $args['position'], $position ) . $args['after'];

				} else if ( $args['show_last_sep'] ) {
					echo $args['separator'];
				}
			}
		} else if ( is_year() ) {
			if ( $args['show_home_link'] && $args['show_current'] ) {
				echo $args['separator'];
			}

			if ( $args['show_current'] ) {
				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_time( 'Y' ) ) . sprintf( $args['position'], $position ) . $args['after'];

			} else if ( $args['show_home_link'] && $args['show_last_sep'] ) {
				echo $args['separator'];
			}
		} else if ( is_month() ) {
			if ( $args['show_home_link'] ) {
				echo $args['separator'];
			}

			$position ++;

			$link = $args['before'];
			$link .= sprintf(
				$args['link'],
				get_year_link( get_the_time( 'Y' ) ),
				get_the_time( 'Y' ),
				sprintf( $args['name'], get_the_time( 'Y' ) )
			);
			$link .= sprintf( $args['position'], $position );
			$link .= $args['after'];
			echo $link;

			if ( $args['show_current'] ) {
				$position ++;
				echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_time( 'F' ) ) . sprintf( $args['position'], $position ) . $args['after'];
			} else if ( $args['show_last_sep'] ) {
				echo $args['separator'];
			}
		} else if ( is_day() ) {
			if ( $args['show_home_link'] ) {
				echo $args['separator'];
			}

			$position ++;

			$link = $args['before'];
			$link .= sprintf(
				$args['link'],
				get_year_link( get_the_time( 'Y' ) ),
				get_the_time( 'Y' ),
				sprintf( $args['name'], get_the_time( 'Y' ) )
			);
			$link .= sprintf( $args['position'], $position );
			$link .= $args['after'];
			echo $link;

			$position ++;

			$link = $args['before'];
			$link .= sprintf(
				$args['link'],
				get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
				get_the_time( 'F' ),
				sprintf( $args['name'], get_the_time( 'F' ) )
			);
			$link .= sprintf( $args['position'], $position );
			$link .= $args['after'];
			echo $link;

			if ( $args['show_current'] ) {
				$position ++;
				echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_time( 'd' ) ) . sprintf( $args['position'], $position ) . $args['after'];
			} else if ( $args['show_last_sep'] ) {
				echo $args['separator'];
			}
		} else if ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$position ++;
				$post_type = get_post_type_object( get_post_type() );

				if ( $position > 1 ) {
					echo $args['separator'];
				}

				$link = $args['before'];
				$link .= sprintf(
					$args['link'],
					get_post_type_archive_link( $post_type->name ),
					$post_type->labels->name,
					sprintf( $args['name'], $post_type->labels->name )
				);
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $link;

				if ( $args['show_current'] ) {
					$position ++;
					echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_title() ) . sprintf( $args['position'], $position ) . $args['after'];
				} else if ( $args['show_last_sep'] ) {
					echo $args['separator'];
				}
			} else {
				$cat       = get_the_category();
				$catID     = $cat[0]->cat_ID;
				$parents   = get_ancestors( $catID, 'category' );
				$parents   = array_reverse( $parents );
				$parents[] = $catID;
				foreach ( $parents as $cat ) {
					$position ++;

					if ( $position > 1 ) {
						echo $args['separator'];
					}

					$link = $args['before'];
					$link .= sprintf(
						$args['link'],
						get_category_link( $cat ),
						get_cat_name( $cat ),
						sprintf( $args['name'], get_cat_name( $cat ) )
					);
					$link .= sprintf( $args['position'], $position );
					$link .= $args['after'];
					echo $link;
				}

				if ( get_query_var( 'cpage' ) ) {
					$position ++;

					$link = $args['before'];
					$link .= sprintf(
						$args['link'],
						get_permalink(),
						get_the_title(),
						sprintf( $args['name'], get_the_title() )
					);
					$link .= sprintf( $args['position'], $position );
					$link .= $args['after'];
					echo $args['separator'] . $link;

					$position ++;
					echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['cpage'], get_query_var( 'cpage' ) ) ) . sprintf( $args['position'], $position ) . $args['after'];
				} else {
					$position ++;

					if ( $args['show_current'] ) {
						echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['name'], get_the_title() ) ) . sprintf( $args['position'], $position ) . $args['after'];
					} else if ( $args['show_last_sep'] ) {
						echo $args['separator'];
					}

				}
			}
		} else if ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_post_type() );
			if ( get_query_var( 'paged' ) ) {

				$position ++;

				if ( $position > 1 ) {
					echo $args['separator'];
				}

				$link = $args['before'];
				$link .= sprintf(
					$args['link'],
					get_post_type_archive_link( $post_type->name ),
					$post_type->label,
					sprintf( $args['name'], $post_type->label )
				);
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $link;

				$position ++;
				echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['page'], get_query_var( 'paged' ) ) ) . sprintf( $args['position'], $position ) . $args['after'];
			} else {
				if ( $args['show_home_link'] && $args['show_current'] ) {
					echo $args['separator'];
				}

				if ( $args['show_current'] ) {
					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), $post_type->label ) . sprintf( $args['position'], $position ) . $args['after'];
				} else if ( $args['show_home_link'] && $args['show_last_sep'] ) {
					echo $args['separator'];
				}
			}
		} else if ( is_attachment() ) {
			$parent    = get_post( $parent_id );
			$cat       = get_the_category( $parent->ID );
			$catID     = $cat[0]->cat_ID;
			$parents   = get_ancestors( $catID, 'category' );
			$parents   = array_reverse( $parents );
			$parents[] = $catID;
			foreach ( $parents as $cat ) {
				$position ++;

				if ( $position > 1 ) {
					echo $args['separator'];
				}

				$link = $args['before'];
				$link .= sprintf(
					$args['link'],
					get_category_link( $cat ),
					get_cat_name( $cat ),
					sprintf( $args['name'], get_cat_name( $cat ) )
				);
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $link;
			}

			$position ++;

			$link = $args['before'];
			$link .= sprintf(
				$args['link'],
				get_permalink( $parent ),
				$parent->post_title,
				sprintf( $args['name'], $parent->post_title )
			);
			$link .= sprintf( $args['position'], $position );
			$link .= $args['after'];
			echo $args['separator'] . $link;

			if ( $args['show_current'] ) {
				$position ++;
				echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_title() ) . sprintf( $args['position'], $position ) . $args['after'];
			} else if ( $args['show_last_sep'] ) {
				echo $args['separator'];
			}
		} else if ( is_page() && ! $parent_id ) {
			if ( $args['show_home_link'] && $args['show_current'] ) {
				echo $args['separator'];
			}

			if ( $args['show_current'] ) {
				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_title() ) . sprintf( $args['position'], $position ) . $args['after'];

			} else if ( $args['show_home_link'] && $args['show_last_sep'] ) {
				echo $args['separator'];
			}
		} else if ( is_page() && $parent_id ) {
			$parents = get_post_ancestors( get_the_ID() );
			foreach ( array_reverse( $parents ) as $pageID ) {
				$position ++;

				if ( $position > 1 ) {
					echo $args['separator'];
				}

				$link = $args['before'];
				$link .= sprintf(
					$args['link'],
					get_page_link( $pageID ),
					get_the_title( $pageID ),
					sprintf( $args['name'], get_the_title( $pageID ) )
				);
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $link;
			}

			if ( $args['show_current'] ) {
				$position ++;
				echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_title() ) . sprintf( $args['position'], $position ) . $args['after'];
			} else if ( $args['show_last_sep'] ) {
				echo $args['separator'];
			}
		} else if ( is_tag() ) {
			if ( get_query_var( 'paged' ) ) {
				$position ++;
				$tagID = get_query_var( 'tag_id' );

				$link = $args['before'];
				$link .= sprintf(
					$args['link'],
					get_tag_link( $tagID ),
					single_tag_title( '', false ),
					sprintf( $args['name'], single_tag_title( '', false ) )
				);
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $args['separator'] . $link;

				$position ++;
				echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['page'], get_query_var( 'paged' ) ) ) . sprintf( $args['position'], $position ) . $args['after'];
			} else {
				if ( $args['show_home_link'] && $args['show_current'] ) {
					echo $args['separator'];
				}

				if ( $args['show_current'] ) {
					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['tag'], single_tag_title( '', false ) ) ) . sprintf( $args['position'], $position ) . $args['after'];
				} else if ( $args['show_home_link'] && $args['show_last_sep'] ) {
					echo $args['separator'];
				}
			}
		} else if ( is_author() ) {
			$author = get_userdata( get_query_var( 'author' ) );
			if ( get_query_var( 'paged' ) ) {

				$position ++;

				$link = $args['before'];
				$link .= sprintf(
					$args['link'],
					get_author_posts_url( $author->ID ),
					$author->display_name,
					sprintf( $args['name'], sprintf( $args['text']['author'], $author->display_name ) )
				);
				$link .= sprintf( $args['position'], $position );
				$link .= $args['after'];
				echo $args['separator'] . $link;

				$position ++;
				echo $args['separator'] . $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['page'], get_query_var( 'paged' ) ) ) . sprintf( $args['position'], $position ) . $args['after'];

			} else {
				if ( $args['show_home_link'] && $args['show_current'] ) {
					echo $args['separator'];
				}

				if ( $args['show_current'] ) {
					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['author'], $author->display_name ) ) . sprintf( $args['position'], $position ) . $args['after'];
				} else if ( $args['show_home_link'] && $args['show_last_sep'] ) {
					echo $args['separator'];
				}
			}
		} else if ( is_404() ) {
			if ( $args['show_home_link'] && $args['show_current'] ) {
				echo $args['separator'];
			}

			if ( $args['show_current'] ) {
				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), $args['text']['404'] ) . sprintf( $args['position'], $position ) . $args['after'];
			} else if ( $args['show_last_sep'] ) {
				echo $args['separator'];
			}
		} else if ( has_post_format() && ! is_singular() ) {
			if ( $args['show_home_link'] && $args['show_current'] ) {
				echo $args['separator'];
			}

			echo get_post_format_string( get_post_format() );
		}

		echo $args['wrap_after'];
	}
}

/**
 * Custom comments form.
 */
function boot_comment_form() {

	$comments_args = array(
		'format'               => 'xhtml',
		'comment_notes_before' => '<p>' . __( 'Your email address will not be published.' ) . '</p>',
		'class_submit'         => 'btn btn-outline-dark rounded-0',
		'fields'               => array(
			'author' => '<div class="form-floating mb-3">
							<input placeholder="' . __( 'Author' ) . '" class="form-control rounded-0" type="text" id="author" name="author" required>
							<label for="author">' . __( 'Author' ) . ' (' . __( 'required' ) . ')</label>
						</div>',
			'email'  => '<div class="form-floating mb-3">
							<input placeholder="' . __( 'Email' ) . '" class="form-control rounded-0" type="email" id="email" name="email" required>
							<label for="email">' . __( 'Email' ) . ' (' . __( 'required' ) . ')</label>
						</div>',
			'url'    => '<div class="form-floating mb-3">
							<input placeholder="' . __( 'Url' ) . '" class="form-control rounded-0" type="url" id="url" name="url">
							<label for="url">' . __( 'Url' ) . '</label>
						</div>',
		),
		'comment_field'        => '<div class="form-floating mb-3">
								<textarea placeholder="' . __( 'Comment' ) . '" class="form-control rounded-0" id="comment" name="comment" style="height: 150px" required></textarea>
								<label for="comment">' . __( 'Comment' ) . ' (' . __( 'required' ) . ')</label>
								</div>',
	);

	return comment_form( $comments_args );
}
