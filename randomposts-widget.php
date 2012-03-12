<?php

/*
Plugin Name: Random Posts Widget
Description: Displays random psots on the sidebar of your blog, ideal for keeping visitors on your site longer and offering more reading.
Version: 1.2
Author: Dan Mahon
*/ 

define("DefShowPosts", "5"); 
define("DefCatID", "-1");    

class WordpressRandomPosts extends WP_Widget {
	
	function WordpressRandomPosts()
	{		
		parent::WP_Widget( false, __('Random entries', 'wp-randomposts'),  array('description' => __('Random entries on your site', 'wp-randomposts')) );
	}

	function widget($args, $instance)
	{
		global $NewRandomPosts;
		$title = empty( $instance['title'] ) ? __('Random entries', 'wp-randomposts') : $instance['title'];
		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		echo $NewRandomPosts->GetRandomPosts( empty( $instance['Cat_ID'] ) ? DefCatID : $instance['Cat_ID'], 
												  empty( $instance['ShowPosts'] ) ? DefShowPosts : $instance['ShowPosts'], 
												  empty( $instance['ShowExcerpt'] ) ? FALSE : $instance['ShowExcerpt'],
												  $instance['BeforeTitle'],
												  $instance['AfterTitle'],												  
												  empty( $instance['BeforeCite'] ) ? '<p>' : $instance['BeforeCite'],
												  empty( $instance['AfterCite'] ) ? '</p>' : $instance['AfterCite'] );  
		echo $args['after_widget'];
	}

	function update($new_instance) 
	{
		return $new_instance;
	}

	function form($instance) 
	{	
		$type2 = $instance['ShowExcerpt'];
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-randomposts'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />		
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('Cat_ID'); ?>"><?php _e('Categories ID:', 'wp-randomposts'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('Cat_ID'); ?>" id="<?php echo $this->get_field_id('Cat_ID'); ?>" value="<?php if ( empty( $instance['Cat_ID'] ) ) { echo esc_attr(DefCatID); } else { echo esc_attr($instance['Cat_ID']); } ?>" size="3" />		
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id('ShowPosts'); ?>"><?php _e('Number of entries:', 'wp-randomposts'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('ShowPosts'); ?>" id="<?php echo $this->get_field_id('ShowPosts'); ?>" value="<?php if ( empty( $instance['ShowPosts'] ) ) { echo esc_attr(DefShowPosts); } else { echo esc_attr($instance['ShowPosts']); } ?>" size="3" />		
		</p>
		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name('ShowExcerpt'); ?>" id="<?php echo $this->get_field_id('ShowExcerpt'); ?>" value="showexcerpt" <?php if ( 'showexcerpt' == $type2 ) { echo ' checked="checked"'; } ?> />		
			<label for="<?php echo $this->get_field_id('ShowExcerpt'); ?>"><?php _e('Display a quote?', 'wp-randomposts'); ?></label>
		</p>
		
		<?php if ('showexcerpt' == $type2 ) { ?>
		<p>
			<label for="<?php echo $this->get_field_id('BeforeTitle'); ?>"><?php _e('Tag before heading out on:', 'wp-randomposts'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('BeforeTitle'); ?>" class="widefat" id="<?php echo $this->get_field_id('BeforeTitle'); ?>" value="<?php echo esc_attr($instance['BeforeTitle']); ?>" />		
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('AfterTitle'); ?>"><?php _e('Tag after the header record:', 'wp-randomposts'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('AfterTitle'); ?>" class="widefat" id="<?php echo $this->get_field_id('AfterTitle'); ?>" value="<?php echo esc_attr($instance['AfterTitle']); ?>" />		
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id('BeforeCite'); ?>"><?php _e('Tag to quote on:', 'wp-randomposts'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('BeforeCite'); ?>" class="widefat" id="<?php echo $this->get_field_id('BeforeCite'); ?>" value="<?php if ( empty( $instance['BeforeCite'] ) ) { echo esc_attr('<p>'); } else { echo esc_attr($instance['BeforeCite']); } ?>" />		
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('AfterCite'); ?>"><?php _e('Tag after a quotation on:', 'wp-randomposts'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('AfterCite'); ?>" class="widefat" id="<?php echo $this->get_field_id('AfterCite'); ?>" value="<?php if ( empty( $instance['AfterCite'] ) ) { echo esc_attr('</p>'); } else { echo esc_attr($instance['AfterCite']); } ?>" />		
		</p>
		<?php ; }
	}
	
}



class WPRandomPosts {

	function GetRandomPosts($cat_ID, $col, $UseExcerpt, $beforetitle, $aftertitle, $beforecite, $aftercite)
	{
		rewind_posts();
		if ($cat_ID != DefCatID) {
			query_posts('cat='.$cat_ID.'&orderby=rand&showposts='.$col);
		} else {
			query_posts('orderby=rand&showposts='.$col);
		}
		if ($UseExcerpt) {
			if (have_posts()) : 
				echo '';
				while (have_posts()) : the_post(); 
					echo '<div id="post-'.get_the_ID().'">'.$beforetitle.'<a href="'.get_permalink().'">'.get_the_title().'</a>'.$aftertitle.$beforecite.get_the_excerpt().$aftercite.'</div>';								 
				endwhile; 
				echo DevURL;
			endif; 
		} else {
			if (have_posts()) : 
				echo '<ul>';
				while (have_posts()) : the_post(); 
					echo '<div id="post-'.get_the_ID().'"><li><a href="'.get_permalink().'">'.get_the_title().'</a></li></div>';								 
				endwhile; 
				echo '</ul>';
				
			endif;
		}
		
		wp_reset_query();
	}

}



$NewRandomPosts = new WPRandomPosts();

function WPRandomPosts_widgets_init()
{
	register_widget('WordpressRandomPosts');	
}	

add_action('widgets_init', 'WPRandomPosts_widgets_init');

//localization
load_plugin_textdomain( 'wp-randomposts', false, '/'.basename(dirname(__FILE__)).'/languages' );

?>