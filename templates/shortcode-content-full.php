<div itemprop="description" class="gsa_match_full_content">
	<?php echo apply_filters( 'gsa_matchs_content', get_post_field( 'post_content', get_the_ID() ) ); ?>
	<?php the_taxonomies();?>
	<?php the_tags();?>
</div>