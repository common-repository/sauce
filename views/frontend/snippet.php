

<!-- Sauce JS SDK -->
<script type='text/javascript'>
  window.Sauce={isReady:!1,options:{},object:{},describe:function(a,b){this.object[a]=b},init:function(a){for(var b in a)this.options[b]=a[b];var c=document.createElement("script");c.src="//dtt617kogtcso.cloudfront.net/sauce.min.js",c.async=!0,c.id="sauce-js",document.getElementsByTagName("head")[0].appendChild(c)},readyCallbacks:[],ready:function(a){this.isReady?a():this.readyCallbacks.push(a)}};

  <?php if (isset($details)) { ?>
    // Describe the article being viewed
    Sauce.describe("article", <?php echo json_encode($details); ?>);  
  <?php } ?>
  
  // Start the engines
  Sauce.init(<?php echo json_encode($options); ?>); 
</script>
<!-- End of Sauce JS SDK -->

