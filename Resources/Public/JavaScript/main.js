jQuery(document).ready(function() {
	jQuery('.social-post.asset-fb_video.fancybox').click(function(){
		jQuery.fancybox({
			'href':		$(this).data('link'),
			'type': 	'iframe',
			'padding': 	0,
			'height': 	405,
			'width': 	720
		});
	});
	jQuery('.social-post.asset-yt_video.fancybox').click(function(){
		jQuery.fancybox({
			'href':		$(this).data('link'),
			'type': 	'iframe',
			'padding': 	0,
			'height': 	405,
			'width': 	720
		});
	});
});