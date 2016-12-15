/**
 * Created by ubuntu on 09/12/16.
 */

var maxX = 0;
var maxY = 0;
var x = 0;
var y = 0;
var ctx;
var canvas;
var tid;
var run;

$(document).ready(function(){

    canvas = document.getElementById("gfxArea");
    ctx = canvas.getContext("2d");

    // hide scroll bar
    $('body').css('overflow','hidden');
    $('body').css('background-color', 'black');

    // reinit canvas properties
    resizeCanvasToWindows();
    $(window).resize(function() {
        resizeCanvasToWindows();
    });

    // toggle animation running
    $(document).click(function(){
        if (run) {
            clearInterval(tid);
            run = false;
        } else {
            tid = setInterval(animate, 1);
            run = true;
        }
    });

    // start animation
    tid = setInterval(animate, 1);
    run = true;
});


// maintain canvas size to window size
function resizeCanvasToWindows(){
    maxX = $(window).width();
    maxY = $(window).height();
    canvas.width = maxX;
    canvas.height = maxY;
    ctx.fillStyle = "#ffffff";
    ctx.strokeStyle = '#ffffff';
}

function animate() {
    for (var i=0; i<100; i++) {
        x = Math.floor(Math.random() * maxX);
        y = Math.floor(Math.random() * maxY);
        ctx.fillRect(x, y, 1, 1);
    }
}
