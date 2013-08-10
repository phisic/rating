$(document).ready(function(){
	var options={
		placement: 'bottom',
		content: popover_content,
		html: true,
		//trigger: 'hover',
		//number: 0
	}
	$('#brand_name').popover(options);
	function popover_content(){
		return $('#'+$(this).attr('id')+'_content').html();
	}
	var brand_name_show=false;
	var brand_name_timer=false;
	$('#brand_name').parent().hover(function(){
										if ( !brand_name_show ) {
											$(this).find('a[rel=popover]').popover('show');
											brand_name_show=true;
										}
										clearInterval(brand_name_timer);
									}, function(){
										var brand_name_obj=$(this).find('a[rel=popover]');
										clearInterval(brand_name_timer);
										brand_name_timer=setInterval( function(){
											clearInterval(brand_name_timer);
											if ( brand_name_show )
												brand_name_obj.popover('hide');
											brand_name_show=false;
										}, 200 );
										
									});
	/*$('.category_list ul a').live(	{
										mouseenter: function(){alert($(this).html())
														//$(this).parent(2).find('a.active').removeClass('active');
														$(this).addClass('active');
													},
										mouseleave: function(){

													}
									});*/

});
$(document).on('mouseover', '.category_list a', function(){
	$(this).parent().parent().find('a.active').removeClass('active');
	$(this).addClass('active');
	$($(this).attr('data-target')).parent().find('div').hide();
	$($(this).attr('data-target')).show();
});
$(document).on('mouseout', '.category_list a', function(){
	//$(this).removeClass('active');
});