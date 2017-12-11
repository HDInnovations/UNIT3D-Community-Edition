<!DOCTYPE html>
<html>

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10; url=https://blutopia.xyz/" />
  </head>

  <body>
    <style>
    @import url("https://fonts.googleapis.com/css?family=Poppins");
    @import url("https://fonts.googleapis.com/css?family=Kaushan+Script");
    /* BASIC */
    a {
      color: #92badd;
      display: inline-block;
      text-decoration: none;
      font-weight: 400;
    }

    h2 {
      text-align: center;
      font-size: 16px;
      font-weight: 600;
      text-transform: uppercase;
      display: inline-block;
      margin: 40px 8px 10px 8px;
      color: #cccccc;
    }

    /* STRUCTURE */
    .wrapper {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
          -ms-flex-align: center;
              align-items: center;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
          -ms-flex-direction: column;
              flex-direction: column;
      -webkit-box-pack: center;
          -ms-flex-pack: center;
              justify-content: center;
      width: 100%;
      min-height: 100%;
    }

    #formContent {
      border-radius: 10px 10px 10px 10px;
      background: #fff;
      padding: 30px;
      width: 90%;
      max-width: 450px;
      position: relative;
      padding: 0px;
      box-shadow: 0 30px 60px 0 rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    #formFooter {
      background-color: #f6f6f6;
      border-top: 1px solid #dce8f1;
      padding: 25px;
      text-align: center;
      border-radius: 0 0 10px 10px;
    }

    /* TABS */
    h2.inactive {
      color: #cccccc;
    }

    h2.active {
      color: #0d0d0d;
      border-bottom: 2px solid #5fbae9;
    }

    /* FORM TYPOGRAPHY*/
    input[type=button], input[type=submit], input[type=reset] {
      background-color: #56baed;
      border: none;
      color: white;
      padding: 15px 80px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      text-transform: uppercase;
      font-size: 13px;
      box-shadow: 0 10px 30px 0 rgba(95, 186, 233, 0.4);
      border-radius: 5px 5px 5px 5px;
      margin: 5px 20px 40px 20px;
      -webkit-transition: all 0.3s ease-in-out;
      transition: all 0.3s ease-in-out;
    }

    input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover {
      background-color: #39ace7;
    }

    input[type=button]:active, input[type=submit]:active, input[type=reset]:active {
      -webkit-transform: scale(0.95);
      transform: scale(0.95);
    }

    input[type=text] {
      background-color: #f6f6f6;
      border: none;
      color: #0d0d0d;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 5px;
      width: 85%;
      border: 2px solid #f6f6f6;
      -webkit-transition: all 0.5s ease-in-out;
      transition: all 0.5s ease-in-out;
      border-radius: 5px 5px 5px 5px;
    }

    input[type=text]:focus {
      background-color: #fff;
      border-bottom: 2px solid #5fbae9;
    }

    input[type=text]:placeholder {
      color: #cccccc;
    }

    input[type=password] {
      background-color: #f6f6f6;
      border: none;
      color: #0d0d0d;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 5px;
      width: 85%;
      border: 2px solid #f6f6f6;
      -webkit-transition: all 0.5s ease-in-out;
      transition: all 0.5s ease-in-out;
      border-radius: 5px 5px 5px 5px;
    }

    input[type=password]:focus {
      background-color: #fff;
      border-bottom: 2px solid #5fbae9;
    }

    input[type=password]:placeholder {
      color: #cccccc;
    }

    button {
      background-color: #56baed;
      border: none;
      color: white;
      padding: 15px 80px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      text-transform: uppercase;
      font-size: 13px;
      box-shadow: 0 10px 30px 0 rgba(95, 186, 233, 0.4);
      border-radius: 5px 5px 5px 5px;
      margin: 5px 20px 40px 20px;
      -webkit-transition: all 0.3s ease-in-out;
      transition: all 0.3s ease-in-out;
    }

    button:hover {
      background-color: #39ace7;
    }

    button:active {
      -webkit-transform: scale(0.95);
      transform: scale(0.95);
    }

    /* ANIMATIONS */
    /* Simple CSS3 Fade-in-down Animation */
    .fadeInDown {
      -webkit-animation-name: fadeInDown;
      animation-name: fadeInDown;
      -webkit-animation-duration: 1s;
      animation-duration: 1s;
      -webkit-animation-fill-mode: both;
      animation-fill-mode: both;
    }

    @-webkit-keyframes fadeInDown {
      0% {
        opacity: 0;
        -webkit-transform: translate3d(0, -100%, 0);
        transform: translate3d(0, -100%, 0);
      }
      100% {
        opacity: 1;
        -webkit-transform: none;
        transform: none;
      }
    }
    @keyframes fadeInDown {
      0% {
        opacity: 0;
        -webkit-transform: translate3d(0, -100%, 0);
        transform: translate3d(0, -100%, 0);
      }
      100% {
        opacity: 1;
        -webkit-transform: none;
        transform: none;
      }
    }
    /* Simple CSS3 Fade-in Animation */
    @-webkit-keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }
    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }
    .fadeIn {
      opacity: 0;
      -webkit-animation: fadeIn ease-in 1;
      animation: fadeIn ease-in 1;
      -webkit-animation-fill-mode: forwards;
      animation-fill-mode: forwards;
      -webkit-animation-duration: 1s;
      animation-duration: 1s;
    }

    .fadeIn.first {
      -webkit-animation-delay: 0.4s;
      animation-delay: 0.4s;
    }

    .fadeIn.second {
      -webkit-animation-delay: 0.6s;
      animation-delay: 0.6s;
    }

    .fadeIn.third {
      -webkit-animation-delay: 0.8s;
      animation-delay: 0.8s;
    }

    .fadeIn.fourth {
      -webkit-animation-delay: 1s;
      animation-delay: 1s;
    }

    /* Simple CSS3 Fade-in Animation */
    .underlineHover:after {
      display: block;
      left: 0;
      bottom: -10px;
      width: 0;
      height: 2px;
      background-color: #56baed;
      content: "";
      -webkit-transition: width 0.2s;
      transition: width 0.2s;
    }

    .underlineHover:hover {
      color: #0d0d0d;
    }

    .underlineHover:hover:after {
      width: 100%;
    }

    /* OTHERS */
    *:focus {
      outline: none;
    }

    #icon {
      width: 60%;
    }

    * {
      box-sizing: border-box;
    }

    .text {
      fill: none;
      stroke-width: 2;
      stroke-linejoin: round;
      stroke-dasharray: 70 330;
      stroke-dashoffset: 0;
      -webkit-animation: stroke 6s infinite linear;
      animation: stroke 6s infinite linear;
      font-size: 4em;
      text-transform: uppercase;
    }

    .text:nth-child(5n + 1) {
      stroke: #c0392b;
      -webkit-animation-delay: -1.2s;
      animation-delay: -1.2s;
    }

    .text:nth-child(5n + 2) {
      stroke: #d35400;
      -webkit-animation-delay: -2.4s;
      animation-delay: -2.4s;
    }

    .text:nth-child(5n + 3) {
      stroke: #f1c40f;
      -webkit-animation-delay: -3.6s;
      animation-delay: -3.6s;
    }

    .text:nth-child(5n + 4) {
      stroke: #f39c12;
      -webkit-animation-delay: -4.8s;
      animation-delay: -4.8s;
    }

    .text:nth-child(5n + 5) {
      stroke: #e74c3c;
      -webkit-animation-delay: -6s;
      animation-delay: -6s;
    }

    @-webkit-keyframes stroke {
      100% {
        stroke-dashoffset: -400;
      }
    }
    @keyframes stroke {
      100% {
        stroke-dashoffset: -400;
      }
    }
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 3px;
      border-bottom-width: 4px;
    }

    .alert h4 {
      margin-top: 0;
      color: inherit;
    }

    .alert > p, .alert > ul {
      margin-bottom: 0;
    }

    .alert > p + p {
      margin-top: 5px;
    }

    .alert-dismissable, .alert-dismissible {
      padding-right: 35px;
    }

    .alert-dismissable .close, .alert-dismissible .close {
      position: relative;
      top: -2px;
      right: -21px;
      color: inherit;
    }

    .modal, .modal-backdrop {
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }

    .alert-success {
      background-color: #00bc63;
      border-color: #00a356;
      color: #fff;
    }

    .alert-success hr {
      border-top-color: #008948;
    }

    .alert-success .alert-link {
      color: #e6e6e6;
    }

    .alert-info {
      background-color: #3498db;
      border-color: #2386c8;
      color: #fff;
    }

    .alert-info hr {
      border-top-color: #2077b2;
    }

    .alert-info .alert-link {
      color: #e6e6e6;
    }

    .alert-warning {
      background-color: #f39c12;
      border-color: #e08e0b;
      color: #fff;
    }

    .alert-warning hr {
      border-top-color: #c87f0a;
    }

    .alert-warning .alert-link {
      color: #e6e6e6;
    }

    .alert-danger {
      background-color: #e74c3c;
      border-color: #e43725;
      color: #fff;
    }

    .alert-danger hr {
      border-top-color: #d62c1a;
    }

    .alert-danger .alert-link {
      color: #e6e6e6;
    }

    html, body {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Catamaran', sans-serif;
    }

    body {
      background: #222;
      background: -webkit-linear-gradient(top, #111, #222);
      background: linear-gradient(to bottom, #111, #222);
    }

    .container {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 100%;
      overflow: hidden;
    }

    svg {
      z-index: 10;
    }

    .message {
      color: #fd870b;
      font-family: creepster;
      height: 200px;
      letter-spacing: 2px;
      position: absolute;
      text-align: center;
      text-shadow: 1px 1px 10px #4d5c5f;
      top: 50%;
      width: 600px;
      z-index: 999999;
      right: 15%;
    }

    .message h1 {
      font-size: 60px;
    }

    /*Button Three*/
.button-three {
    position: relative;
    background-color: #f39c12;
    border: none;
    padding: 20px;
    width: 200px;
    text-align: center;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    text-decoration: none;
    overflow: hidden;
}

.button-three:hover{
   background:#fff;
   box-shadow:0px 2px 10px 5px #97B1BF;
   color:#000;
}

.button-three:after {
    content: "";
    background: #f1c40f;
    display: block;
    position: absolute;
    padding-top: 300%;
    padding-left: 350%;
    margin-left: -20px !important;
    margin-top: -120%;
    opacity: 0;
    transition: all 0.8s
}

.button-three:active:after {
    padding: 0;
    margin: 0;
    opacity: 1;
    transition: 0s
}
    </style>

    <div class="container">
      <svg viewBox="0 0 1320 100">

        <symbol id="s-text">
          <text text-anchor="middle" x="50%" y="50%" dy=".35em">
            Blutopia Horror Month
          </text>
        </symbol>

        <!-- Duplicate symbols -->
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
      </svg>
      <div class="message">
        <h1>#Happy Halloween</h1>
      </div>
      <svg id="svg"></svg>
    </div>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.4.1/snap.svg-min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js'></script>
    <script src='https://use.edgefonts.net/creepster.js'></script>

    <script>
    "use strict";
    var Ghost = (function () {
        function Ghost(svg, start, end) {
            this.id = String(Math.round(Math.random() * 999999999999999));
            this.group = svg.group();
            this.startPoint = start;
            this.endPoint = end;
            this.startThickness = 0;
            this.endThickness = 150 + Math.round(Math.random() * 50);
            this.guidePosition = Math.random() * 1000;
            this.frequency = 0.01 + Math.random() * 0.01;
            this.amplitude = 20 + Math.random() * 40;
            this.height = 0;
            this.endHeight = 150 + Math.round(Math.random() * 100);
            this.y = 0;
            var faceAttr = {
                fill: '#111111',
                opacity: 0.9,
                stroke: 'none'
            };
            this.body = this.group.path().attr({
                fill: '#eeeeee',
                opacity: 0.8,
                stroke: 'none'
            });
            this.eyeLeft = this.group.path().attr(faceAttr);
            this.eyeRight = this.group.path().attr(faceAttr);
            this.mouth = this.group.path().attr(faceAttr);
            this.updateGuide();
        }
        Ghost.prototype.remove = function () {
            this.group.remove();
        };
        Ghost.prototype.updateGuide = function () {
            this.guide = [];
            var height = this.startPoint.y - this.endPoint.y;
            var widthChange = this.startPoint.x - this.endPoint.x;
            var y = this.startPoint.y;
            while (y-- >= this.endPoint.y) {
                var x = this.startPoint.x + (widthChange - (widthChange / height) * y);
                var wave = Math.sin(y * this.frequency + this.guidePosition);
                this.guide.push({ y: y, x: x + (wave * this.amplitude / 2 + this.amplitude / 2) });
            }
            //console.log(this.guide)
        };
        Ghost.prototype.start = function (onComplete) {
            TweenMax.to(this, 2, { y: this.guide.length, height: this.endHeight, position: '+=6', ease: SlowMo.ease.config(0.3, 0.3, false), onComplete: onComplete, onCompleteParams: [this] });
        };
        Ghost.prototype.getPointAlongGuide = function (y, offsetXPercentage) {
            if (this.guide.length) {
                if (y >= this.guide.length)
                    y = this.guide.length - 1;
                if (y < 0)
                    y = 0;
                var thicknessDifference = this.endThickness - this.startThickness;
                var percentageAlongGuide = (y / this.guide.length) * 100;
                var thickness = this.startThickness + ((thicknessDifference / 100) * percentageAlongGuide);
                var xOffset = ((thickness / 2) / 100) * offsetXPercentage;
                return { x: this.guide[y].x + xOffset, y: this.guide[y].y };
            }
            return { x: 0, y: 0 };
        };
        Ghost.prototype.drawPath = function (pathPoints) {
            var points = [];
            for (var i = 0; i < pathPoints.length; i++) {
                var subPoints = [];
                for (var j = 0; j < pathPoints[i].points.length / 2; j++) {
                    var p = pathPoints[i].points.slice(j * 2, j * 2 + 2);
                    //console.log(i, p)
                    var point = this.getPointAlongGuide(Math.round(p[1]), p[0]);
                    subPoints.push(point.x);
                    subPoints.push(point.y);
                }
                points.push(pathPoints[i].type + subPoints.join(' '));
            }
            return points.join(' ') + 'Z';
        };
        Ghost.prototype.draw = function () {
            if (this.height > 0) {
                var y = Math.round(this.y);
                var height = Math.round(this.height);
                var heightChunks = height / 6;
                var body = [
                    { type: 'M', points: [10, y] },
                    { type: 'Q', points: [75, y, 80, y - heightChunks * 2] },
                    { type: 'L', points: [85, y - heightChunks * 3,
                            90, y - heightChunks * 4,
                            95, y - heightChunks * 5,
                            100, y - heightChunks * 6,
                            75, y - heightChunks * 5,
                            50, y - heightChunks * 6,
                            25, y - heightChunks * 5,
                            0, y - heightChunks * 6,
                            -25, y - heightChunks * 5,
                            -50, y - heightChunks * 6,
                            -75, y - heightChunks * 5,
                            -100, y - heightChunks * 6,
                            -95, y - heightChunks * 5,
                            -90, y - heightChunks * 4,
                            -85, y - heightChunks * 3,
                            -80, y - heightChunks * 2
                        ] },
                    { type: 'Q', points: [-75, y, 10, y] },
                ];
                this.body.attr({ d: this.drawPath(body) });
                var leftEye = [
                    { type: 'M', points: [-40, y - heightChunks * 2] },
                    { type: 'Q', points: [-50, y - heightChunks * 2, -50, y - heightChunks * 2.5] },
                    { type: 'Q', points: [-50, y - heightChunks * 3, -40, y - heightChunks * 3] },
                    { type: 'Q', points: [-30, y - heightChunks * 3, -30, y - heightChunks * 2.5] },
                    { type: 'Q', points: [-30, y - heightChunks * 2, -40, y - heightChunks * 2] }
                ];
                this.eyeLeft.attr({ d: this.drawPath(leftEye) });
                var rightEye = [
                    { type: 'M', points: [40, y - heightChunks * 2] },
                    { type: 'Q', points: [50, y - heightChunks * 2, 50, y - heightChunks * 2.5] },
                    { type: 'Q', points: [50, y - heightChunks * 3, 40, y - heightChunks * 3] },
                    { type: 'Q', points: [30, y - heightChunks * 3, 30, y - heightChunks * 2.5] },
                    { type: 'Q', points: [30, y - heightChunks * 2, 40, y - heightChunks * 2] }
                ];
                this.eyeRight.attr({ d: this.drawPath(rightEye) });
                var mouth = [
                    { type: 'M', points: [0, y - heightChunks * 3] },
                    { type: 'Q', points: [20, y - heightChunks * 3, 20, y - heightChunks * 3.5] },
                    { type: 'Q', points: [20, y - heightChunks * 4.5, 0, y - heightChunks * 4.5] },
                    { type: 'Q', points: [-20, y - heightChunks * 4.5, -20, y - heightChunks * 3.5] },
                    { type: 'Q', points: [-20, y - heightChunks * 3, 0, y - heightChunks * 3] }
                ];
                this.mouth.attr({ d: this.drawPath(mouth) });
            }
        };
        return Ghost;
    }());
    var StageManager = (function () {
        function StageManager(svg) {
            this.svg = svg;
            this.ghosts = {};
            this.size = { width: 0, height: 0 };
        }
        StageManager.prototype.init = function () {
            var _this = this;
            window.addEventListener('resize', function () { return _this.onResize(); }, true);
            this.onResize();
            this.tick();
        };
        StageManager.prototype.onResize = function () {
            this.size.width = window.innerWidth;
            this.size.height = window.innerHeight;
            this.svg
                .attr('width', this.size.width)
                .attr('height', this.size.height);
            // for(let i in this.ghosts)
            // {
            // 	this.ghosts[i].updateGuide();
            // }
        };
        StageManager.prototype.addGhost = function () {
            var _this = this;
            var start = { x: this.size.width / 2, y: this.size.height };
            var end = { x: (this.size.width / 4) + Math.random() * (this.size.width / 2), y: -300 };
            var ghost = new Ghost(this.svg, start, end, this.onGhostComplete);
            ghost.start(function (ghost) { return _this.removeGhost(ghost); });
            this.ghosts[ghost.id] = ghost;
        };
        StageManager.prototype.removeGhost = function (ghost) {
            delete this.ghosts[ghost.id];
            ghost.remove();
            ghost = null;
        };
        StageManager.prototype.tick = function () {
            var _this = this;
            for (var i in this.ghosts) {
                this.ghosts[i].draw();
            }
            requestAnimationFrame(function () { return _this.tick(); });
        };
        return StageManager;
    }());
    var stageManager = new StageManager(Snap('svg'));
    stageManager.init();
    makeGhost();
    function makeGhost() {
        stageManager.addGhost();
        setTimeout(makeGhost, Math.random() * 500);
    }
    </script>

  </body>

</html>
