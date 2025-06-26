<?php get_header(); ?>

	<?php while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			
			<?php if($hero = get_field('hero')): ?>
				<div class="hero">
					<?php
						$hero_type = $hero['type'];
						
						// $popup_video_type = $hero['popup_type'];
						// $popup_video_embed_url = $hero['popup_video_embed'];
						// $popup_video_file = $hero['popup_video_file'];
						// $popup_video_url = false;
						// $popup_video_action = '';
					?>
					
					<?php if($hero_type == 'image' && $hero['image']): ?>
						<div class="hero-bg" style="background-image:url(<?php echo $hero['image']['url']; ?>)"></div>

					<?php elseif($hero_type == 'slider' && $hero['slider']): ?>
						<div class="hero-slider" style="background-image:url(<?php echo $hero['slider'][0]['image']['sizes']['medium']; ?>);">
							<?php foreach($hero['slider'] as $slide): ?>
								<div class="slide">
									<img src="" alt="" data-lazy="<?php echo $slide['image']['url']; ?>">
								</div>
							<?php endforeach; ?>
						</div>

					<?php elseif($hero_type == 'video_file' && $hero['video_file']): ?>
						<div class="hero-video">
							<?php $video_url = $hero['video_file']['url']; ?>
							<video src="<?php echo $video_url; ?>" poster="<?php //echo $hero_bg; ?>" autoplay muted loop playsinline ></video>
						</div>

					<?php elseif($hero_type == 'video_embed' && $hero['video_embed']): ?>
						<?php 
							$video = $hero['video_embed'];

							// Add autoplay functionality to the video code
							if ( preg_match('/src="(.+?)"/', $video, $matches) ) {
								// Video source URL
								$src = $matches[1];

								// get youtube video id
								preg_match('/embed\/(.*?)\?/', $src, $vid_id_arr);
								
								if(is_array($vid_id_arr) && count($vid_id_arr) > 0){
									if(isset($vid_id_arr[1])){
										$playlist_id = $vid_id_arr[1];
									}
									else{
										$playlist_id = $vid_id_arr[0];
									}
								}
								else{
									$playlist_id = '';
								}

								// Add option to hide controls, enable HD, and do autoplay -- depending on provider
								$params = array(
									'controls'    => 0,
					                'muted' => 1,
					                'mute' => 1,
					                'playsinline' => 1,
									'hd'  => 1,
									'background' => 1,
									'loop' => 1,
									'title' => 0,
									'byline' => 0,
									'autoplay' => 1,
					                'playlist' => $playlist_id // required to loop youtube
								);

								
								$new_src = add_query_arg($params, $src);
								
								$video = str_replace($src, $new_src, $video);
								
								// add extra attributes to iframe html
								$attributes = 'frameborder="0" autoplay muted loop playsinline webkit-playsinline allow="autoplay; fullscreen"';
								 
								$video = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $video);
							}
						?>
						<div class="hero-video"><?php echo $video ?></div>

					<?php endif; ?>

					<div class="hero-content">
						<div class="container">
							<?php if($hero['title']): ?>
								<h1 class="hero-title"><?php echo text_replace_brackets($hero['title']); ?></h1>
							<?php endif; ?>
							<?php if($hero['content']): ?>
								<p class="hero-description"><?php echo $hero['content']; ?></p>
							<?php endif; ?>
							<?php if($hero['button_1'] || $hero['button_2']): ?>
								<p class="buttons">
									<?php if($hero['button_1']): ?>
										<a href="<?php echo $hero['button_1']['url']; ?>" target="<?php echo $hero['button_1']['target']; ?>" class="button"><?php echo $hero['button_1']['title']; ?></a>
									<?php endif; ?>
									<?php if($hero['button_2']): ?>
										<a href="<?php echo $hero['button_2']['url']; ?>" target="<?php echo $hero['button_2']['target']; ?>" class="button outline"><?php echo $hero['button_2']['title']; ?></a>
									<?php endif; ?>
								</p>
							<?php endif; ?>
						</div>
						<?php /*if($popup_video_type == 'file' && $popup_video_file): ?>
							<a href="#hero-video-modal-container" class="hero-play no-scroll" data-action="hero-popup-play">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50.8 50.8" style="enable-background:new 0 0 50.8 50.8;" xml:space="preserve">
									<path d="M35.9,26.5l-8,4.6l-8,4.6c-1.1,0.6-1.9,0.1-1.9-1.1v-9.2v-9.2c0-1.2,0.9-1.7,1.9-1.1l8,4.6l8,4.6C37,24.9,37,25.9,35.9,26.5
											 M25.4,0C11.4,0,0,11.4,0,25.4c0,14,11.4,25.4,25.4,25.4c14,0,25.4-11.4,25.4-25.4C50.8,11.4,39.5,0,25.4,0"/>
								</svg>
								<span>Play Full Video</span>
							</a>
						<?php elseif($popup_video_type == 'embed' && $popup_video_embed_url): ?>
							<a href="<?php echo $popup_video_embed_url; ?>" class="hero-play no-scroll" data-action="hero-popup-embed">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50.8 50.8" style="enable-background:new 0 0 50.8 50.8;" xml:space="preserve">
									<path d="M35.9,26.5l-8,4.6l-8,4.6c-1.1,0.6-1.9,0.1-1.9-1.1v-9.2v-9.2c0-1.2,0.9-1.7,1.9-1.1l8,4.6l8,4.6C37,24.9,37,25.9,35.9,26.5
									 M25.4,0C11.4,0,0,11.4,0,25.4c0,14,11.4,25.4,25.4,25.4c14,0,25.4-11.4,25.4-25.4C50.8,11.4,39.5,0,25.4,0"/>
								</svg>
								<span>Play Full Video</span>
							</a>
						<?php endif;*/ ?>
					</div>
				</div>

				<?php /*if($popup_video_type == 'file' && $popup_video_file): ?>
					<div class="video-outer-wrap" id="hero-video-modal-container">
						<div class="video-container">
							<?php if($popup_video_type == 'file' && $popup_video_file): ?>
								<video id="hero-video-modal" src="<?php echo $popup_video_file['url']; ?>" poster="<?php ?>" playsinline controls ></video>
							<?php endif; ?>
						</div>
					</div>
				<?php endif;*/ ?>
			<?php endif; ?>

			<?php if($intro = get_field('intro')): ?>
				<section class="home-section home-intro">
					<div class="container">
						<div class="content">
							<?php if($intro['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($intro['title']); ?></h2>
							<?php endif; ?>
							<?php if($intro['subtitle']): ?>
								<h3 class="section-subtitle"><?php echo $intro['subtitle']; ?></h3>
							<?php endif; ?>
							<?php if($intro['content']): ?>
								<div class="wysiwyg"><?php echo $intro['content']; ?></div>
							<?php endif; ?>
						</div>
						<?php if($intro['image']): ?>
							<figure class="image">
								<img src="<?php echo $intro['image']['sizes']['large']; ?>" alt="" loading="lazy">
							</figure>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>


			<?php if($before_after = get_field('before_after')): ?>
				<section class="home-section home-before-after">
					<div class="container">
						<div class="content">
							<?php if($before_after['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($before_after['title']); ?></h2>
							<?php endif; ?>
							<?php if($before_after['content']): ?>
								<div class="wysiwyg"><?php echo $before_after['content']; ?></div>
							<?php endif; ?>
						</div>
						<?php if($before_after['image']): ?>
							<figure class="image">
								<img src="<?php echo $before_after['image']['sizes']['large']; ?>" alt="" loading="lazy">
							</figure>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if($benefits = get_field('benefits')): ?>
				<section class="home-section home-benefits">
					<div class="bg-1">
						<div class="container">
							<div class="content">
								<?php if($benefits['title']): ?>
									<h2 class="section-title"><?php echo text_replace_brackets($benefits['title']); ?></h2>
								<?php endif; ?>
								<?php if($benefits['content']): ?>
									<div class="wysiwyg"><?php echo $intro['content']; ?></div>
								<?php endif; ?>
							</div>
							<?php if($benefits['benefits_list']): ?>
								<div class="list-container">
									<?php if($benefits['benefits_list_title']): ?>
										<h3 class="list-title"><span><?php echo $benefits['benefits_list_title']; ?></span></h3>
									<?php endif; ?>
									<ul class="benefits-list">
										<?php foreach($benefits['benefits_list'] as $item): ?>
											<li>
												<?php if($item['title']): ?>
													<h4 class="title"><?php echo $item['title']; ?></h4>
												<?php endif; ?>
												<?php if($item['description']): ?>
													<div class="description"><?php echo $item['description']; ?></div>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="bg-2">
						<div class="container">
							<?php if($benefits['components_list']): ?>
								<div class="list-container">
									<?php if($benefits['components_list_title']): ?>
										<h3 class="list-title"><span><?php echo $benefits['components_list_title']; ?></span></h3>
									<?php endif; ?>
									<ul class="components-list">
										<?php foreach($benefits['components_list'] as $item): ?>
											<li>
												<?php if($item['title']): ?>
													<h4 class="title"><?php echo $item['title']; ?></h4>
												<?php endif; ?>
												<?php if($item['description']): ?>
													<div class="description"><?php echo $item['description']; ?></div>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<?php if($customization = get_field('customization')): ?>
				<section class="home-section home-customization">
					<div class="container">
						<div class="content">
							<?php if($customization['label']): ?>
								<div class="section-label"><?php echo $customization['label']; ?></div>
							<?php endif; ?>
							<?php if($customization['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($customization['title']); ?></h2>
							<?php endif; ?>
							<?php if($customization['content']): ?>
								<div class="wysiwyg"><?php echo $customization['content']; ?></div>
							<?php endif; ?>
						</div>
						<div class="list-container">
							<svg width="96" height="65" viewBox="0 0 96 65" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M77.0079 34.4035H60.8532V51.2139H51.5743V11.4518H60.8532V28.0734H77.0079V11.4518H86.2868V30.3356L95.9055 0H20.6071L0 65H75.2984L79.6683 51.2139H77.0079V34.4035ZM46.099 43.6274C45.4605 45.2305 44.5371 46.6242 43.3288 47.8154C42.117 49.0066 40.6271 49.9438 38.8524 50.6269C37.0776 51.31 35.0626 51.6499 32.8072 51.6499C31.5714 51.6499 30.3116 51.5228 29.0277 51.2688C27.7439 51.0148 26.5046 50.6543 25.3031 50.1909C24.1017 49.7275 22.9757 49.1782 21.9184 48.5397C20.8645 47.9047 19.9445 47.1941 19.1619 46.4114L21.8909 42.0998C22.09 41.7737 22.3715 41.5094 22.7354 41.3103C23.0993 41.1112 23.4906 41.0082 23.9094 41.0082C24.4552 41.0082 25.0045 41.1798 25.5606 41.5265C26.1167 41.8732 26.7415 42.2543 27.4452 42.6731C28.1455 43.0919 28.9488 43.4729 29.8619 43.8196C30.7716 44.1664 31.8461 44.338 33.0819 44.338C34.7571 44.338 36.0581 43.9707 36.985 43.2326C37.9118 42.4946 38.3752 41.3274 38.3752 39.7243C38.3752 38.7975 38.1212 38.0422 37.6097 37.4587C37.1017 36.8751 36.4323 36.3945 35.605 36.0135C34.7777 35.6324 33.8336 35.296 32.7798 35.0042C31.7259 34.7124 30.6411 34.3898 29.5323 34.0362C28.4236 33.6826 27.3388 33.2466 26.2849 32.7248C25.2311 32.2065 24.287 31.5234 23.4597 30.6789C22.6324 29.8344 21.963 28.7771 21.455 27.5139C20.9469 26.2506 20.6895 24.6887 20.6895 22.835C20.6895 21.3451 20.9881 19.8862 21.5889 18.4684C22.1896 17.0507 23.0718 15.784 24.2355 14.6752C25.3993 13.5664 26.8273 12.6773 28.5197 12.0148C30.212 11.3523 32.1481 11.0193 34.3314 11.0193C35.55 11.0193 36.7378 11.1154 37.8946 11.3042C39.0481 11.4964 40.1466 11.7779 41.1833 12.1487C42.22 12.5228 43.188 12.9657 44.0908 13.4875C44.9937 14.0092 45.7969 14.5928 46.5075 15.2485L44.2144 19.5326C43.9432 19.9686 43.6549 20.2981 43.3562 20.5144C43.0576 20.7341 42.6697 20.8405 42.1959 20.8405C41.7771 20.8405 41.3274 20.7101 40.8434 20.4457C40.3594 20.1814 39.8136 19.8862 39.206 19.5601C38.5949 19.234 37.9015 18.9387 37.1188 18.6744C36.3362 18.4101 35.4436 18.2796 34.4447 18.2796C32.7146 18.2796 31.4307 18.6469 30.5828 19.385C29.7349 20.1231 29.3127 21.1186 29.3127 22.375C29.3127 23.1748 29.5667 23.8408 30.0782 24.366C30.5862 24.8946 31.2556 25.3478 32.0829 25.7322C32.9102 26.1133 33.8577 26.4634 34.9218 26.7827C35.986 27.1019 37.0742 27.4555 38.183 27.8468C39.2918 28.2382 40.38 28.6982 41.4442 29.2234C42.5083 29.752 43.4558 30.4249 44.2831 31.2419C45.1104 32.0589 45.7798 33.0578 46.2878 34.2318C46.7959 35.4059 47.0533 36.8202 47.0533 38.4748C47.0533 40.3113 46.7341 42.0312 46.099 43.6343V43.6274Z" fill="#0071B4"/>
							</svg>

							<?php if($customization['customization_list_title']): ?>
								<h3 class="list-title"><?php echo $customization['customization_list_title']; ?></h3>
							<?php endif; ?>
							<?php if($customization['customization_list']): ?>
								<ul class="customization-list">
									<?php foreach($customization['customization_list'] as $item): ?>
										<li>
											<?php if($item['title']): ?>
												<h4 class="title"><?php echo $item['title']; ?></h4>
											<?php endif; ?>
											<?php if($item['description']): ?>
												<div class="description"><?php echo $item['description']; ?></div>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
						<?php if($customization['note']): ?>
							<div class="note"><p><?php echo $customization['note']; ?></p></div>
						<?php endif; ?>
						<?php if($customization['buttons']): ?>
							<p class="buttons">
								<?php foreach($customization['buttons'] as $button): ?>
									<a href="<?php echo $button['button']['url']; ?>" target="<?php echo $button['button']['target']; ?>" class="button"><?php echo $button['button']['title']; ?></a>
								<?php endforeach; ?>
							</p>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if($gallery = get_field('gallery')): ?>
				<section class="home-section home-gallery">
					<div class="container">
						<div class="content">
							<?php if($gallery['label']): ?>
								<div class="section-label"><?php echo $gallery['label']; ?></div>
							<?php endif; ?>
							<?php if($gallery['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($gallery['title']); ?></h2>
							<?php endif; ?>
							<?php if($gallery['content']): ?>
								<div class="wysiwyg"><?php echo $gallery['content']; ?></div>
							<?php endif; ?>
						</div>
						<?php if($gallery_images = $gallery['images']): ?>
							<div class="gallery">
								<figure class="gallery-main">
									<a href="<?php echo $gallery_images[0]['sizes']['large']; ?>" data-action="gallery" data-index="0">
										<img src="<?php echo $gallery_images[0]['sizes']['large']; ?>" alt="">
									</a>
								</figure>
								<?php if(count($gallery['images']) > 1): ?>
									<ul class="gallery-thumbs">
										<?php $thumb_i = 0; foreach ($gallery['images'] as $img): ?>
											<li>
												<a href="<?php echo $img['sizes']['large']; ?>" data-action="thumb" data-index="<?php echo $thumb_i; ?>">
													<img src="<?php echo $img['sizes']['small']; ?>" alt="" loading="lazy">
												</a>
											</li>
										<?php $thumb_i++; endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if($uses = get_field('uses')): ?>
				<section class="home-section home-uses">
					<div class="container">
						<div class="content">
							<?php if($uses['label']): ?>
								<div class="section-label"><?php echo $uses['label']; ?></div>
							<?php endif; ?>
							<?php if($uses['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($uses['title']); ?></h2>
							<?php endif; ?>
							<?php if($uses['content']): ?>
								<div class="wysiwyg"><?php echo $uses['content']; ?></div>
							<?php endif; ?>
						</div>
						<?php if($uses['uses_list']): ?>
							<ul class="uses-list">
								<?php foreach($uses['uses_list'] as $item): ?>
									<li>
										<div class="image">
											<?php if($item['image']): ?>
												<img src="<?php echo $item['image']['sizes']['small']; ?>" alt="" loading="lazy">
											<?php endif; ?>
										</div>
										<?php if($item['title']): ?>
											<h4 class="title"><?php echo $item['title']; ?></h4>
										<?php endif; ?>
										<?php if($item['description']): ?>
											<div class="description"><?php echo $item['description']; ?></div>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<?php if($uses['quote']): ?>
							<blockquote class="quote"><p><?php echo $uses['quote']; ?></p></blockquote>
						<?php endif; ?>
						<?php if($uses['button']): ?>
							<p class="buttons">
								<a href="<?php echo $uses['button']['url']; ?>" target="<?php echo $uses['button']['target']; ?>" class="button"><?php echo $uses['button']['title']; ?></a>
							</p>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if($testimonials = get_field('testimonials')): ?>
				<section class="home-section home-testimonials">
					<div class="container">
						<div class="content">
							<?php if($testimonials['label']): ?>
								<div class="section-label"><?php echo $testimonials['label']; ?></div>
							<?php endif; ?>
							<?php if($testimonials['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($testimonials['title']); ?></h2>
							<?php endif; ?>
							<?php if($testimonials['content']): ?>
								<div class="wysiwyg"><?php echo $testimonials['content']; ?></div>
							<?php endif; ?>
						</div>
						<?php if($testimonials['quote']): ?>
							<blockquote class="testimonial">
								<p class="quote"><?php echo $testimonials['quote']; ?></p>
								<?php if($testimonials['quote_cite']): ?>
									<cite class="cite"><?php echo $testimonials['quote_cite']; ?></cite>
								<?php endif; ?>
							</blockquote>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if($faq = get_field('faq')): ?>
				<section class="home-section home-faq">
					<div class="container">
						<div class="content">
							<?php if($faq['label']): ?>
								<div class="section-label"><?php echo $faq['label']; ?></div>
							<?php endif; ?>
							<?php if($faq['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($faq['title']); ?></h2>
							<?php endif; ?>
							<?php if($faq['content']): ?>
								<div class="wysiwyg"><?php echo $faq['content']; ?></div>
							<?php endif; ?>
						</div>
						<?php if($faq['faqs']): ?>
							<ul class="faq-list">
								<?php foreach($faq['faqs'] as $item): ?>
									<li data-action="faq">
										<div class="question">
											<p><?php echo $item['question']; ?></p>
										</div>
										<div class="answer">
											<div class="wysiwyg"><?php echo $item['answer']; ?></div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if($contact = get_field('contact')): ?>
				<section class="home-section home-contact">
					<div class="container">
						<div class="content">
							<?php if($contact['label']): ?>
								<div class="section-label"><?php echo $contact['label']; ?></div>
							<?php endif; ?>
							<?php if($contact['title']): ?>
								<h2 class="section-title"><?php echo text_replace_brackets($contact['title']); ?></h2>
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
				</section>
			<?php endif; ?>

		</article>
	<?php endwhile; ?>
<?php get_footer();