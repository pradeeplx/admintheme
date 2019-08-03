<div class="lipid-profile-area">

				<div class="lipid-profile-tab">
					<ul>
						<li id="patient" class="active">For Patient</li>
						<li id="doctor">For Doctors</li>
					</ul>
				</div> <!-- Lipid Profile Tab End -->
				<div class="lipid-profile-content patients">
					<div class="show-more-details" style="max-height: none; height: 525px;" data-readmore="" aria-expanded="false" id="rmjs-1">
						<div class="content-head">
							<div class="row">
								<div class="col-lg-4 col-6">
									<div class="lipid-test">
										<h4><?php the_title(); ?></h4>
									</div>
								</div>
								<div class="col-lg-5 col-6">
									<div class="lipid-menu">
										<ul>
											<li>
												<a href="#">
													<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/home.png" alt="">
													Home Visit <?php if(get_field('home_visit')): ?> (<?php echo get_field('home_visit', false, false);?>)<?php endif; ?>
												</a>
											</li>
											<li>
												<a href="#">
													<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/lab.png" alt="">
													Lab Visit<?php if(get_field('lab_visit')): ?>(<?php echo get_field('lab_visit', false, false);?>)<?php endif; ?>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-lg-3 col-6">
									<div class="lipid-rate">
										<?php $_product = wc_get_product( get_the_ID() ); ?>
										<h3>Rs. <?php echo $_product->get_price(); ?>/-</h3>
									</div>
								</div>
								<div class="col-6 d-md-block d-lg-none ">
									<div class="patient-cart text-right">
										<a class="product_type_simple add_to_cart add_to_cart add_to_cart_button ajax_add_to_cart"  data-product_id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>?add-to-cart=<?php the_ID(); ?>"><i class="material-icons">add_shopping_cart</i>add to cart</a>
									</div>
								</div>
							</div>
						</div> <!-- Content Head End -->
						<div class="content-head-info">
							<div class="row">
								<div class="col-lg-3 col-sm-5">
									<div class="lipid-sample">
										<ul>
											<li>
												<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/testtube.png" alt="">
												Sample: <span><?php if(get_field('patient_test_sample')) echo get_field('patient_test_sample'); ?></span>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-lg-6 col-sm-7">
									<div class="lipid-date">
										<ul>
											<li>
												<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/calender.png" alt="">
												Turnaround Time: <span><?php if(get_field('turnaround_time')) echo get_field('turnaround_time'); ?></span>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="lipid-cart">
										<a class="product_type_simple add_to_cart add_to_cart add_to_cart_button ajax_add_to_cart"  data-product_id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>?add-to-cart=<?php the_ID(); ?>"><i class="material-icons">add_shopping_cart</i>add to cart</a>
									</div>
								</div>
							</div>
						</div> <!-- Content Head End -->
						<div class="lipid-test-details">
							<div class="row">
								<div class="col-lg-6 col-12">
									<div class="about-test">
										<ul>
											<li>
												<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/list.png" alt="">
												About Test:
											</li>
										</ul>
										<p><?php if(get_field('about_test')) echo get_field('about_test'); ?></p>
									</div>
								</div>
								<div class="col-lg-6 col-12">
									<div class="test-intro-area">
										<div class="test-intro">
											<ul>
												<li>
													<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/star.png" alt="">
													Medical condition:
												</li>
											</ul>
											<span><?php if(get_field('medical_condition')) echo get_field('medical_condition', false, false); ?></span>
										</div>
										<!-- Single Intro -->
										<div class="test-intro">
											<ul>
												<li>
													<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/star.png" alt="">
													Test preparation:
												</li>
											</ul>
											<span><?php if(get_field('test_preparation')) echo get_field('test_preparation', false, false); ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
			
					</div>
						<!-- Show More Starts -->
						<div class="single_product_show_more">
							<h1> <?php if(get_field('more_info_for_patient')) echo get_field('more_info_for_patient'); ?> </h1> 
						</div>
						<!-- Show More Ends -->

					<div class="patient-cart">
						<a class="product_type_simple add_to_cart add_to_cart add_to_cart_button ajax_add_to_cart"  data-product_id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>?add-to-cart=<?php the_ID(); ?>"><i class="material-icons">add_shopping_cart</i>add to cart</a>
					</div>
				</div> <!-- Lipid Profile Content End -->
				
				
				<div class="lipid-profile-content doctors">
						<div class="content-head">
							<div class="row">
								<div class="col-lg-4 col-12">
									<div class="lipid-test">
										<h4><?php the_title(); ?></h4>
									</div>
								</div>
								<div class="col-lg-5 col-8">
									<div class="lipid-menu">
										<ul>
											<li>
												<a href="#">
													<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/code.png" alt="">
													code: <span><?php if(get_field('for_doctor_code')) echo get_field('for_doctor_code'); ?></span>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<?php $_product2 = wc_get_product( get_the_ID() ); ?>
								<div class="col-lg-3 col-4">
									<div class="lipid-rate">										
										<h3>Rs. <?php echo $_product2->get_price(); ?>/-</h3>
									</div>
								</div>
							</div>
						</div> <!-- Content Head End -->
						<div class="content-head-info">
							<div class="row">
								<div class="col-lg-4 col-sm-5">
									<div class="lipid-date">
										<ul>
											<li>
												<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/testtube.png" alt="">
												Sample: <span><?php if(get_field('patient_test_sample')) echo get_field('patient_test_sample'); ?></span>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-lg-4 col-sm-7">
									<div class="lipid-date">
										<ul>
											<li>
												<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/calender.png" alt="">
												Turnaround Time: <span><?php if(get_field('turnaround_time')) echo get_field('turnaround_time'); ?></span>
											</li>
										</ul>
									</div>
								</div>
								<div class="col-lg-4 col-sm-6">
									<div class="lipid-date">
										<ul>
											<li>
												<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/catagory.png" alt="">
												Category: 
												<span>
													<?php $product_id = get_the_ID(); 
														$post_terms = wp_get_post_terms($product_id,'product_cat');
														$total_terms = count($post_terms);
														foreach($post_terms as $term){
															echo $term->name;
															$total_terms--;
															if($total_terms>0)
																echo ", ";
														}
													?>
												</span>
											</li>
										</ul>
									</div>
								</div>
								
							
							</div>
						</div> <!-- Content Head End -->
										<!-- Test Result Accordion -->
						<div class="intro-test test-result-accordion">
							<ul>
								<li>
									<img src="<?php echo get_template_directory_uri(); ?>/images/lipid_profile/list.png" alt="">
									Result Interpretation
								</li>
							</ul>
							<!--Accordion wrapper-->
							<div class="accordion md-accordion" id="accordionEx1" role="tablist" aria-multiselectable="true">

								<!-- Accordion card -->
								<div class="card">

									<!-- Card header -->
									<div class="card-header" role="tab" id="headingTwo1">
										<a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo1">
											<h5 class="mb-0">Lorem Ipsum is a dummy content<i class="fas fa-angle-down rotate-icon"></i></h5>
										</a>
									</div>

									<!-- Card body -->
									<div id="collapseTwo1" class="collapse" role="tabpanel" aria-labelledby="headingTwo1" data-parent="#accordionEx1">
										<div class="card-body">
											Lorem ipsum dolor sit, amet consectetur adipisicing elit. Tempore vel error
											iusto, perferendis nisi culpa, voluptate, nulla veniam quos voluptatem eius
											vero fugiat magnam id et ad ab praesentium voluptatibus!
										</div>
									</div>

								</div>
								<!-- Accordion card -->

								<!-- Accordion card -->
								<div class="card">

									<!-- Card header -->
									<div class="card-header" role="tab" id="headingTwo2">
										<a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo21" aria-expanded="false" aria-controls="collapseTwo21">
											<h5 class="mb-0">Lorem Ipsum is a dummy content<i class="fas fa-angle-down rotate-icon"></i></h5>
										</a>
									</div>

									<!-- Card body -->
									<div id="collapseTwo21" class="collapse" role="tabpanel" aria-labelledby="headingTwo21" data-parent="#accordionEx1">
										<div class="card-body">
											Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime dolorum esse
											quia quos molestiae libero voluptatem blanditiis, quae at necessitatibus
											reprehenderit, ullam aperiam beatae exercitationem delectus inventore.
											Voluptatem, vitae dolores.
										</div>
									</div>

								</div>
								<!-- Accordion card -->

								<!-- Accordion card -->
								<div class="card">

									<!-- Card header -->
									<div class="card-header" role="tab" id="headingThree31">
										<a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseThree31" aria-expanded="false" aria-controls="collapseThree31">
											<h5 class="mb-0">Lorem Ipsum is a dummy content<i class="fas fa-angle-down rotate-icon"></i></h5>
										</a>
									</div>

									<!-- Card body -->
									<div id="collapseThree31" class="collapse" role="tabpanel" aria-labelledby="headingThree31" data-parent="#accordionEx1">
										<div class="card-body">
											Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat accusantium
											ad tenetur. Impedit nam laudantium quas. Accusantium officia sint ea enim
											eius facere earum ab illum harum libero. Placeat, repellat!
										</div>
									</div>

								</div>
								<!-- Accordion card -->

								<!-- Accordion card -->
								<div class="card">

									<!-- Card header -->
									<div class="card-header" role="tab" id="headingFour41">
										<a class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseFour41" aria-expanded="false" aria-controls="collapseFour41">
											<h5 class="mb-0">Lorem Ipsum is a dummy content<i class="fas fa-angle-down rotate-icon"></i></h5>
										</a>
									</div>

									<!-- Card body -->
									<div id="collapseFour41" class="collapse" role="tabpanel" aria-labelledby="headingFour41" data-parent="#accordionEx1">
										<div class="card-body">
											Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat accusantium
											ad tenetur. Impedit nam laudantium quas. Accusantium officia sint ea enim
											eius facere earum ab illum harum libero. Placeat, repellat!
										</div>
									</div>

								</div>
								<!-- Accordion card -->

							</div>
							<!-- Accordion wrapper -->
						</div>
						<!-- Test Result Accordion -->
						<div class="intro-test test-result-accordion">
							<?php if(get_field('more_info_for_doctor')) echo get_field('more_info_for_doctor'); ?>
						</div>
						
		
				</div> <!-- Lipid Profile Content End -->
				<span class="test-recommended">recommended</span>
			</div>