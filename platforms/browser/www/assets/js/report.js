$(function() {
    $('#ajaxTest').on('change', 'select[name="taegwang"]', function(){
        var ded = $(this).children('option:selected').text();

        $.ajax({
            url : '/report/testMethod2/',
            type: 'POST',
            dataType: 'text',
            data: {
                haha: ded,
                no: $(this).val()
            },
            beforeSend: function(){
                console.log('beforeSend');
            },
            success: function(data) {
                console.log(data);
            },
            error: function(e) {
                console.log(e);
            },
            complete:function(){
                console.log('complete');
            }
        });
    });
});

function dmy(text) {
    var json = {
        "start_date" : '2017-02-12',
        "end_date" : '2017-02-12'
    }

    $.ajax({
        url : '/report/testMethod/',
        type: "POST",
        data: json,
        dataType: "json",
        success: function(data)
        {
            console.log(data);
        },
        error: function(e)
        {
            console.log(e);
        }
    });
}