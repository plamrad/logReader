$(document).ready(function(){
    var vertical_x = 0;

    var lastPos = 0;

    $(window).scroll(function () {
        var currPos = $(document).scrollLeft();
        lastPos = currPos;
    });

    $(document).on('mousemove', function (e) {
        $('#vertical_line').css({
            left: (e.pageX - 1),
            top: (e.pageY - 1000)
        });
        $('#vertical_line_position_info').text('x=' + (e.pageX - 128));
        vertical_x = e.pageX;
    });

    $(document).on('mouseover', '.log_box', function () {
        $(this).addClass('mouseover');
    });

    $(document).on('mouseout', '.log_box', function () {
        $(this).removeClass('mouseover');
    });

    $(document).on('click', '.log_box', function () {
        $('#ts_clicked_info').text('ts: ' + vertical_x);

        var highlight_count = 0;
        $('.log_box').removeClass('highlight_box');

        var i = $(this).attr('data-i');
        var start = 0;

        if ((i - 50) > 0) {
            start = (i - 50);
        }

        var box_count = start + 100;
        for (j = start; j < box_count; j++) {
            if (j > 0) {
                var log_box = $('#log_box_' + j)[0].getBoundingClientRect();
                if (vertical_x > (log_box.left + lastPos) && vertical_x <= (log_box.right + lastPos)) {
                    $('#log_box_' + j).addClass('hightlight_box');
                    highlight_count++;
                } else {
                    $('#log_box_' + j).removeClass('hightlight_box');
                }
            }
        }
        $('#logPeak_info').text('concurrent Logs ' + highlight_count);
    });
});
