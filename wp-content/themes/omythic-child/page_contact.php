<?php
/*
 * Template Name: Contact
 */
get_header(); ?>

	<?php while( have_posts() ): the_post(); ?>
		
		<?php if($contact = get_field('contact', get_option('page_on_front'))): ?>
			<article class="home-section home-contact" id="home-section-contact">
				<div class="container">
					<div class="content">
						<?php if($contact['label']): ?>
							<div class="section-label"><?php echo $contact['label']; ?></div>
						<?php endif; ?>
						<?php if($contact['title']): ?>
							<h1 class="section-title"><?php echo text_replace_brackets($contact['title']); ?></h1>
						<?php endif; ?>
						<?php if($contact['content']): ?>
							<div class="wysiwyg"><?php echo $contact['content']; ?></div>
						<?php endif; ?>
					</div>
					<?php if($contact['form_shortcode']): ?>
						<div class="form">
							<?php echo do_shortcode($contact['form_shortcode']); ?>
						</div>
					<?php endif; ?>
				</div>
			</article>
		<?php endif; ?>
	<?php endwhile; ?>

<?php get_footer();
