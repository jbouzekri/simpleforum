/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function forumConfirmDiscard( question )
{
    // Ask user if he really wants to do it.
    return confirm( question );
}

jQuery(function( $ )
{
    $('.context-information .actions a, .simpleforum-topic_item .actions a, simpleforum-response_item .actions a').click(function(){
        var url = $(this).attr('href');
        $.ajax({
            url: url+'?ajax=1',
            success: function(data) {
                if (data == 0)
                {
                    window.location.href=window.location.href;
                }
                else
                {
                    $('.message-error').show();
                }
            }
        });
        return false;
    });
});
