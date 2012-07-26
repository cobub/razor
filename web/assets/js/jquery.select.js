function initTimeSelect()
{
    $('body').delegate('.select_arrow,.selected_value','click',function(e){
    	e.stopPropagation();
      $(this).nextAll('.select_list_body').slideToggle(200);
    });
    
    $(document).click(function(e){
    	e.stopPropagation();
    	 if($(e.target).closest('#ui-datepicker-div').length>0|$(e.target).closest('.compared_date_select').length>0||$(e.target).closest('.datainp').length>0||$(e.target).closest('#calroot').length>0){//calroot
    		 	return false;
    	 }
    	$('.select_list_body').slideUp(200);
    });
    $('body').delegate('.select_list_body ul li.date_picker','click',function(e){
    	e.stopPropagation();
    	$(this).next('li').slideToggle(200);
    });	
}


