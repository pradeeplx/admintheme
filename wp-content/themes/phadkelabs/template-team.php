<?php 
/*
Template Name: Team Template
*/
get_header(); 
?>
<section class="team-member-area section-padding" id="sec-inspiration">
	<div class="container">
		<div class="section-head">
			<h2>Meet the team</h2>
		</div>
		<div class="team-view-page">
			<div class="row">
	
				<?php
					$team_query = new WP_Query(array(
						'post_type' => 'our-team',
						'posts_per_page' => -1
					));
					
					while($team_query->have_posts()): $team_query->the_post();
					$thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full');
				?>
				<!-- Single Member -->
				<div class="col-md-3 col-12">
					<div class="single-member">
						<div class="member-img" data-toggle="modal" data-target="#member<?php echo get_the_ID(); ?>">
							<img src="<?php echo $thumb[0]; ?>" alt="">
						</div>
						<div class="member-info">
							<p data-toggle="modal" data-target="#member<?php echo get_the_ID(); ?>"><?php the_title(); ?></p>
							<span><?php if(get_field('doctor_designation')) echo get_field('doctor_designation'); ?></span>
						</div>
					</div>
				</div> <!-- Single Member End -->				
				<?php endwhile; ?>
			</div>
		</div>
		
		
		
		<?php
			$team_query = new WP_Query(array(
				'post_type' => 'our-team',
				'posts_per_page' => -1
			));
			
			while($team_query->have_posts()): $team_query->the_post();
			$thumb = wp_get_attachment_image_url(get_post_thumbnail_id(),'full');
		?>
		
		
		<!-- Single Member Modal  -->
		<!-- Modal -->
		<div class="modal fade" id="member<?php echo get_the_ID(); ?>" tabindex="-1" role="dialog"
			aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
			<div class="modal-dialog member-modal modal-dialog-scrollable" role="document">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-3">
								<div class="member-modal-img">
									<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/team-member.png" alt="">
								</div>
							</div>
							<div class="col-lg-9">
								<div class="member-modal-intro">
									<h5><?php the_title(); ?></h5>
									<h6><?php if(get_field('doctor_designation')) echo get_field('doctor_designation'); ?></h6>
									<?php the_content(); ?>
									<div class="member-modal-award">
										
										<?php if(get_field('doctor_awards_1')) echo get_field('doctor_awards_1'); ?>
										<?php if(get_field('doctor_awards_2')) echo get_field('doctor_awards_2'); ?>
										<?php if(get_field('doctor_awards_3')) echo get_field('doctor_awards_3'); ?>
										<?php if(get_field('doctor_awards_4')) echo get_field('doctor_awards_4'); ?>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- Single Member Modal End  -->
		
		
		<?php endwhile; ?>
		
		

	</div>
</section>
<?php get_footer(); ?>