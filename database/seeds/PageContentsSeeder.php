<?php

use Illuminate\Database\Seeder;

class PageContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() //date('Y-m-d h:i:s')
    {

    	DB::table('pages')->insert([
        	[
	            'page_id' => 1,
	            'title' => 'About Us',
	            'description' => '<div class="banner_images"><img src="images/about_banner.jpg" /></div>

									<div class="banner_ctn">
									<h1>About Us</h1>

									<h4>Commitment to Fanatical Customer Service</h4>
									</div>

									<div class="container">
									<div class="row">
									<div class="col-md-7">
									<div class="ctn_who_we_are">
									<div class="row">
									<div class="col-md-12">
									<div class="heading_section">
									<h2>Who we are?</h2>
									</div>
									</div>

									<div class="col-md-5"><img src="images/who_we_are.jpg" /></div>

									<div class="col-md-7">
									<div class="ctn_pre">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus interdum erat libero, pulvinar tincidunt leo consectetur eget. Nulla vitae adipiscing nisi, in digni ssim nisl. Cras iaculis purus nec massa commodo, sit amet varius tellus comm odo. Phasellus interdum erat libero, pulvinar tincidunt tiger consectetur eget. Su spendisse semper vulputate leo ut cursus.</p>

									<p>Curabitur lacinia pellentesque tempor. Utah euismod condimentum velit en gra vida. Nulla vitae adipiscing nisi, in dignissim nisl. Cras nec tellus eget urna facilisis scelerisque vitae et sapien. Suspendisse semper vulputate leo ut cursus. Nulla vitae adipiscing nisi, in dignissim nisl. Cras iaculis purus nec massa commodo, sit amet varius tellus commodo.</p>
									</div>
									</div>
									</div>
									</div>
									</div>

									<div class="col-md-5">
									<div class="right_side_acd">
									<div class="heading_section">
									<h2>Three frequently ask question</h2>
									</div>

									<div class="panel-group" id="accordionGroupOpen">
									<div class="panel panel-default">
									<div class="panel-heading" id="headingOne">
									<h4><a href="#collapseOpenOne">1. What is work area? </a></h4>
									</div>

									<div class="collapse panel-collapse show" id="collapseOpenOne">
									<div class="panel-body">
									<p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</p>
									</div>
									</div>
									</div>

									<div class="panel panel-default">
									<div class="panel-heading" id="headingTwo">
									<h4><a class="collapsed" href="#collapseOpenTwo">2. How to purchase videos? </a></h4>
									</div>

									<div class="collapse panel-collapse" id="collapseOpenTwo">
									<div class="panel-body">
									<p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</p>
									</div>
									</div>
									</div>

									<div class="panel panel-default">
									<div class="panel-heading" id="headingThree">
									<h4><a class="collapsed" href="#collapseOpenThree">3. What is privacy policy? </a></h4>
									</div>

									<div class="collapse panel-collapse" id="collapseOpenThree">
									<div class="panel-body">
									<p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</p>
									</div>
									</div>
									</div>
									</div>
									</div>
									</div>
									</div>
									</div>

									<div class="container">
									<div class="row">
									<div class="col-md-4">
									<div class="box_sec">
									<div class="box_img"><img src="images/mission.jpg" /></div>

									<div class="box_ctn">
									<h4>Mission</h4>

									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus interdum erat libero, pulvinar tincidunt leo consectetur eget. Nulla vitae adipiscing nisi, in digni ssim nisl.</p>
									</div>
									</div>
									</div>

									<div class="col-md-4">
									<div class="box_sec">
									<div class="box_img"><img src="images/vision.jpg" /></div>

									<div class="box_ctn">
									<h4>Vision</h4>

									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus interdum erat libero, pulvinar tincidunt leo consectetur eget. Nulla vitae adipiscing nisi, in digni ssim nisl.</p>
									</div>
									</div>
									</div>

									<div class="col-md-4">
									<div class="box_sec">
									<div class="box_img"><img src="images/value.jpg" /></div>

									<div class="box_ctn">
									<h4>Value</h4>

									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus interdum erat libero, pulvinar tincidunt leo consectetur eget. Nulla vitae adipiscing nisi, in digni ssim nisl.</p>
									</div>
									</div>
									</div>
									</div>
									</div>

									<div class="container">
									<div class="row">
									<div class="col-md-6">
									<div class="box_methodology">
									<div class="heading_section">
									<h2>Who we are?</h2>
									</div>

									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus interdum erat libero, pulvinar tincidunt leo consectetur eget. Nulla vitae adipiscing nisi, in digni ssim nisl. Cras iaculis purus nec massa commodo, sit amet varius tellus comm odo. Phasellus interdum erat libero, pulvinar tincidunt tiger consectetur eget. Su spendisse semper vulputate leo ut cursus.</p>

									<p>Curabitur lacinia pellentesque tempor. Utah euismod condimentum velit en gra vida. Nulla vitae adipiscing nisi, in dignissim nisl. Cras nec tellus eget urna facilisis scelerisque vitae et sapien. Suspendisse semper vulputate leo ut cursus. Nulla vitae adipiscing nisi, in dignissim nisl. Cras iaculis purus nec massa commodo, sit amet varius tellus commodo.</p>
									</div>
									</div>

									<div class="col-md-6">
									<div class="box_methodology">
									<div class="heading_section">
									<h2>Features</h2>
									</div>

									<ul>
										<li>
										<p>Quick and convenient Localization Support rutrum erat non arcu gravida port.</p>
										</li>
										<li>
										<p>Compatible with the latest W3C standards Flexible, Responsive Layout.</p>
										</li>
										<li>
										<p>Flexible, Responsive Layout compatible with Major Browsers.</p>
										</li>
										<li>
										<p>Compatible with Major Browsers ttuned to Smartphones and Tablet PCs.</p>
										</li>
										<li>
										<p>Nunc et rutrum consetetur sadipscing elitr, sed diam nonumy at volutpat.</p>
										</li>
										<li>
										<p>Maecenas sed justo varius velit imperdietAliquam at erat in purus aliquet.</p>
										</li>
									</ul>
									</div>
									</div>
									</div>
									</div>',
	            'created_at' => date('Y-m-d h:i:s'),
	            'updated_at' => date('Y-m-d h:i:s'),
	        ],

	        [
	            'page_id' => 2,
	            'title' => 'Privacy & Policy',
	            'description' => '<main>
									<section class="inner_banner">
									<div class="banner_images"><img src="https://cdn.dnaindia.com/sites/default/files/styles/full/public/2018/08/24/721903-mall-070218.jpg" /></div>

									<div class="banner_ctn">
									<h1>Privacy Policy</h1>

									<h4>Commitment to Fanatical Customer Service</h4>
									</div>
									</section>

									<section class="privacy_policy padd-row">
									<div class="container">
									<div class="row">
									<div class="col-md-12">
									<div class="heading_policy d-flex align-items-center">
									<h2>Privacy policy</h2>
									</div>

									<ul>
										<li>- Sed ut perspiciatis unde omnis iste natus error sit voluptatem</li>
										<li>- Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit</li>
										<li>- Ut enim ad minima veniam, quis nostrum exercitationem ullam</li>
										<li>- Sed ut perspiciatis unde omnis iste natus error sit voluptatem</li>
										<li>- Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit</li>
										<li>- Ut enim ad minima veniam, quis nostrum exercitationem ullam</li>
									</ul>
									</div>

									<div class="col-md-12 mt-5">
									<div class="heading_policy d-flex align-items-center">
									<h2>Definitions</h2>
									</div>

									<ul class="list_style">
										<li><strong>At vero eos:-</strong> et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est epellat.</li>
										<li><strong>Accusamus et iusto:-</strong> odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est epellat.</li>
										<li><strong>Iusto odio dignissimos:-</strong> ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa.</li>
										<li><strong>At vero eos et accusamus:-</strong> et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa.</li>
										<li><strong>Ducimus qui blanditiis praesentium :-</strong> voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est epellat.</li>
									</ul>
									</div>

									<div class="col-md-12 mt-5">
									<div class="heading_policy d-flex align-items-center">
									<h2>Usage Data</h2>
									</div>

									<p class="mb-0"><strong>At vero eos:-</strong> et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est epellat.</p>
									</div>

									<div class="col-md-12 mt-5">
									<div class="heading_policy d-flex align-items-center">
									<h2>Location Data</h2>
									</div>

									<p class="mb-0"><strong>At vero eos:-</strong> et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est epellat.</p>
									</div>
									</div>
									</div>
									</section>
									</main>',
	            'created_at' => date('Y-m-d h:i:s'),
	            'updated_at' => date('Y-m-d h:i:s'),
	        ], 

	        [
	            'page_id' => 3,
	            'title' => 'Contact Us',
	            'description' => '<section class="inner_banner">
						            <div class="banner_images">
						                <img src="https://cdn.dnaindia.com/sites/default/files/styles/full/public/2018/08/24/721903-mall-070218.jpg">
						            </div>
						            <div class="banner_ctn">
						                <h1>Contact Us</h1>
						                <h4>Commitment to Fanatical Customer Service</h4>
						            </div>
						        </section>
						        <section class="contact_page padd-row">
						            <div class="container">
						                <div class="row">
						                    <div class="col-md-7">
						                        <div class="contact_left pr-5">
						                            <div class="heading_section">
						                                        <h2>Methodology</h2>
						                                    </div>
						                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus interdum erat libero, pulvinar tincidunt leo consectetur eget. Nulla vitae adipiscing nisi, in digni ssim nisl. Cras iaculis purus nec massa commodo, sit amet varius tellus comm odo. Phasellus interdum erat libero, pulvinar tincidunt tiger consectetur eget. Su spendisse semper vulputate leo ut cursus.</p>
						                            <p class="mb-0">Aet amet varius tellus comm odo. Phasellus interdum erat libero, pulvinar tinci dunt tiger consectetur eget. Su spendisse semper vulputate leo ut cursus.</p>
						                        </div>
						                    </div>
						                    <div class="col-md-5">
						                        <div class="contact_right">
						                        <div class="heading_section">
						                                        <h2>Our Location</h2>
						                                    </div>
						                            <ul>
						                                <li>
						                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
						                                <p>30 Your Street Name <br>Los Angeles, CA 94108, USA</p>
						                                </li>
						                                <li>
						                                    <i class="fa fa-user" aria-hidden="true"></i>
						                                <p>Phone: (123) 456-7890</p>
						                                </li>
						                                <li>
						                                    <i class="fa fa-envelope" aria-hidden="true"></i>
						                                <p>Email: contact@virtualevent.com</p>
						                                </li>
						                                <li>
						                                    <i class="fa fa-globe" aria-hidden="true"></i>
						                                <p>Web: www.virtaulevent.com</p>
						                                </li>
						                                
						                            </ul>
						                            <h4>Social Media</h4>
						                            <hr>
						                            <ul class="contact_page_social d-flex align-items-center">
						                                <li><a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						                                <li><a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
						                                <li><a href=""><i class="fa fa-rss" aria-hidden="true"></i></a></li>
						                            </ul>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </section>',
	            'created_at' => date('Y-m-d h:i:s'),
	            'updated_at' => date('Y-m-d h:i:s'),
	        ],
	        [
	            'page_id' => 4,
	            'title' => 'Contact Us',
	            'description' => '<div class="help_heading">
							<h2>frequently asked questions (121)</h2>
							</div>
							<div class="faq_ctn">
								<div class="faq_box mb-3">
									<div class="heading_stc">

										<h4>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore
										m que laudantiu totam rem aperiam, eaque ipsa quae ab illo inventore veritatis .</h4>
									</div>
									<div class="pre_faq">
										<p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore m que laudantiu totam rem aperiam, eaque ipsa quae ab illo inventore veritatis .</p>
									</div>
								</div>
								<div class="faq_box mb-3">
									<div class="heading_stc">

										<h4>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore
										m que laudantiu totam rem aperiam, eaque ipsa quae ab illo inventore veritatis .</h4>
									</div>
									<div class="pre_faq">
										<p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore m que laudantiu totam rem aperiam, eaque ipsa quae ab illo inventore veritatis .</p>
									</div>
								</div>  <div class="faq_box">
									<div class="heading_stc">

										<h4>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore
										m que laudantiu totam rem aperiam, eaque ipsa quae ab illo inventore veritatis .</h4>
									</div>
									<div class="pre_faq">
										<p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore m que laudantiu totam rem aperiam, eaque ipsa quae ab illo inventore veritatis .</p>
									</div>
								</div>
							</div>',
	            'created_at' => date('Y-m-d h:i:s'),
	            'updated_at' => date('Y-m-d h:i:s'),
	        ],
        ]);


    }
}
