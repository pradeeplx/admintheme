<?php
/*
Template Name: About Us Template
*/
 get_header(); 
 global $phadkelabs;
?>



	<!--==============================================-->
	<!--============== Banner Area Start =============-->
	<!--==============================================-->
	<!-- Legacy Menu Start -->
	<div class="container">
		<div class="row">
			<div class="col">
				<nav class="legacy-menu">
					<ul>
						<li><a href="#sec-inspiration">Inspiration</a></li>
						<li><a href="#sec-service">Services</a></li>  
						<li><a href="#sec-nerve-centre">Nerve Centre </a></li> 
						<li><a href="#sec-pedigree">Pedigree</a></li>  
						<li><a href="#sec-legacy">Legacy</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<!-- Legacy Menu End -->
	<div class="about-banner-area margin_hide" id="sec-legacy">
		<div class="container">
			<div class="row legacy-banner">
				<div class="col-lg-5 offset-lg-1 col-md-12">
					<div class="banner-member-info">
						<?php
							$legacy_caption = $phadkelabs['our_legacy_text_title'];
							$our_legacy_text = $phadkelabs['our_legacy_text'];
							$our_legacy_right_photo = $phadkelabs['our_legacy_right_photo']['url'];
						?>
					
						<h3 class="about-legacy"><?php if($legacy_caption) echo $legacy_caption;  ?></h3>
						<p><?php if($our_legacy_text) echo $our_legacy_text;  ?></p>
					</div>
					
				</div>
				<div class="col-lg-6 col-12">
					<div class="banner-doctors legacy">
						<img src="<?php if($our_legacy_right_photo) echo $our_legacy_right_photo; ?>" alt="">
					</div>
				</div>
			</div>
			<div class="row" id="sec-pedigree">
				
				<?php
					$pedigree_title = $phadkelabs['pedigree_title'];
					$pedigree_details = $phadkelabs['pedigree_details'];
					$pedigree_left_photo = $phadkelabs['pedigree_left_photo']['url'];
				?>
				<div class="col-lg-5 offset-lg-1 col-md-12">
					<div class="banner-doctors">
						<img src="<?php echo $pedigree_left_photo; ?>" alt="">
					</div>
				</div>
				<div class="col-lg-6 col-md-12">
					<div class="banner-member-info">

						<h3><?php if($legacy_caption) echo $legacy_caption;  ?></h3>
						<p>
							<?php if($pedigree_details) echo $pedigree_details;  ?>
						</p>
						
					</div>
				</div>
			</div>
		</div>
			<div class="about-banner-service-area">
				<div class="container">
				<div class="row">
					
					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_1 = $phadkelabs['our_history_title_1'];
							$our_history_logo_1 = $phadkelabs['our_history_logo_1']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_1) echo $our_history_logo_1; ?>" alt="">
							</div>
							<p><?php if($our_history_title_1) echo $our_history_title_1; ?></p>
						</div>
					</div> <!-- Single service Image End -->
					
					
					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_2 = $phadkelabs['our_history_title_2'];
							$our_history_logo_2 = $phadkelabs['our_history_logo_2']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_2) echo $our_history_logo_2; ?>" alt="">
							</div>
							<p><?php if($our_history_title_2) echo $our_history_title_2; ?></p>
						</div>
					</div> <!-- Single service Image End -->
					
					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_3 = $phadkelabs['our_history_title_3'];
							$our_history_logo_3 = $phadkelabs['our_history_logo_3']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_3) echo $our_history_logo_3; ?>" alt="">
							</div>
							<p><?php if($our_history_title_3) echo $our_history_title_3; ?></p>
						</div>
					</div> <!-- Single service Image End -->
					
					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_4 = $phadkelabs['our_history_title_4'];
							$our_history_logo_4 = $phadkelabs['our_history_logo_4']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_4) echo $our_history_logo_4; ?>" alt="">
							</div>
							<p><?php if($our_history_title_4) echo $our_history_title_4; ?></p>
						</div>
					</div> <!-- Single service Image End -->
					
					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_5 = $phadkelabs['our_history_title_5'];
							$our_history_logo_5 = $phadkelabs['our_history_logo_5']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_5) echo $our_history_logo_5; ?>" alt="">
							</div>
							<p><?php if($our_history_title_5) echo $our_history_title_5; ?></p>
						</div>
					</div> <!-- Single service Image End -->

					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_6 = $phadkelabs['our_history_title_6'];
							$our_history_logo_6 = $phadkelabs['our_history_logo_6']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_6) echo $our_history_logo_6; ?>" alt="">
							</div>
							<p><?php if($our_history_title_6) echo $our_history_title_6; ?></p>
						</div>
					</div> <!-- Single service Image End -->
					
					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_7 = $phadkelabs['our_history_title_7'];
							$our_history_logo_7 = $phadkelabs['our_history_logo_7']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_7) echo $our_history_logo_7; ?>" alt="">
							</div>
							<p><?php if($our_history_title_7) echo $our_history_title_7; ?></p>
						</div>
					</div> <!-- Single service Image End -->
					
					<!-- Single service Image -->
					<div class="col-lg-3 col-6">
						<?php
							$our_history_title_8 = $phadkelabs['our_history_title_8'];
							$our_history_logo_8 = $phadkelabs['our_history_logo_8']['url'];
						?>
						<div class="about-banner-service">
							<div class="service-img">
								<img src="<?php if($our_history_logo_8) echo $our_history_logo_8; ?>" alt="">
							</div>
							<p><?php if($our_history_title_8) echo $our_history_title_8; ?></p>
						</div>
					</div> <!-- Single service Image End -->

					

				</div>
				</div>
			</div>
		</div>
	</div>

	<!--==============================================-->
	<!--============== Banner Area End ===============-->
	<!--==============================================-->


	<!--==============================================-->
	<!--========== Journey Tab Area Start ==============-->
	<!--==============================================-->

	<div class="journey-tab-area section-padding">
		<div class="container">
			<div class="journey-tab">
				<h3 class="title">Our Journey</h3>
				<div class="row main-journey">
					<div class="col-lg-8 col-sm-8">
						<div class="tab-content" id="v-pills-tabContent">							
							
							<?php
								$journey_query = new WP_Query(array(
									'post_type' => 'our-journey',
									'posts_per_page' => -1
								));
								$count=1;
								while($journey_query->have_posts()): $journey_query->the_post();
							?>	
								<!-- Single Tab Content -->
								<div class="tab-pane fade show <?php if($count==1) echo "active"; ?> journey-tab-content" id="v-pills-<?php echo get_the_ID(); ?>"
									role="tabpanel" aria-labelledby="v-pills-<?php echo get_the_ID(); ?>-tab">
									<h1><?php the_title(); ?></h1>
									<p><?php the_content(); ?></p>
								</div> <!-- Single Tab Content End -->
							<?php $count=1; endwhile; ?>

				
						</div>
					</div>
					<div class="col-lg-4 col-sm-4">
						<div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						
							<?php
								$journey_query = new WP_Query(array(
									'post_type' => 'our-journey',
									'posts_per_page' => -1
								));
								$count=1;
								while($journey_query->have_posts()): $journey_query->the_post();
							?>
							<!-- Single tab -->
							<a class="nav-link <?php if($count==1) echo "active"; ?>" id="v-pills-<?php echo get_the_ID(); ?>-tab" data-toggle="pill" href="#v-pills-<?php echo get_the_ID(); ?>"
								role="tab" aria-controls="v-pills-<?php echo get_the_ID(); ?>" aria-selected="true"><?php the_title(); ?></a>
							<!-- Single tab End -->
							<?php $count=2; endwhile; ?>
			
						</div>
					</div>

				</div>
				
			
				
			</div>
		</div>
	</div>

	<!--==============================================-->
	<!--========== Journey Tab Area End ==============-->
	<!--==============================================-->


	<!--==============================================-->
	<!--========== About Labs Area Start ===============-->
	<!--==============================================-->

	<div class="about-labs section-padding" id="sec-nerve-centre">
		<div class="container">
			<div class="row">
				<?php				
					$nerve_center_title = $phadkelabs['nerve_center_title'];
					$nerve_center_subtitle = $phadkelabs['nerve_center_subtitle'];
					$nerve_center_details = $phadkelabs['nerve_center_details'];
					$nerve_center_left_photo_1 = $phadkelabs['nerve_center_left_photo_1']['url'];
					$nerve_center_left_photo_2 = $phadkelabs['nerve_center_left_photo_2']['url'];
					$nerve_center_left_photo_3 = $phadkelabs['nerve_center_left_photo_3']['url'];
				?>
				<div class="col-lg-5 offset-lg-1">
					<div class="nerve-center-area">
						<!-- single Images -->
						<div class="single-nerve item1">
							<img src="<?php if($nerve_center_left_photo_1) echo $nerve_center_left_photo_1; ?>" alt="">
						</div>
						<!-- single Images -->
						<div class="single-nerve item2">
							<img src="<?php if($nerve_center_left_photo_2) echo $nerve_center_left_photo_2; ?>" alt="">
						</div>
						<!-- single Images -->
						<div class="single-nerve item3">
							<img src="<?php if($nerve_center_left_photo_3) echo $nerve_center_left_photo_3; ?>" alt="">
						</div>
					</div>

				</div>
				<div class=" col-lg-6">
						<div class="banner-member-info">
							<h3><?php if($nerve_center_title) echo $nerve_center_title; ?></h3>
							<p><?php if($nerve_center_subtitle) echo $nerve_center_subtitle; ?>
							</p>
							<span><?php if($nerve_center_details) echo $nerve_center_details; ?></span>
						</div>
					</div>
				</div>
				<div class="about-lab-service" id="sec-service">
					<div class="lab-service-intro">
						<?php if($phadkelabs['offer-range_off_services_details']) echo $phadkelabs['offer-range_off_services_details']; ?>
					</div>
					<div class="lab-service-offer">
						<h3>Services we offer</h3>
						<div class="row">
						
						<?php
							$service_query = new WP_Query(array(
								'post_type' => 'offer-service',
								'posts_per_page' => -1
							));
							$count=1;
							while($service_query->have_posts()): $service_query->the_post();
							
						?>
							<!-- Single Labs Offer -->
							<div class="col-lg-3 col-sm-4 col-6">
								<div class="single-labs-offer">
									<?php the_post_thumbnail(); ?>
									<span><?php the_title(); ?></span>
								</div>
							</div> <!-- Single Labs Offer -->
							
						<?php endwhile; ?>

						
						</div>
					</div>
				</div>
			</div>
		</div>

		<!--==============================================-->
		<!--========== About Labs Area End ===============-->
		<!--==============================================-->

		<!--==============================================-->
		<!--========== Team Member Area Start ============-->
		<!--==============================================-->

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
				$phadkelabs_team_page_info = get_pages(
			        array(
			            'meta_key' => '_wp_page_template',
			            'meta_value' => 'template-team.php'
			        )
			    );

			    $phadkelabs_team_page_id = $phadkelabs_team_page_info[0]->ID;
			    $phadkelabs_team_page_link = get_permalink( $phadkelabs_team_page_id );
				?>
				<a class="view-all" href="<?php echo esc_url($phadkelabs_team_page_link) ;?>">View Full Team</a>

				<!-- Single Member Modal  -->
				<!-- Modal -->
				<div class="modal fade" id="member1" tabindex="-1" role="dialog"
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
											<h5>Dr. Avinash Phadke</h5>
											<h6>Designation</h6>
											<p>Armed with an MBBS doctor’s degree, a MD in Pathology and a Diploma in
												Pathology & Bacteriology, Dr. Avinash Phadke has revolutionized the
												pathology industry in India and is one of the most renowned medical
												professionals in Mumbai.</p>
											<span>Founder of Dr. Avinash Phadke Pathology Labs as well as President -
												Technology of SRL Diagnostics, in Mumbai, the doctor has earned many
												laurels and awards over the years.</span>
											<span>Besides running a chain of his own labs, Dr. Avinash also heads
												various institutes in the medical sector. He is the Director of India
												Venture Healthcare Fund; Advisor & Mentor at APPI (Association of
												Practicing Pathologists of India); Trustee and member of governing
												board, Cancare Trust Cancer Hospital.</span>
											<span>He is also a guest faculty member at Tata Institute of Social Science
												(TISS), Bhabha Atomic Research Centre and Mumbai University. He has
												participated in more than 400 regional, national and international
												conferences. Often heard quoting Laurence Miller, PhD, a clinical and
												forensic psychologist, “By the deficits we may know the talents, by the
												exceptions we may know the rules, by studying pathology we may construct
												a model of health,” Dr. Avinash Phadke truly believes in the potential
												of pathology in human health and wellbeing.</span>
											<span>Driven by the understanding of the importance of doctor-led diagnostic
												centres, Dr. Avinash established Dr. Avinash Phadke Path Labs with a
												mission to deliver qualitative medical guidance and counseling through
												his labs. It is this very vision of his that has helped him garner trust
												and reputation in his legacy lab.</span>
											<p class="awardp">Awards & Kudos</p>
											<span>Years of dedicated research, development and innovation for the
												medical and diagnostic sectors has earned him various awards and
												recognition by many established organizations.</span>
											<div class="member-modal-award">
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Professional of the Year</p>
														<span>- by Maharashtra Times </span>
													</div>
												</div> <!-- Single Award End -->
												
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Distinguished Doctor Award</p>
														<span>- by IMA</span>
													</div>
												</div> <!-- Single Award End -->
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Among Top Three Pathologists <br> in Mumbai</p>
														<span>- by India Today </span>
													</div>
												</div> <!-- Single Award End -->
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Named as one of the Best <br> Fifty Healthcare Professionals</p>
														<span>- by Healthcare Express</span>
													</div>
												</div> <!-- Single Award End -->

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- Single Member Modal End  -->
				
				<!-- Single Member Modal  -->
				<!-- Modal -->
				<div class="modal fade" id="member2" tabindex="-1" role="dialog"
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
											<h5>Dr. Vandana Phadke</h5>
											<h6>Designation</h6>
											<p>Armed with an MBBS doctor’s degree, a MD in Pathology and a Diploma in
												Pathology & Bacteriology, Dr. Avinash Phadke has revolutionized the
												pathology industry in India and is one of the most renowned medical
												professionals in Mumbai.</p>
											<span>Founder of Dr. Avinash Phadke Pathology Labs as well as President -
												Technology of SRL Diagnostics, in Mumbai, the doctor has earned many
												laurels and awards over the years.</span>
											<span>Besides running a chain of his own labs, Dr. Avinash also heads
												various institutes in the medical sector. He is the Director of India
												Venture Healthcare Fund; Advisor & Mentor at APPI (Association of
												Practicing Pathologists of India); Trustee and member of governing
												board, Cancare Trust Cancer Hospital.</span>
											<span>He is also a guest faculty member at Tata Institute of Social Science
												(TISS), Bhabha Atomic Research Centre and Mumbai University. He has
												participated in more than 400 regional, national and international
												conferences. Often heard quoting Laurence Miller, PhD, a clinical and
												forensic psychologist, “By the deficits we may know the talents, by the
												exceptions we may know the rules, by studying pathology we may construct
												a model of health,” Dr. Avinash Phadke truly believes in the potential
												of pathology in human health and wellbeing.</span>
											<span>Driven by the understanding of the importance of doctor-led diagnostic
												centres, Dr. Avinash established Dr. Avinash Phadke Path Labs with a
												mission to deliver qualitative medical guidance and counseling through
												his labs. It is this very vision of his that has helped him garner trust
												and reputation in his legacy lab.</span>
											<p class="awardp">Awards & Kudos</p>
											<span>Years of dedicated research, development and innovation for the
												medical and diagnostic sectors has earned him various awards and
												recognition by many established organizations.</span>
											<div class="member-modal-award">
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Professional of the Year</p>
														<span>- by Maharashtra Times </span>
													</div>
												</div> <!-- Single Award End -->
												
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Distinguished Doctor Award</p>
														<span>- by IMA</span>
													</div>
												</div> <!-- Single Award End -->
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Among Top Three Pathologists <br> in Mumbai</p>
														<span>- by India Today </span>
													</div>
												</div> <!-- Single Award End -->
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Named as one of the Best <br> Fifty Healthcare Professionals</p>
														<span>- by Healthcare Express</span>
													</div>
												</div> <!-- Single Award End -->

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- Single Member Modal End  -->

				<!-- Single Member Modal  -->
				<!-- Modal -->
				<div class="modal fade" id="member3" tabindex="-1" role="dialog"
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
											<h5>Dr. Ajay Phadke</h5>
											<h6>Designation</h6>
											<p>Armed with an MBBS doctor’s degree, a MD in Pathology and a Diploma in
												Pathology & Bacteriology, Dr. Avinash Phadke has revolutionized the
												pathology industry in India and is one of the most renowned medical
												professionals in Mumbai.</p>
											<span>Founder of Dr. Avinash Phadke Pathology Labs as well as President -
												Technology of SRL Diagnostics, in Mumbai, the doctor has earned many
												laurels and awards over the years.</span>
											<span>Besides running a chain of his own labs, Dr. Avinash also heads
												various institutes in the medical sector. He is the Director of India
												Venture Healthcare Fund; Advisor & Mentor at APPI (Association of
												Practicing Pathologists of India); Trustee and member of governing
												board, Cancare Trust Cancer Hospital.</span>
											<span>He is also a guest faculty member at Tata Institute of Social Science
												(TISS), Bhabha Atomic Research Centre and Mumbai University. He has
												participated in more than 400 regional, national and international
												conferences. Often heard quoting Laurence Miller, PhD, a clinical and
												forensic psychologist, “By the deficits we may know the talents, by the
												exceptions we may know the rules, by studying pathology we may construct
												a model of health,” Dr. Avinash Phadke truly believes in the potential
												of pathology in human health and wellbeing.</span>
											<span>Driven by the understanding of the importance of doctor-led diagnostic
												centres, Dr. Avinash established Dr. Avinash Phadke Path Labs with a
												mission to deliver qualitative medical guidance and counseling through
												his labs. It is this very vision of his that has helped him garner trust
												and reputation in his legacy lab.</span>
											<p class="awardp">Awards & Kudos</p>
											<span>Years of dedicated research, development and innovation for the
												medical and diagnostic sectors has earned him various awards and
												recognition by many established organizations.</span>
											<div class="member-modal-award">
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Professional of the Year</p>
														<span>- by Maharashtra Times </span>
													</div>
												</div> <!-- Single Award End -->
												
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Distinguished Doctor Award</p>
														<span>- by IMA</span>
													</div>
												</div> <!-- Single Award End -->
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Among Top Three Pathologists <br> in Mumbai</p>
														<span>- by India Today </span>
													</div>
												</div> <!-- Single Award End -->
												<!-- Single Award -->
												<div class="single-member-award">
													<div class="member-award-img">
														<img src="<?php echo get_template_directory_uri(); ?>/images/about/team/award.png" alt="">
													</div>
													<div class="member-award-info">
														<p>Named as one of the Best <br> Fifty Healthcare Professionals</p>
														<span>- by Healthcare Express</span>
													</div>
												</div> <!-- Single Award End -->

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- Single Member Modal End  -->
			</div>
		</section>
	</div>




	

<?php get_footer(); ?>