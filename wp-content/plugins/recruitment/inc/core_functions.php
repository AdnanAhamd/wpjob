<?php


if(!function_exists('func_alpha_custom_blogs')) { 
	function func_alpha_custom_blogs($blog_count,$blog_category,$blog_tags){
		if(is_front_page()){
							 
		  $paged = (get_query_var('page')) ? get_query_var('page') : 1;   
		}
		else
		{
		  	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			 
		}
		 if(empty($blog_category)&&empty($blog_tags))
			  {
				
				 $args = array( 
							'post_type' 		=> 'post',
							'posts_per_page' 	=>$blog_count,
							'paged' 			=>  $paged,
							'post_status'       => 'publish',
							'orderby' 			=> 'date',
							'order' 			=>  "DESC",
						);
			  }else{

               
		  	 if(!empty($blog_category)&&empty($blog_tags))
               { 
			  		$args = array( 
						'post_type' 		=> 'post',
						'category__and'     =>  $blog_category,
						'post_status'       => 'publish',
						'posts_per_page' 	=> $blog_count,
						'paged' 			=>  $paged,
						'orderby' 			=> 'date',
						'order' 			=>  "DESC",
					);
		     	}else{
		     		if(empty($blog_category)&&!empty($blog_tags))
                   { 
				  		$args = array( 
							'post_type' 		=> 'post',
							'tag_id'     		=>  $blog_tags,
							'post_status'       => 'publish',
							'posts_per_page' 	=> $blog_count,
							'paged' 			=>  $paged,
							'orderby' 			=> 'date',
							'order' 			=>  "DESC",
						);
			     	}else{  
			     		$args = array( 
							'post_type' 		=> 'post',
							'category__in'      =>   $blog_category,
							'tag_in'            =>   $blog_tags, 
							'post_status'       => 'publish',
							'posts_per_page' 	=> $blog_count,
							'paged' 			=>  $paged,
							'orderby' 			=> 'date',
							'order' 			=>  "DESC",
						);

			     	}

		     	}

			 }	 

		return $args;

	}	
}
/*
if(!function_exists('func_alpha_custom_testimonial')) { 
	function func_alpha_custom_testimonial($count,$category){
		if(empty($category)){

			$args = array( 
					'post_type' 		=> 'testimonial',
					'post_status'       => 'publish',
					'posts_per_page' 	=> $count,
					'orderby' 			=> 'date',
					'order' 			=>  "DESC",
				);

		}else{
			$args = array( 
					'post_type' 		=> 'testimonial',
					'post_status'       => 'publish',
					'tax_query' => array(
				        array(
				            'taxonomy' => 'testimonial-category',
				            'field' => 'term_id',
				            'terms' => $category,
				        ),
				    ),
					'posts_per_page' 	=> $count,
					'orderby' 			=> 'date',
					'order' 			=>  "DESC",
				);
		}
		return $args;

	}	
}

if(!function_exists('func_alpha_custom_restaurant')) { 
	function func_alpha_custom_restaurant($count,$category){
		if(empty($category)){

			$args = array( 
					'post_type' 		=> 'restaurant',
					'post_status'       => 'publish',
					'posts_per_page' 	=> $count,
					'orderby' 			=> 'date',
					'order' 			=>  "DESC",
				);

		}else{
			$args = array( 
					'post_type' 		=> 'restaurant',
					'post_status'       => 'publish',
					'tax_query' => array(
				        array(
				            'taxonomy' => 'restaurant-category',
				            'field' => 'term_id',
				            'terms' => $category,
				        ),
				    ),
					'posts_per_page' 	=> $count,
					'orderby' 			=> 'date',
					'order' 			=>  "DESC",
				);
		}
		return $args;

	}	
}


if(!function_exists('func_alpha_custom_prod')) { 
	function func_alpha_custom_prod($category){
	
		$args = array( 
				'post_type' 		=> 'product',
				'post_status'       => 'publish',
				'tax_query' => array(
			        array(
			            'taxonomy' => 'product_cat',
			            'field' => 'term_id',
			            'terms' => $category,
			        ),
			    ),
				'posts_per_page' 	=> -1,
				'orderby' 			=> 'date',
				'order' 			=>  "DESC",
			);
		$post=new WP_Query($args);
		
		return $post->posts;

	}	
}

function alpha_pagination(){
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
				return;
			}
			$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
			$pagenum_link = html_entity_decode( get_pagenum_link() );
			$query_args   = array();
			$url_parts    = explode( '?', $pagenum_link );

			if ( isset( $url_parts[1] ) ) {
				wp_parse_str( $url_parts[1], $query_args );
			}

			$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
			$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

			$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
			$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';			
			// Set up paginated links.
			$links = paginate_links( array(
				'base'     => $pagenum_link,
				'format'   => $format,
				'total'    => $GLOBALS['wp_query']->max_num_pages,
				'current'  => $paged,
				'mid_size' => 1,
				'add_args' => array_map( 'urlencode', $query_args ),
				'prev_text' =>'Prev',
				'next_text' => 'Next',
			) );
			html_entity_decode($links);
			if ( $links ) :
				return $links;
			endif;
	}



function alpha_acf_breadcrumb() {
	$html="";
    global $post;
   $html.= '<ol class="breadcrumb">';
    if (!is_home()) {
         $html.= '<li class="breadcrumb-item"><a href="';
         $html.= get_option('home');
         $html.= '">';
         $html.= 'Home';
         $html.= '</a></li>';
        if (is_category() || is_single()) {
             $html.= '<li class="breadcrumb-item">';
             $html.=get_the_category(' </li><li class="breadcrumb-item"> </li><li> ');
            if (is_single()) {
                 $html.= '</li><li class="breadcrumb-item"> </li><li>';
                 $html.=get_the_title();
                 $html.= '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li class="breadcrumb-item"><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li>';
                }
                 $html.= $output;
                 $html.= $title;
            } else {
                 $html.= '<li class="breadcrumb-item">'.get_the_title().'</li>';
            }
        }
    }
    elseif (is_tag()) {single_tag_title();}
    elseif (is_day()) {  $html.="<li class='breadcrumb-item'>Archive for "; the_time('F jS, Y'); echo'</li>';}
    elseif (is_month()) { $html.="<li class='breadcrumb-item'>Archive for "; the_time('F, Y'); echo'</li>';}
    elseif (is_year()) { $html.="<li class='breadcrumb-item'>Archive for "; the_time('Y'); echo'</li>';}
    elseif (is_author()) { $html.="<li class='breadcrumb-item'>Author Archive"; echo'</li>';}
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { $html.= "<li class='breadcrumb-item'>Blog Archives";  $html.='</li>';}
    elseif (is_search()) { $html.="<li class='breadcrumb-item'>Search Results";  $html.='</li>';}
     $html.= '</ol>';
     return $html;
}

function func_addon_extras_list(){
	$data=$_POST["data"];
	$data=preg_replace('/\\\\/', '', $data);
	$data=json_decode($data);
	if(!empty($data)){
		foreach ($data as $dt) {
			$dt=explode('|',  $dt);
			WC()->cart->add_to_cart( $dt[0],$dt[1] );
		}
	}
	die();
}
add_action( 'wp_ajax_addon_extras_list','func_addon_extras_list');
add_action( 'wp_ajax_nopriv_addon_extras_list','func_addon_extras_list');


add_filter( 'woocommerce_get_country_locale', 'ireland_country_locale_change', 10, 1 );
function ireland_country_locale_change( $locale ) {
    $locale['IE']['postcode']['required'] = true;

    return $locale;
}*/