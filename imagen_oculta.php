<script type="text/javascript">

$( document ).ready(function() {

function imgrm() {
	  $('img').each(function() {
	    if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
	      // image was broken, replace with your new image
	       $(this).parent().parent().parent().remove();
	    }
	  });
	};
	setInterval(imgrm, 3000)
	$('.product_item').removeAttr("style");
	$('.product_grid').removeAttr("style");

 });

</script>
