/**
 * Created by ubuntu on 09/12/16.
 */

var tid;

$(document).ready(function(){
    tid = setInterval(pictureRetry, 3000);
});

function pictureRetry(){
    var total = 0;
    $('img').each(function() {
        if (!$(this).complete || typeof $(this).naturalWidth == "undefined" || $(this).naturalWidth == 0) {
            $(this).attr("src", $(this).attr("src"));
            total++;
        }
    });
    if (total == 0) {
        clearInterval(tid);
    }
}