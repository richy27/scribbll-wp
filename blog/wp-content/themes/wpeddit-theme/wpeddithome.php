<?php
/*
Template Name: Home
*/

$wpstyle = get_option('wpedditstyle');
if($wpstyle != 'default'){
get_header();
}else{
get_header();
}

?>


  <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
        <div class="item active">
          <img src="http://twitter.github.com/bootstrap/assets/img/examples/slide-01.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>The new way to blog.</h1>
              <p class="lead">wpeddit is the new way to create your website. Show your visitors your hottest posts first. Let them get involved in the ratings</p>
              <a class="btn btn-large btn-primary" href="http://reddit.epicplugins.com/">View Blog</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="http://twitter.github.com/bootstrap/assets/img/examples/slide-02.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Start using those categories.</h1>
              <p class="lead">Yep. Now those categories really come into play. People can vote on them and have fun</p>
              <a class="btn btn-large btn-primary" href="#">See Categories</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="http://twitter.github.com/bootstrap/assets/img/examples/slide-03.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Bootstrap.</h1>
              <p class="lead">All the power of bootstrap. The theme is built on the twitter bootstrap. It can be skinned by any of the bootstrap skins with only a few small tweaks</p>
              <a class="btn btn-large btn-primary" href="http://codecanyon.net/category/skins/bootstrap?ref=mikemayhem3030">View Skins</a>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div><!-- /.carousel -->



    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="span4">
         
          <h2>Home Template</h2>
          <p>Show a page just like this on your website easily. It only takes a few minutes to set up with the custom page template included</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div><!-- /.span4 -->
        <div class="span4">

          <h2>Full Width</h2>
          <p>Aren't those themes annoying that do not have a full width page template for you to use? Well. We do. And it rocks!</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div><!-- /.span4 -->
        <div class="span4">

          <h2>Responsive</h2>
          <p>What's more is the theme looks pretty EPIC on any device. Thanks again to the power of the bootstrap. Check it out on your device today</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div><!-- /.span4 -->
      </div><!-- /.row -->


      <!-- START THE FEATURETTES -->
	<div class = "row">
      <hr class="featurette-divider">

      <div class="featurette">
        <img class="featurette-image pull-right" src="http://edudemic.com/wp-content/uploads/2010/11/wplogo.png" width = "200px">
        <h2 class="featurette-heading">Post rating system. <span class="muted">It'll blow your mind.</span></h2>
        <p class="lead">There's a reason that reddit is so darn popular. Find out which posts are the most popular and keep writing more of them!</p>
      </div>
	</div>
	
    <div class = "row">
      <hr class="featurette-divider">

      <div class="featurette">
        <img class="featurette-image pull-left" src="http://edudemic.com/wp-content/uploads/2010/11/wplogo.png" width = "200px">
        <h2 class="featurette-heading">Oh yeah, it's that good. <span class="muted">See for yourself.</span></h2>
        <p class="lead">Check around the site. It looks awesome on Safari, Chrome, Firefox and even IE.</p>
      </div>
	</div>
		<div class = "row">
      <hr class="featurette-divider">

      <div class="featurette">

        <h2 class="featurette-heading">And lastly, this one. <span class="muted">MULTI SKIN COMPATIBLE.</span></h2>
        <p class="lead">Not a fan of the two starter themes? Well the beauty of this theme is that it is secretly <strong>a hand crafted template</strong> to work with the bootstrap. There are more and more skins on 
        	<a href = "http://codecanyon.net/category/skins/bootstrap?ref=mikemayhem3030" target = "_blank">CodeCanyon</a> every day. We are
        	also working on a couple of corkers!</p>
      </div>
     </div>

  

      <!-- /END THE FEATURETTES -->

</div>


<?php get_footer(); ?>
        
       