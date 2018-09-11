<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 *
 * @Packge      Colorlib
 * @Author      Colorlib
 * @Author URL  https//www.Colorlib.com
 * @version     1.0
 *
 */

function mosh_pagination( $args = array() ) {
    
    $defaults = array(
        'range'           => 4,
        'custom_query'    => FALSE,
        'previous_string' => esc_html__( '&laquo;', 'mosh' ),
        'next_string'     => esc_html__( '&raquo;', 'mosh' ),
        'before_output'   => '<div class="mosh-pagination-area col-12"><nav><ul class="pagination">',
        'after_output'    => '</ul></nav></div>'
    );
    
    $args = wp_parse_args( 
        $args, 
        apply_filters( 'mosh_pagination_defaults', $defaults )
    );
    
    $args['range'] = (int) $args['range'] - 1;
    if ( !$args['custom_query'] )
        $args['custom_query'] = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : '';
    $count = (int) $args['custom_query']->max_num_pages;
    $page  = intval( get_query_var( 'paged' ) );
    $ceil  = ceil( $args['range'] / 2 );
    
    if ( $count <= 1 )
        return FALSE;
    
    if ( !$page )
        $page = 1;
    
    if ( $count > $args['range'] ) {
        if ( $page <= $args['range'] ) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ( $page >= ($count - $ceil) ) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }
    
    $echo = '';
   
    
    if ( !empty($min) && !empty($max) ) {
        for( $i = $min; $i <= $max; $i++ ) {
            if ( $page == $i ) {
                $echo .= '<li class="page-item active"><a class="page-link">' . str_pad( (int)$i, 1, '0', STR_PAD_LEFT ) . '.</a></li>';
            } else {
                $echo .= sprintf( '<li class="page-item"><a href="%s" class="page-link">%2d.</a></li>', esc_attr( get_pagenum_link($i) ), $i );
            }
        }
    }
     

    if ( isset($echo) )
        echo wp_kses_post( $args['before_output'] . $echo . $args['after_output'] );
}
