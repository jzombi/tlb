<?php

session_start();


$_SESSION['max'] = array( '5' =>  5,
                          '8' =>  8,
                         '12' => 12);


if (isset($_GET['reset'])) {
    unset($_SESSION['state']);
}

if (!isset($_SESSION['state'])) {
    $_SESSION['state'] = array( '5' =>  0,
                                '8' =>  0,
                               '12' => 12);
}

if ((isset($_GET['s']) and isset($_SESSION['state'][$_GET['s']])) and
    (isset($_GET['d']) and isset($_SESSION['state'][$_GET['d']]))) {
    $src = $_GET['s'];
    $dst = $_GET['d'];
    $amount = min($_SESSION['max'][$dst] - $_SESSION['state'][$dst],
                  $_SESSION['state'][$src]);
    $_SESSION['state'][$src] -= $amount;
    $_SESSION['state'][$dst] += $amount;
}


$state = $_SESSION['state'];
$max = $_SESSION['max'];
$solution = '';


if ($state['8'] == $state['12']) {
    $solution = 'stego.html';
}

?>

<html>
<head>
<title>egyenlitsd ki a merleget!</title>
</head>
<body>

<a href="ontogetos.php?reset">reset</a><br/>
<canvas id="canvas" width="400" height="200" style="border:1px solid #000000;">
</canvas>
<br/>
<?php echo $solution; ?>




<script type="text/javascript">

var source = null;
var dest = null;
var b12 = <?php echo $state['12']; ?>;
var b8 = <?php echo $state['8']; ?>;
var b5 = <?php echo $state['5']; ?>;
var rects = {};

var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");

function printLibra() {
    ctx.moveTo(200, 150);
    ctx.lineTo(200, 50);
    var y1 = 50;
    var y2 = 50;
    if (b12 > b8) {
        y1 = 70;
        y2 = 30;
    } else if (b12 < b8) {
        y1 = 30;
        y2 = 70;
    }
    ctx.moveTo(120, y1);
    ctx.lineTo(280, y2);
    ctx.moveTo(120, y1);
    ctx.lineTo(120, y1+40);
    ctx.moveTo(280, y2);
    ctx.lineTo(280, y2+40);
    ctx.stroke();
}

printLibra();

function getRects() {
    var h12 = 12*3;
    var h8 = 8*3;
    var h5 = 5*3;
    var y12 = 50+h8;
    var y8 = 50+h12;
    var y5 = 160+h5;
    if (b12 < b8) {
        y12 = 30+h12;
        y8 = 70+h8;
    } else if (b12 > b8) {
        y12 = 70+h12;
        y8 = 30+h8;
    }
    rects.r12 = [100, y12, 40, h12];
    rects.r8 = [260, y8, 40, h8];
    rects.r5 = [10, y5, 40, h5];
}

getRects();

function printBox(rect, maxvalue, value) {
    ctx.fillStyle='black';
    if (source == maxvalue) {
        ctx.fillStyle='red';
    }
    ctx.fillRect(rect[0]-2, rect[1]-2, rect[2]+4, rect[3]+4);
    ctx.fillStyle='white';
    ctx.fillRect(rect[0], rect[1], rect[2], rect[3]);
    ctx.fillStyle='blue';
    ctx.fillRect(rect[0], rect[1]+3*(maxvalue-value), rect[2], 3*(value));
    ctx.fillStyle='green';
    ctx.font = 'bold 15px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('' + value + '/' + maxvalue, rect[0]+rect[2]/2, rect[1]+rect[3]/2);
}

printBox(rects.r12, 12, b12);
printBox(rects.r8, 8, b8);
printBox(rects.r5, 5, b5);

function onRect(rect, position) {
    if ((rect[0] <= position.x) && (rect[1] <= position.y) &&
        (rect[0]+rect[2] >= position.x) && (rect[1]+rect[3] >= position.y)) {
        return true;
    }
    return false;
}

function clickEvent(position) {
    if (onRect(rects.r12, position)) {
        if (source == null) {
            source = 12;
        } else if (source == 12) {
            source = null;
        } else {
            dest = 12;
        }
    }
    if (onRect(rects.r8, position)) {
        if (source == null) {
            source = 8;
        } else if (source == 8) {
            source = null;
        } else {
            dest = 8;
        }
    }
    if (onRect(rects.r5, position)) {
        if (source == null) {
            source = 5;
        } else if (source == 5) {
            source = null;
        } else {
            dest = 5;
        }
    }
    printBox(rects.r12, 12, b12);
    printBox(rects.r8, 8, b8);
    printBox(rects.r5, 5, b5);
    if (dest != null) {
        window.location = 'ontogetos.php?s='+source+'&d='+dest;
    }
}

// stolen from: http://www.html5canvastutorials.com/advanced/html5-canvas-mouse-coordinates/
function getMousePos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    return {x: evt.clientX - rect.left, y: evt.clientY - rect.top};
}

canvas.addEventListener('click',
    function(evt) {
        var mousePos = getMousePos(canvas, evt);
        var message = 'Mouse position: ' + mousePos.x + ',' + mousePos.y;
        clickEvent(mousePos);
    }, false);


</script>


</body>
</html>