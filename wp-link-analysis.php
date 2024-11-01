<?php
/*
Plugin Name: WP Link Analysis
Plugin URI: http://www.diije.fr/wp-link-analysis/
Description: Analyse les liens contenus dans l'article et les affiche dans une metabox dans l'interface d'administration.
Author: Julien Deneuville
Version: 0.1.1
Author URI: http://www.diije.fr/
*/

if(is_admin()) {
	//get links in post content
	function dfr_links_analyse($post_id) {
		global $post;
		//$array = dfr_links_parse(get_post($post_id)->post_content);

		$pattern='/<a[^>]*(href)=(["\'])(.*?)\2[^>]*>(.*?)<\/a>/i';	
		preg_match_all($pattern,$post->post_content, $liens);

		return $liens;		
	}
	//print links in metabox
	function dfr_links_metabox_callback() {
		global $post;
		$links = dfr_links_analyse($post->ID);
		?>
		<ul><?php
		$i = 0;
		while($links[3][$i] != null) {
			?>
				<li><a href="<?php echo $links[3][$i]; ?>"><?php echo $links[3][$i]; ?></a> ( <?php echo $links[4][$i]; ?> )</li>
			<?php
			$i++; 
			} ?>
		</ul><?php
	}
	//print metabox
	function dfr_links_metabox() {
		if(current_user_can('edit_others_posts')) {
			add_meta_box( 'dfr_links_metabox', 'Liens', 'dfr_links_metabox_callback', 'post', 'side', 'high');
		}
	}
	add_action('admin_init', 'dfr_links_metabox', 2);
}
?>
