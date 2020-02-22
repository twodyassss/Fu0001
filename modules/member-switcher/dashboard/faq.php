<div class="main-content" style="margin-left: -20px;">
<div class="navbar-wrapper">
    <div class="container-fluid">
        <nav class="navbar navbar-inverse navbar-static-top" style="margin-left: -20px;">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar3">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <img class="navbar-brand" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/um_logo.png'; ?>">
                </div>
                <div id="navbar3" class="navbar-collapse collapse" style="margin-top: 7px;">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php $url = admin_url(); ?>admin.php?page=UM-Switcher"><span class="glyphicon glyphicon-signal" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                        <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_settings"><span class="glyphicon glyphicon-calendar" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                        <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_faq"><span class="glyphicon glyphicon-question-sign" style="color: #000; font-size: 30px;"></span></a></li>
                        <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_email"><span class="glyphicon glyphicon-envelope" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                        <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_support"><span class="	glyphicon glyphicon-cog" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
            <!--/.container-fluid -->
        </nav>
    </div>
</div>
<div id="myCarousel" class="carousel slide" data-ride="carousel"  style="margin-bottom: 30px;">
<div class="carousel-inner" role="listbox">
    <div class="item active" style="background-color: #000;height: 500px">
         <div class="container">
            <div class="carousel-caption" style="position: absolute;
    right: 15%;
    bottom: 80px;
    left: 15%;
    z-index: 10;
    padding-top: 20px;
    padding-bottom: 60px;
    color: #fff;
    text-align: center;
    text-shadow: 0 1px 2px rgba(0,0,0,.6)">
                <h1>FAQ</h1>
                <p>Here are the most frequently asked questions.</p>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid marketing" style="padding: 25px; padding-top: 0px;">
<div class="row row-eq-height" style="margin-top: 15px;">
    <div class="col-sm-6">
        <h2>Why do I need a cronjob?</h2>
        <p>UM switcher needs a cronjob for sending accurate email reminders on autopilot to clients before and after their subscriptions expire.  The default wp cron is not accurate, as the default wp cron first needs visitors to your site to work. With a cronjob, your email reminders are always on time.</p>
    </div>
    <div class="col-sm-6">
        <h2>How to set up a cronjob?</h2>
        <p>This is a difficult question to answer. Different hosting providers use different control panels and different methods. Contact your hosting provider and ask how to set up a cron-job. We do not give free support on creating a cronjob. This might be a time consuming-process and it is not included in the license fee. We hope for your understanding on this matter.</p>
    </div>
</div>
<div class="row row-eq-height">
    <div class="col-sm-6">
        <h2 style="margin-top: -40px;">What's included in support?</h2>
        <p>We provide support through our support forums and provide help with questions about the technical function of um switcher. We do not support any questions about the technical functions of Ultimate Member and WooCommerce. Visit the developer's website for more information and answers to your questions. Logging in to your website/server to view a problem in your installation/server is excluded. Due to the time involved, this is not included in the license price.</p>
    </div>
    <div class="col-sm-6">
        <h2 style="margin-top: -40px;">My hosting does not offer a cronjob.</h2>
        <p>Most hosting providers offer this feature by default via your control panel. Some budget-hosting providers do not offer a cronjob. If that's the case your probably have a shared hosting package. If you don't want to upgrade your hosting you can make use of services like Easy Cron. For more information visit: <a href="https://www.easycron.com?ref=72867" target="_blank">Easycronjobs.com</a></p>
    </div>
</div>
<div class="row row-eq-height">
    <div class="col-sm-6">
        <h2 style="margin-top: -40px;">Community roles is now user roles</h2>
        <p>Since Ultimate member 2.0 the community roles have changed to the default WP user role system. Check your user roles and update the UMS product and hit save! The UMS products are now updated and will work with user roles.</p>
    </div>
    <div class="col-sm-6">  
	<h2 style="margin-top: -40px;">Predefined Field Display expire date</h2>
        <p>From your Ultimate member Forms you will see a new predefined field: UM Expiry date. Add this field and set the label, visibility and privacy settings from the field options. When you have created a form before you'll know how it works.</p>
    </div>
</div>

<?php include 'footer.php';?>