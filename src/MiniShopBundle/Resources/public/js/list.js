$(function(){
    $('a.jq_remove').on('click', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .one('click', '#delete', function() {
                $('#confirm').modal('hide');
                $.getJSON(url, function(json){
                    if( json && json.hasOwnProperty('status') && json.status == 'ok' ){
                        window.location.reload();
                    }
                })
            });
    });
});