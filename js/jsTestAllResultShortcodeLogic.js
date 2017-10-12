function showFullResults(event,sender)
{
	var jquery_elem = jQuery(sender, event);
	
	var n = jquery_elem.find(">.kv_test_result_full");
	if (n.is(":hidden"))
	{
		n.show();
	}
	else
	{
		n.hide();
	}
	 if (event.stopPropagation) {
      event.stopPropagation();   //   чтобы клик на дочерний div не распросранялся на родительский
	} else {
      event.cancelBubble = true;
  }
}
function showDateResults(event,sender)
{
	var jquery_elem = jQuery(sender);
	var n = jquery_elem.find(">.kv_test_result_date");
	if (n.is(":hidden"))
	{
		jquery_elem.find(">.kv_test_result_date").show();
	}
	else
	{
	jquery_elem.find(">.kv_test_result_date").hide();
	}
}