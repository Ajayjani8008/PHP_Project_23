<?php 
$crud = new Crud();
$result = $crud->getData('home', "id=1", '', '');
$home_data = $result[0];
$home_json_data = json_decode($home_data['header_section'], true);
$main_site_url = getMainSiteUrl();
?>
<header id="h-home" class="h-home-wapper">
	<div class="h-header-main">
		<div class="container w-1557">
			<div class="h-header-nav">
				<div class="h-header-logo">
					<a href="<?php echo $GLOBALS['new_site_url']?>"><img src="<?php echo $GLOBALS['site_url'].$home_json_data['logo']; ?>" class="img-fluid" alt="logo"></a>
				</div>
				<div class="h-main-menu" id="h-sidebar-wrapper">
					<div class="h-header-card">
						<ul>
							<li class="active"><a href="<?php echo $GLOBALS['new_site_url']?>">Home</a></li>
							<li><a href="<?php echo $GLOBALS['new_site_url']?>services">Services</a></li>
							<li><a href="<?php echo $GLOBALS['new_site_url']?>projects">Projects</a></li>
							<li><a href="<?php echo $GLOBALS['new_site_url']?>blog">Blog</a></li>
							<li><a href="<?php echo $GLOBALS['new_site_url']?>contact_us">Contact Us</a></li>
							<li><a href="<?php echo $GLOBALS['new_site_url']?>about">About Us</a></li>
							<li><a href="<?php echo $GLOBALS['new_site_url']?>career">Career</a></li>
						</ul>
					</div>
				</div>
				<div class="menu-right-wapper">
					<div class="h-toggle-btn" id="h-toggle-btn">
						<div class="line-card one"></div>
						<div class="line-card two"></div>
						<div class="line-card three"></div>
					</div>

					<div class="subscribe-wapper">
						<a href="<?php echo $home_json_data['sign_button_url'] ?>"><?php echo $home_json_data['sign_button'] ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>