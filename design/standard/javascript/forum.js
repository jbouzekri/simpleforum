/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function(){
    if (typeof topic_view != 'undefined')
    {
    	// The topic_view cookie does not exists
    	if (!$.cookie("topic_view"))
    	{
    	    $.ajax({url:'/topic/inc_view_count/'+topic_view});
    		$.cookie("topic_view", topic_view, { path: '/', expires: 1 });
    	}
    	else
    	{
    		tmpTopicView = $.cookie("topic_view")+'';
    		topicViewArray = tmpTopicView.split('|');
    		if (topicViewArray.indexOf(topic_view+"") < 0)
    		{
    			$.ajax({url:'/topic/inc_view_count/'+topic_view});
    			topicViewArray.push(topic_view);
    			$.cookie("topic_view", topicViewArray.join('|'), { path: '/', expires: 1 });
    		}
    	}
    }
    
    if (typeof response_rate != 'undefined')
    {
    	if ($.cookie("response_rate"))
    	{
    		tmpResponseRate   = $.cookie("response_rate")+'';
    		responseRateArray = tmpResponseRate.split('|');
	    	$('div[id|=response]').each(function(){
	    		responseDivId = $(this).attr('id');
	    		responseDivId = responseDivId.split('-');	    		
	    		if (responseRateArray.indexOf(responseDivId[1]+"") >= 0) 
	    		{
	    			$(this).find('.rate p').hide();
	    		}
	    	});
    	}
    }
});