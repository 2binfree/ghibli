var maxFlake = 20;
var wind = 0;
var speed = 1;
var density = 5;
var flakes = [];
var maxX ;
var maxY;
var run = true;
var tid = null;

$(document).ready(function(){

    maxX = $(window).width();
    maxY = $(window).height();

    for (var i=0; i<maxFlake; i++) {
        flakes.push({
            x: Math.floor(Math.random() * maxX),
            y: Math.floor(Math.random() * maxY),
            size: Math.floor(Math.random() * 5),
        });
        $(document.body).append(
            "<div id='flake" + i + "' style='position:absolute;'><img src='/img/flake.jpg' style='width:50px; height:50px;'></div>"
        );
    }

    tid = setInterval(animate, 2000);

    // toggle animation running
    $(document).click(function(){
        if (run) {
            clearInterval(tid);
            run = false;
        } else {
            tid = setInterval(animate, 2000);
            run = true;
        }
    });
});

function animate(){
    for(var i=0; i < maxFlake; i++) {
        $('#flake' + i).top = flakes[i].y;
        $('#flake' + i).left = flakes[i].x;
    }
}
