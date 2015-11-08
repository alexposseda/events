$(document).ready(function() {
    $('button[role=show_event_info]').click(function () {
        var but = $(this);
        var event_id = but.attr('id').substr(6);
        $('#event_data').remove();

        $.post("/ajax/event/"+event_id, function(data){
            if(data){
                $('body').append(data);
                $('#event_data').css({
                    top: but.offset().top+'px',
                    left: but.offset().left - $('#event_data').width() + 'px',
                });
                $('#close').click(function(){
                    $('#event_data').remove();
                });
            }
        });
    });
    $('button[role=participate]').click(function(){
        var but = $(this);
        var event_id = but.attr('id').substr(6);
        $('#event_data').remove();
        $('#small_modal').remove();
        $.post("/ajax/participate/"+event_id, function(data){
            if(data){
                $('body').append(data);
                $('#small_modal').css({
                    top: but.offset().top+'px',
                    left: but.offset().left - $('#small_modal').width() + 'px',
                });
                $('#close').click(function(){
                    $('#small_modal').remove();
                    location.reload();
                });
            }
        });
    });
});
