$(document).on('mouseover', '.category_list a', function(){
	$(this).parent().parent().find('a.active').removeClass('active');
	$(this).addClass('active');
	$($(this).attr('data-target')).parent().find('div').hide();
	$($(this).attr('data-target')).show();
});
$(document).on('mouseout', '.category_list a', function(){
	//$(this).removeClass('active');
});