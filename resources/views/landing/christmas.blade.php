<!DOCTYPE html>
<html>

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10; url=https://blutopia.xyz/" />
  </head>

  <style>
  *, html, body {
    box-sizing: border-box;
  }

  body {
    height: 100vh;
    background-color: #E7F4FA;
    overflow-x: hidden;
    overflow-y: scroll;
    font-family: 'Lato', sans-serif;
    color: #0C1110;
  }

  .background {
    height: 100vh;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background: -webkit-linear-gradient(#3A2959, #64A9EA) no-repeat;
    background: linear-gradient(#3A2959, #64A9EA) no-repeat;
  }

  .foreground {
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 1000;
    opacity: .99;
  }

  svg.background__ground {
    position: absolute;
    bottom: 0;
  }
  @media screen and (min-width: 1100px) {
    svg.background__ground {
      bottom: -2em;
    }
  }
  @media screen and (min-width: 1400px) {
    svg.background__ground {
      bottom: -4em;
    }
  }

  #snowman {
    width: 14em;
    position: absolute;
    bottom: 0;
    margin: 1em;
  }
  @media screen and (min-width: 720px) {
    #snowman {
      bottom: 2em;
      left: 1em;
      width: 22em;
    }
  }
  @media screen and (min-width: 900px) {
    #snowman {
      bottom: 3em;
    }
  }
  @media screen and (min-width: 1100px) {
    #snowman {
      left: 4em;
      width: 28em;
      bottom: 2em;
    }
  }
  @media screen and (min-width: 1500px) {
    #snowman {
      bottom: 4em;
    }
  }

  #cat {
    bottom: 1em;
    right: 2em;
    width: 9em;
    position: absolute;
  }
  @media screen and (min-width: 720px) {
    #cat {
      width: 18em;
      bottom: 3em;
    }
  }
  @media screen and (min-width: 1100px) {
    #cat {
      width: 19em;
    }
  }
  @media screen and (min-width: 1500px) {
    #cat {
      width: 22em;
    }
  }

  g#eye1, g#eye2 {
    -webkit-animation: 5s blink 2s ease-in-out infinite;
            animation: 5s blink 2s ease-in-out infinite;
    -webkit-transform-origin: 50% 50%;
            transform-origin: 50% 50%;
  }

  @-webkit-keyframes blink {
    0% {
      -webkit-transform: scaleY(1) scaleX(1);
              transform: scaleY(1) scaleX(1);
    }
    2% {
      -webkit-transform: scaleY(0) scaleX(0.6);
              transform: scaleY(0) scaleX(0.6);
    }
    4% {
      -webkit-transform: scaleY(1) scaleX(1);
              transform: scaleY(1) scaleX(1);
    }
  }

  @keyframes blink {
    0% {
      -webkit-transform: scaleY(1) scaleX(1);
              transform: scaleY(1) scaleX(1);
    }
    2% {
      -webkit-transform: scaleY(0) scaleX(0.6);
              transform: scaleY(0) scaleX(0.6);
    }
    4% {
      -webkit-transform: scaleY(1) scaleX(1);
              transform: scaleY(1) scaleX(1);
    }
  }
  g#scarf-behind {
    -webkit-transform-origin: right 50%;
            transform-origin: right 50%;
    -webkit-animation: 3s blow 2s infinite;
            animation: 3s blow 2s infinite;
  }

  @-webkit-keyframes blow {
    0% {
      -webkit-transform: rotate() scaleX(1);
              transform: rotate() scaleX(1);
      -webkit-animation-timing-function: ease-in;
              animation-timing-function: ease-in;
    }
    20% {
      -webkit-transform: rotate(5deg) scaleX(0.9);
              transform: rotate(5deg) scaleX(0.9);
      -webkit-animation-timing-function: linear;
              animation-timing-function: linear;
    }
    40% {
      -webkit-transform: rotate(-4deg) scaleX(1);
              transform: rotate(-4deg) scaleX(1);
    }
    60% {
      -webkit-transform: rotate(0) scaleX(1);
              transform: rotate(0) scaleX(1);
      -webkit-animation-timing-function: ease-out;
              animation-timing-function: ease-out;
    }
  }

  @keyframes blow {
    0% {
      -webkit-transform: rotate() scaleX(1);
              transform: rotate() scaleX(1);
      -webkit-animation-timing-function: ease-in;
              animation-timing-function: ease-in;
    }
    20% {
      -webkit-transform: rotate(5deg) scaleX(0.9);
              transform: rotate(5deg) scaleX(0.9);
      -webkit-animation-timing-function: linear;
              animation-timing-function: linear;
    }
    40% {
      -webkit-transform: rotate(-4deg) scaleX(1);
              transform: rotate(-4deg) scaleX(1);
    }
    60% {
      -webkit-transform: rotate(0) scaleX(1);
              transform: rotate(0) scaleX(1);
      -webkit-animation-timing-function: ease-out;
              animation-timing-function: ease-out;
    }
  }
  #snowman1 {
    -webkit-transform-origin: 50% 100%;
            transform-origin: 50% 100%;
  }

  #snowman2 {
    -webkit-transform-origin: 50% 100%;
            transform-origin: 50% 100%;
  }

  g.background__snowman {
    -webkit-transform-origin: 50% 100%;
            transform-origin: 50% 100%;
  }

  .red {
    fill: #aa1231;
    -webkit-animation: 0.6s red-flash ease-in-out infinite;
            animation: 0.6s red-flash ease-in-out infinite;
  }
  @-webkit-keyframes red-flash {
    40% {
      fill: #ea385c;
    }
    80% {
      fill: #aa1231;
    }
  }
  @keyframes red-flash {
    40% {
      fill: #ea385c;
    }
    80% {
      fill: #aa1231;
    }
  }
  .yellow {
    fill: #c1881c;
    -webkit-animation: 0.6s yellow-flash ease-in-out infinite;
            animation: 0.6s yellow-flash ease-in-out infinite;
  }
  @-webkit-keyframes yellow-flash {
    40% {
      fill: #e7b75c;
    }
    80% {
      fill: #c1881c;
    }
  }
  @keyframes yellow-flash {
    40% {
      fill: #e7b75c;
    }
    80% {
      fill: #c1881c;
    }
  }
  .blue-lt {
    fill: #1f3e64;
    -webkit-animation: 0.6s blue-lt-flash ease-in-out infinite;
            animation: 0.6s blue-lt-flash ease-in-out infinite;
  }
  @-webkit-keyframes blue-lt-flash {
    40% {
      fill: #386fb1;
    }
    80% {
      fill: #1f3e64;
    }
  }
  @keyframes blue-lt-flash {
    40% {
      fill: #386fb1;
    }
    80% {
      fill: #1f3e64;
    }
  }
  .blue-dk {
    fill: #0f1f2f;
    -webkit-animation: 0.6s blue-dk-flash ease-in-out infinite;
            animation: 0.6s blue-dk-flash ease-in-out infinite;
  }
  @-webkit-keyframes blue-dk-flash {
    40% {
      fill: #28527c;
    }
    80% {
      fill: #0f1f2f;
    }
  }
  @keyframes blue-dk-flash {
    40% {
      fill: #28527c;
    }
    80% {
      fill: #0f1f2f;
    }
  }
  .gold-dk {
    fill: #69512a;
    -webkit-animation: 0.6s gold-dk-flash ease-in-out infinite;
            animation: 0.6s gold-dk-flash ease-in-out infinite;
  }
  @-webkit-keyframes gold-dk-flash {
    40% {
      fill: #b28947;
    }
    80% {
      fill: #69512a;
    }
  }
  @keyframes gold-dk-flash {
    40% {
      fill: #b28947;
    }
    80% {
      fill: #69512a;
    }
  }
  .baubles-g1 {
    -webkit-animation-delay: .8s;
            animation-delay: .8s;
  }

  .baubles-g2 {
    -webkit-animation-delay: 1.1s;
            animation-delay: 1.1s;
  }

  .star-left {
    fill: #E7B75C;
  }

  .star-right {
    fill: #B28947;
  }

  .container--snowflakes {
    max-width: 1200px;
    margin: 3em auto 1em auto;
  }

  #robin {
    position: absolute;
    bottom: 2em;
    left: 3em;
    width: 4em;
  }
  @media screen and (min-width: 1100px) {
    #robin {
      width: 5em;
    }
  }

  /* Fireworks */
  g.circle1, g.circle2 {
    -webkit-transform: scale(0.2);
            transform: scale(0.2);
    -webkit-transform-origin: 50% 50%;
            transform-origin: 50% 50%;
    opacity: 0;
  }

  g.circle1 {
    -webkit-animation: 4s explode ease-out .7s infinite;
            animation: 4s explode ease-out .7s infinite;
  }

  g.circle2 {
    -webkit-animation: 4s explode ease-out 1s infinite;
            animation: 4s explode ease-out 1s infinite;
  }

  @-webkit-keyframes explode {
    20% {
      opacity: 1;
    }
    30% {
      -webkit-transform: scale(1);
              transform: scale(1);
      opacity: 0;
    }
  }

  @keyframes explode {
    20% {
      opacity: 1;
    }
    30% {
      -webkit-transform: scale(1);
              transform: scale(1);
      opacity: 0;
    }
  }
  .firework1 g.circle1 {
    -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s;
  }
  .firework1 g.circle2 {
    -webkit-animation-delay: 0.6s;
            animation-delay: 0.6s;
  }

  .firework2 g.circle1 {
    -webkit-animation-delay: 0.4s;
            animation-delay: 0.4s;
  }
  .firework2 g.circle2 {
    -webkit-animation-delay: 0.8s;
            animation-delay: 0.8s;
  }

  .firework3 g.circle1 {
    -webkit-animation-delay: 0.6s;
            animation-delay: 0.6s;
  }
  .firework3 g.circle2 {
    -webkit-animation-delay: 1s;
            animation-delay: 1s;
  }

  .firework4 g.circle1 {
    -webkit-animation-delay: 1.2s;
            animation-delay: 1.2s;
  }
  .firework4 g.circle2 {
    -webkit-animation-delay: 1.6s;
            animation-delay: 1.6s;
  }

  .firework5 g.circle1 {
    -webkit-animation-delay: 1.4s;
            animation-delay: 1.4s;
  }
  .firework5 g.circle2 {
    -webkit-animation-delay: 1.8s;
            animation-delay: 1.8s;
  }

  /* Foreground styles */
  h1 {
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: .03em;
  }

  h2 {
    font-weight: 400;
    font-size: 1em;
  }

  .about {
    display: block;
    max-width: 8em;
    float: right;
    padding: 1em;
    text-transform: uppercase;
    text-decoration: none;
    letter-spacing: .06em;
    cursor: pointer;
    text-align: center;
    font-size: .9em;
    background-color: #33244f;
    color: #64A9EA;
    border-radius: .5em;
    margin: 1em;
  }

  .wrapper--header {
    clear: both;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    max-width: 52em;
    margin: 0 auto;
    padding: 1em;
    z-index: -10000000;
    text-align: center;
  }
  .wrapper--header h1 {
    color: #E7F4FA;
    text-shadow: -0.1em 0.1em 0.5em #3A2959;
  }
  .wrapper--header h2 {
    color: #2f2148;
  }

  .wrapper--main {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    -webkit-box-pack: justify;
        -ms-flex-pack: justify;
            justify-content: space-between;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    padding: 1em;
  }

  .card-wrapper,
  .card-face,
  .card-under {
    width: 10em;
    height: 10em;
  }

  .card-wrapper {
    position: relative;
    cursor: pointer;
    display: block;
  }

  .card {
    z-index: 100;
  }

  .card-face {
    position: absolute;
    overflow: hidden;
    border-radius: .5em;
    z-index: 200;
  }

  .front {
    background: -webkit-linear-gradient(45deg, #3A2959, #64A9EA);
    background: linear-gradient(45deg, #3A2959, #64A9EA);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
  }
  .front h1 {
    font-size: 3em;
    color: #3A2959;
  }

  .back {
    background-color: #2f2148;
  }

  .card-under {
    position: absolute;
    top: 0;
    left: 0;
    background-color: #3A2959;
    z-index: 0;
    border-radius: .5em;
    box-shadow: -0.3em 0.3em 0.2em #2f2148 inset;
  }

  .front {
    border-top: 2px solid #64A9EA;
    border-right: 2px solid #64A9EA;
    border-bottom: 2px solid #3A2959;
    border-left: 2px solid #3A2959;
  }

  .card-under {
    border-top: 2px solid #2f2148;
    border-right: 2px solid #2f2148;
    border-bottom: 2px solid #2f2148;
  }

  .card__contents {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999999999;
  }
  .card__contents .contents__wrapper {
    position: relative;
    max-width: 40em;
    margin: 0 auto;
    background-color: #E7F4FA;
    padding: 2em;
    border: 1px solid #bde1f1;
    box-shadow: -0.1em 0.1em 0.5em #3A2959;
  }
  .card__contents p {
    line-height: 1.4;
  }
  .card__contents h3.send-off {
    font-style: italic;
    margin-top: 1.5em;
    margin-bottom: 0;
    font-size: 1.6em;
  }
  .card__contents a {
    color: #64A9EA;
  }
  .card__contents a.article__link {
    padding: .5em;
    background-color: #64A9EA;
    border-radius: .3em;
    color: #E7F4FA;
    text-decoration: none;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }
  .card__contents a.article__link:focus, .card__contents a.article__link:hover, .card__contents a.article__link:active {
    background-color: #3A2959;
    color: #E7F4FA;
  }
  .card__contents a:focus, .card__contents a:hover, .card__contents a:active {
    color: #3A2959;
  }
  .card__contents a.close {
    position: absolute;
    top: 1em;
    right: 1em;
    cursor: pointer;
    padding: .2em;
    width: 2em;
    height: 2em;
    text-align: center;
    border-radius: 50%;
    border: 2px solid #64A9EA;
    font-weight: 700;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }
  .card__contents a.close:focus, .card__contents a.close:hover, .card__contents a.close:active {
    border: 2px solid #3A2959;
  }
  .card__contents #holly {
    position: absolute;
    top: -1em;
    left: -.5em;
    width: 6em;
  }

  .arrow {
    display: block;
    width: 2.5em;
    height: 2.5em;
    cursor: pointer;
    fill: #3A2959;
    opacity: .5;
    -webkit-transition: all .3s ease-in-out;
    transition: all .3s ease-in-out;
  }
  .arrow:focus, .arrow:hover, .arrow:active {
    opacity: 1;
  }

  .text-box {
    position: absolute;
    top: 45vh;
    width: 100%;
    text-align: center;
  }
  .text-box p {
    max-width: 20em;
    margin: 0 auto;
    background-color: orange;
    padding: 1em;
    border-radius: .2em;
    box-shadow: -0.1em 0.1em 0.5em #3A2959;
  }

  /* Tablet styles */
  @media screen and (min-width: 22em) {
    .card-wrapper,
    .card-face,
    .card-under {
      width: 12em;
      height: 12em;
    }
  }
  @media screen and (min-width: 720px) {
    .wrapper--header,
    .wrapper--main {
      padding: 1em 2em;
    }

    h1 {
      font-size: 2.2em;
    }

    h2 {
      font-size: 1.1em;
    }

    .card-wrapper,
    .card-face,
    .card-under {
      width: 16em;
      height: 16em;
    }

    .front h1 {
      font-size: 5em;
    }
  }
  @media screen and (min-width: 1100px) {
    h1 {
      font-size: 2.8em;
      margin-bottom: .5em;
    }

    h2 {
      font-size: 1.2em;
    }
  }
  @media screen and (min-width: 1400px) {
    .card-wrapper,
    .card-face,
    .card-under {
      width: 22em;
      height: 22em;
    }

    .wrapper--header {
      padding: 2em;
    }

    h1 {
      font-size: 3.4em;
      margin-bottom: .5em;
    }

    h2 {
      font-size: 1.4em;
    }
  }

  .snow-flake {
  position: absolute;
  color: white;
  filter: blur(1px);
  top: -50px;
  animation: snow-fall linear infinite;
}

@keyframes snow-fall {
  to {
    transform: translateY(calc(100vh + 55px));
  }
}
  </style>

  <body>
    <div class="foreground">

    <header class="wrapper wrapper--header">
      <h1>Happy Holidays</h1>
      <h2>From Your Number One Tracker Blutopia</h2>
    </header>

    <main class="wrapper--main">

      <div class="main">
      </div>
    </main>
    </div>

    <div class="background">
      <!--ground-->
      <svg class="background__ground" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_2" x="0" y="0" viewBox="0 0 1314 227.7" xml:space="preserve">
      <style type="text/css">

    	.snow{fill:#E7F4FA;}

      </style>
      <g class="snow" id="ground">
        <path d="M0 21.6c141-41.8 293.2-11.1 441.1-5.1 107.2 4.4 214.4-4.5 321.6-9.3C814.4 4.8 866.3 3.4 918 8c46.2 4.1 92 13.1 138.3 13.2 86.5 0.1 175.7-30.4 257.7-2.5v209H0V21.6z"/>
      </g>
    </svg>
      <!--snowman-->
      <svg id="snowman" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0" y="0" viewBox="-1843.6 483.2 462.7 330" xml:space="preserve">
      <style type="text/css">

    	.scarf1{fill:#E7B75C;}
    	.scarf2{fill:#EA385C;}
    	.shadow{fill:#B9C3C6;}
    	.arm{fill:none;stroke:#202123;stroke-width:3;stroke-miterlimit:10;}
    	.st4{fill:#E7F4FA;}
    	.eyes{fill:#202123;}
    	.mouth{fill:none;stroke:#C8C8CC;stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
    	.st7{fill:none;stroke:#202123;stroke-width:4;stroke-miterlimit:10;}
    	.buttons{fill:#808080;}
    	.st9{fill:#332C28;}
    	.st10{fill:#00513E;}
    	.st11{fill:#003828;}
    	.gift1{fill:#386FB1}

      </style>
    		<ellipse id="shadow" class="shadow" transform="matrix(0.9976 -6.985204e-02 6.985204e-02 0.9976 -55.1049 -100.9228)" class="st2" cx="-1470.6" cy="737.5" rx="55.9" ry="11.2"/>
    		<ellipse id="shadow_1_" class="shadow" transform="matrix(6.985503e-02 0.9976 -0.9976 6.985503e-02 -699.3292 1884.1479)"cx="-1355" cy="517.1" rx="11.2" ry="55.9"/>
      <g id="snowman1">
        <g id="scarf-behind">
          <path class="scarf1" d="M-1815.8 609.9l-1.4-4.6h0.1c0.8-0.1 1.3-0.2 1.3-0.2l-1.8-5.7c0 0-0.3 0.1-0.9 0.4 -0.3 0.1-0.7 0.3-1.1 0.4s-0.9 0.3-1.5 0.4c-0.6 0.2-1.2 0.3-1.8 0.4 -0.7 0.1-1.4 0.2-2.1 0.3 -1.5 0.2-3.2 0.3-4.8 0.4 -1.7 0.1-3.4 0.3-5 0.4 -0.8 0.1-1.6 0.2-2.3 0.3 -0.7 0.1-1.4 0.3-2 0.4 -0.6 0.1-1.2 0.3-1.7 0.5 -0.5 0.1-0.9 0.3-1.3 0.4 -0.7 0.3-1.1 0.4-1.1 0.4s0.4-0.1 1.1-0.2c0.4-0.1 0.8-0.1 1.3-0.1s1.1-0.1 1.7-0.1 1.3 0 2 0 1.5 0.1 2.2 0.2c1.5 0.2 3.2 0.4 4.8 0.7 1.7 0.3 3.4 0.5 5 0.7 0.8 0.1 1.6 0.2 2.4 0.2 0.2 0 0.4 0 0.7 0 -0.3 0.1-0.6 0.1-1 0.2 -0.7 0.1-1.4 0.2-2.1 0.3 -1.5 0.2-3.2 0.3-4.8 0.4 -1.7 0.1-3.4 0.3-5 0.4 -0.8 0.1-1.6 0.2-2.3 0.3 -0.7 0.1-1.4 0.3-2 0.4s-1.2 0.3-1.7 0.5c-0.5 0.1-0.9 0.3-1.3 0.4 -0.7 0.3-1.1 0.4-1.1 0.4s0.4-0.1 1.1-0.2c0.4-0.1 0.8-0.1 1.3-0.1s1.1-0.1 1.7-0.1 1.3 0 2 0 1.5 0.1 2.2 0.2c1.5 0.2 3.2 0.4 4.8 0.7 1.7 0.3 3.4 0.5 5 0.7 0.8 0.1 1.6 0.2 2.4 0.2 0.2 0 0.4 0 0.7 0 -0.3 0.1-0.6 0.1-1 0.2 -0.7 0.1-1.4 0.2-2.1 0.3 -1.5 0.2-3.2 0.3-4.8 0.4 -1.7 0.1-3.4 0.3-5 0.4 -0.8 0.1-1.6 0.2-2.3 0.3 -0.7 0.1-1.4 0.3-2 0.4 -0.6 0.1-1.2 0.3-1.7 0.5 -0.5 0.1-0.9 0.3-1.3 0.4 -0.7 0.3-1.1 0.4-1.1 0.4s0.4-0.1 1.1-0.2c0.4-0.1 0.8-0.1 1.3-0.1s1.1-0.1 1.7-0.1 1.3 0 2 0 1.5 0.1 2.2 0.2c1.5 0.2 3.2 0.4 4.8 0.7 1.7 0.3 3.4 0.5 5 0.7 0.8 0.1 1.6 0.2 2.4 0.2 0.2 0 0.4 0 0.7 0 -0.3 0.1-0.6 0.1-1 0.2 -0.7 0.1-1.4 0.2-2.1 0.3 -1.5 0.2-3.2 0.3-4.8 0.4 -1.7 0.1-3.4 0.3-5 0.4 -0.8 0.1-1.6 0.2-2.3 0.3 -0.7 0.1-1.4 0.3-2 0.4s-1.2 0.3-1.7 0.5c-0.5 0.1-0.9 0.3-1.3 0.4 -0.7 0.3-1.1 0.4-1.1 0.4s0.4-0.1 1.1-0.2c0.4-0.1 0.8-0.1 1.3-0.1s1.1-0.1 1.7-0.1 1.3 0 2 0 1.5 0.1 2.2 0.2c1.5 0.2 3.2 0.4 4.8 0.7 1.7 0.3 3.4 0.5 5 0.7 0.8 0.1 1.6 0.2 2.4 0.2 0.2 0 0.4 0 0.7 0 -0.3 0.1-0.6 0.1-1 0.2 -0.7 0.1-1.4 0.2-2.1 0.3 -1.5 0.2-3.2 0.3-4.8 0.4 -1.7 0.1-3.4 0.3-5 0.4 -0.8 0.1-1.6 0.2-2.3 0.3 -0.7 0.1-1.4 0.3-2 0.4 -0.6 0.1-1.2 0.3-1.7 0.5 -0.5 0.1-0.9 0.3-1.3 0.4 -0.7 0.3-1.1 0.4-1.1 0.4s0.4-0.1 1.1-0.2c0.4-0.1 0.8-0.1 1.3-0.1s1.1-0.1 1.7-0.1 1.3 0 2 0 1.5 0.1 2.2 0.2c1.5 0.2 3.2 0.4 4.8 0.7 1.7 0.3 3.4 0.5 5 0.7 0.8 0.1 1.6 0.2 2.4 0.2s1.5 0 2.2 0 1.3-0.1 1.9-0.1 1.1-0.1 1.5-0.2c0.8-0.1 1.3-0.2 1.3-0.2l-1.4-4.6h0.1c0.8-0.1 1.3-0.2 1.3-0.2l-1.4-4.6h0.1c0.8-0.1 1.3-0.2 1.3-0.2l-1.4-4.6h0.1C-1816.2 610-1815.8 609.9-1815.8 609.9z"/>
          <path class="scarf2" d="M-1716.1 626.1c-33.1 15.7-66.1-14.4-99.2-1.2 -1.3 0.5-2.8-0.4-2.8-1.8v-22.6c0-0.8 0.5-1.5 1.2-1.8 33.6-14.9 67.2 16.5 100.8 0.5C-1716.1 608.1-1716.1 617.1-1716.1 626.1z"/>
          <path class="scarf1" d="M-1802.1 594.8c0 9 0 17.9 0 26.9 4.7-0.5 9.3-0.3 14 0.3 0-9 0-17.9 0-26.9C-1792.7 594.5-1797.4 594.3-1802.1 594.8z"/>
          <path class="scarf1" d="M-1769.8 598.6c0 9 0 17.9 0 26.9 4.7 1.1 9.3 2.2 14 3.2 0-9 0-17.9 0-26.9C-1760.4 600.8-1765.1 599.6-1769.8 598.6z"/>
          <path class="scarf1" d="M-1737.4 603.8c0 9 0 17.9 0 26.9 4.7 0 9.3-0.6 14-1.9 0-9 0-17.9 0-26.9C-1728.1 603.3-1732.8 603.8-1737.4 603.8z"/>
        </g>
        <g id="snowman">
          <ellipse class="shadow" id="shadow" transform="matrix(0.9976 -6.985204e-02 6.985204e-02 0.9976 -56.5575 -117.4122)" cx="-1707.1" cy="750" rx="68.9" ry="13.8"/>
          <g id="arm-r" class="arm">
            <polyline points="-1663 636.7 -1637.5 611.7 -1619.9 608.7 -1616.5 597.6 "/>
            <line x1="-1638" y1="612.2" x2="-1638" y2="597.2"/>
            <line x1="-1627.6" y1="610" x2="-1616.5" y2="622.2"/>
          </g>
          <ellipse id="body" class="snow" transform="matrix(0.9955 -9.426693e-02 9.426693e-02 0.9955 -71.8153 -156.804)" class="st4" cx="-1695.6" cy="681.7" rx="65" ry="71.5"/>
          <circle id="head" class="snow" cx="-1706.5" cy="586.2" r="41"/>
           <g id="eyes">
            <ellipse transform="matrix(0.9437 -0.3309 0.3309 0.9437 -289.4088 -533.6516)" class="eyes" cx="-1712.1" cy="583.2" rx="4.9" ry="6.5"/>
            <ellipse transform="matrix(0.9437 -0.3309 0.3309 0.9437 -285.5079 -522.7391)" class="eyes" cx="-1678.1" cy="577.2" rx="4.9" ry="6.5"/>
          </g>
          </g>
          <ellipse id="nose" transform="matrix(0.9598 -0.2807 0.2807 0.9598 -233.9379 -450.1066)" class="scarf1" cx="-1688.3" cy="591.6" rx="7.1" ry="5.4"/>
          <path id="mouth" class="mouth" d="M-1696.7 607c0 0 10.1 4.4 16.8-3.7"/>
          <g id="arm-l" class="arm">
            <polyline points="-1732 648.7 -1771 630.7 -1776.5 610.3 -1785.5 605.7 "/>
            <line x1="-1773.3" y1="622" x2="-1792.5" y2="627.2"/>
            <line x1="-1760.9" y1="635.4" x2="-1773.3" y2="641.2"/>
          </g>
          <g id="buttons" class="buttons">
            <ellipse transform="matrix(0.9821 0.1884 -0.1884 0.9821 90.7004 328.5859)" cx="-1683.1" cy="641.4" rx="5.1" ry="4.8"/>
            <ellipse transform="matrix(0.9378 -0.3472 0.3472 0.9378 -335.7439 -541.206)" cx="-1678.2" cy="666.3" rx="5.1" ry="4.8"/>
          </g>
    		<g id="scarf-front">
          <path class="scarf2" d="M-1655.2 626c-26.3 16.6-60.1 20.5-91.3 8.8 -2.4-0.9-3.9-3-3.4-4.6 2.1-6.6 4.4-13.2 7-19.6 0.6-1.5 2.6-2.1 4.4-1.4 23.5 9.4 48.8 6.6 68.2-5.2 1.5-0.9 3.4-0.6 4.4 0.8 4 5.7 8.1 11.2 12.5 16.6C-1652.4 622.5-1653.1 624.7-1655.2 626z"/>
          <path class="scarf1" d="M-1715.7 641.3c-0.2-9-0.1-17.9 0.4-26.8 -4.1-0.3-8.1-0.9-12.2-1.9 -1.8 8.7-3.3 17.5-4.4 26.5C-1726.5 640.3-1721.1 641-1715.7 641.3z"/>
          <path class="scarf1" d="M-1678.5 636.8c-3.4-8.3-6.4-16.7-9-25.2 -3.7 1.1-7.6 2-11.5 2.5 1.3 8.8 3 17.6 5.1 26.3C-1688.7 639.5-1683.5 638.3-1678.5 636.8z"/>
          <path class="scarf1" d="M-1655.2 626c2-1.3 2.8-3.4 1.7-4.7 -4.4-5.4-8.5-10.9-12.5-16.6 -1-1.4-2.9-1.7-4.4-0.8 -0.9 0.5-1.8 1.1-2.7 1.5 4.4 7.8 9.1 15.4 14.1 22.8C-1657.7 627.5-1656.4 626.8-1655.2 626z"/>
        </g>
        </g>
      </g>
      <g id="snowman2">
        <g id="arm-r_2_" class="arm">
          <polyline points="-1492.8 646.5 -1506.6 626.3 -1515.6 623.8 -1525.2 615.5 "/>
          <line x1="-1506.2" y1="627.1" x2="-1507" y2="614"/>
          <line x1="-1500.6" y1="635.2" x2="-1524.1" y2="632"/>
        </g>
        <ellipse id="body_2_" transform="matrix(0.9998 -2.089692e-02 2.089692e-02 0.9998 -14.4823 -30.3753)" class="snow" cx="-1460.7" cy="677.8" rx="59.6" ry="60.5"/>
        <circle id="head_2_" class="snow" cx="-1449.8" cy="600.9" r="34.7"/>
        <g id="eyes_2_" class="eyes">
          <ellipse transform="matrix(0.9636 0.2672 -0.2672 0.9636 107.3009 407.9326)" cx="-1445.2" cy="598.2" rx="4.1" ry="5.5"/>
          <ellipse transform="matrix(0.9636 0.2672 -0.2672 0.9636 105.3762 415.5825)" cx="-1474.3" cy="595" rx="4.1" ry="5.5"/>
        </g>
        <g id="arm-l_2_" class="arm">
          <polyline points="-1425.2 645.3 -1403.6 630.3 -1389.4 617.2 -1382.6 607.3 "/>
          <line x1="-1395.8" y1="623.6" x2="-1386.3" y2="627.5"/>
          <line x1="-1403.6" y1="630.3" x2="-1399.1" y2="607.1"/>
        </g>
        <path id="mouth_1_" class="mouth" d="M-1468.6 615.5c4.3 6.5 10.8 1.3 10.8 1.3"/>
        <path id="nose_1_" class="scarf1" d="M-1458.7 600.5l-17.2-0.3c-1.7 0-2.3 2.4-0.7 3.1l14.9 7C-1461.5 610.4-1453.3 608.6-1458.7 600.5z"/>
    		<path class="scarf2" d="M-1413.1 600.9l-60.9-28.8c16.2-29.7 66.1-35.1 84.4-2.3C-1389.6 569.8-1412.7 577.1-1413.1 600.9z"/>
      <circle class="scarf1" cx="-1390.4" cy="570.8" r="9.5"/>
      <path class="scarf1" d="M-1417.8 603.1c-21.9-5.6-42.1-13.8-60.4-24.1 -2.6-1.4-3.3-4.5-1.9-6.9 1-1.6 1.9-3.1 2.9-4.7 1.4-2.4 4.5-3.2 6.9-1.8 17.4 9.6 36.7 17.4 57.7 22.5 2.9 0.7 4.6 3.4 3.8 6.1 -0.6 1.8-1.2 3.6-1.8 5.3C-1411.4 602.3-1414.7 603.9-1417.8 603.1z"/>
      </g>
      <g id="tree">
        <rect x="-1605.6" y="697.1" class="st9" width="21.7" height="71.3"/>
        <polygon class="st10" points="-1656.1 616.8 -1634.8 612 -1670.6 676.1 -1648.5 671.1 -1694.2 753 -1595 730.5 -1595 507.4 "/>
        <polygon class="st11" points="-1494.9 753 -1540.6 671.1 -1518.5 676.1 -1554.4 612 -1533.1 616.8 -1594.7 506.8 -1595 507.4 -1595 730.5 -1594.7 730.4 "/>
      </g>
      <g id="baubles">
        <g id="blue-lt">
          <circle class="blue-lt baubles-g1" cx="-1575" cy="706.1" r="9"/>
          <circle class="blue-lt baubles-g2" cx="-1621.3" cy="641" r="7"/>
          <circle class="blue-lt" cx="-1665.5" cy="732.8" r="7"/>
          <circle class="blue-lt baubles-g2" cx="-1600.3" cy="668.5" r="7"/>
        </g>
        <g id="blue-dk">
          <circle class="blue-dk baubles-g1" cx="-1576.3" cy="570.8" r="7"/>
          <circle class="blue-dk" cx="-1538" cy="718.6" r="7"/>
          <circle class="blue-dk baubles-g2" cx="-1594.8" cy="610.3" r="7"/>
        </g>
        <g id="red">
          <circle class="red baubles-g1" cx="-1635.6" cy="681.7" r="9"/>
          <circle class="red" cx="-1570.3" cy="634" r="9"/>
          <circle class="red baubles-g2" cx="-1607.3" cy="711.6" r="7"/>
        </g>
        <g id="gold-lt">
          <circle class="yellow baubles-g1" cx="-1612.3" cy="585.8" r="9"/>
          <circle class="yellow" cx="-1631.6" cy="705.6" r="7"/>
        </g>
        <g id="gold-dk">
          <circle class="gold-dk" cx="-1572.3" cy="604.7" r="7"/>
          <circle class="gold-dk baubles-g2" cx="-1561.3" cy="681.7" r="7"/>
        </g>
      </g>
    		<g id="gift">
        <path class="gift1" d="M-1496.5 783l-35 9.1c-0.3 0.1-0.7 0.1-1 0l-35-9.1c-0.9-0.2-1.5-1-1.5-1.9v-51.7c0-1 0.7-1.8 1.7-2l35-4.7c0.2 0 0.4 0 0.5 0l35 4.7c1 0.1 1.7 1 1.7 2V781C-1495.1 782-1495.7 782.7-1496.5 783z"/>
        <path class="scarf1" d="M-1510.6 711.6c-9.8 0.4-15.8 10.4-17.9 13.4 -1.5-1.9-3.2-0.5-3.2-0.5 -1-5.2-16-19-24.7-11.6 -8.7 7.4-3 16-1 17.2 0.1 0.1 0.3 0.2 0.4 0.2 -0.3 0.2-0.4 0.5-0.4 0.8v54.6l10 2.6v-55.8l0 0c5.2 0.3 10.7-0.6 14-2.8l0.4 0.1 0 0c1.4 2 3.6 1.5 4.8 1l11.2 2.4c0.5 0.1 0.8 0.5 0.8 1v53.9l10.2-2.6v-53.3c0-0.3-0.2-0.7-0.5-0.8C-1499.5 727.4-1501.8 711.2-1510.6 711.6zM-1506.7 721.7c8.1 2.2-4.5 5.8-13.3 5.3l8.9-1.5 -8.3-1.1 -5.1 0.8C-1522 723.5-1514.9 719.5-1506.7 721.7zM-1557.5 724.5c0-1.6 2.2-3.6 7.3-3.5 5.8 0.1 17.5 3.1 16 3.4 -1.5 0.4-2.9 0.6-4.2 0.9l-5.5-1 -9.1 1.2 4.7 1C-1554.6 726.7-1557.5 725.5-1557.5 724.5z"/>
      </g>

    	<g id="star">
    		<polygon class="star-left" points="-1600.5 499.9 -1618.1 499.9 -1603.8 510.3 -1609.3 527 -1595 516.7 -1595 483.2 "/>
    		<polygon class="star-right" points="-1572 499.9 -1589.6 499.9 -1595 483.2 -1595 516.7 -1580.8 527 -1586.2 510.3 "/>
    	</g>

    		<g id="gift">
        <path class="gift1" d="M-1496.5 783l-35 9.1c-0.3 0.1-0.7 0.1-1 0l-35-9.1c-0.9-0.2-1.5-1-1.5-1.9v-51.7c0-1 0.7-1.8 1.7-2l35-4.7c0.2 0 0.4 0 0.5 0l35 4.7c1 0.1 1.7 1 1.7 2V781C-1495.1 782-1495.7 782.7-1496.5 783z"/>
        <path class="scarf1" d="M-1510.6 711.6c-9.8 0.4-15.8 10.4-17.9 13.4 -1.5-1.9-3.2-0.5-3.2-0.5 -1-5.2-16-19-24.7-11.6 -8.7 7.4-3 16-1 17.2 0.1 0.1 0.3 0.2 0.4 0.2 -0.3 0.2-0.4 0.5-0.4 0.8v54.6l10 2.6v-55.8l0 0c5.2 0.3 10.7-0.6 14-2.8l0.4 0.1 0 0c1.4 2 3.6 1.5 4.8 1l11.2 2.4c0.5 0.1 0.8 0.5 0.8 1v53.9l10.2-2.6v-53.3c0-0.3-0.2-0.7-0.5-0.8C-1499.5 727.4-1501.8 711.2-1510.6 711.6zM-1506.7 721.7c8.1 2.2-4.5 5.8-13.3 5.3l8.9-1.5 -8.3-1.1 -5.1 0.8C-1522 723.5-1514.9 719.5-1506.7 721.7zM-1557.5 724.5c0-1.6 2.2-3.6 7.3-3.5 5.8 0.1 17.5 3.1 16 3.4 -1.5 0.4-2.9 0.6-4.2 0.9l-5.5-1 -9.1 1.2 4.7 1C-1554.6 726.7-1557.5 725.5-1557.5 724.5z"/>
      </g>
    	<g id="gift2" transform="matrix(1,0,0,1,-60,25)">
        <path class="scarf1" d="M-1496.5 773L-1531.5 782.1C-1531.8 782.2-1532.2 782.2-1532.5 782.1L-1567.5 773C-1568.4 772.8-1569 772-1569 771.1L-1569 729.4C-1569 728.4-1568.3 727.6-1567.3 727.4L-1532.3 722.7C-1532.1 722.7-1531.9 722.7-1531.8 722.7L-1496.8 727.4C-1495.8 727.5-1495.1 728.4-1495.1 729.4L-1495.1 771C-1495.1 772-1495.7 772.7-1496.5 773Z" style="fill-rule:nonzero;fill:rgb(233,56,92)"/>
        <path class="gift1" d="M-1510.6 711.6C-1520.4 712-1526.4 722-1528.5 725 -1530 723.1-1531.7 724.5-1531.7 724.5 -1532.7 719.3-1547.7 705.5-1556.4 712.9 -1565.1 720.3-1559.4 728.9-1557.4 730.1 -1557.3 730.2-1557.1 730.3-1557 730.3 -1557.3 730.5-1557.4 730.8-1557.4 731.1L-1557.4 775.7 -1547.4 778.3 -1547.4 732.5C-1542.2 732.8-1536.7 731.9-1533.4 729.7L-1533 729.8C-1531.6 731.8-1529.4 731.3-1528.2 730.8L-1517 733.2C-1516.5 733.3-1516.2 733.7-1516.2 734.2L-1516.2 778.1 -1506 775.5 -1506 732.2C-1506 731.9-1506.2 731.5-1506.5 731.4 -1499.5 727.4-1501.8 711.2-1510.6 711.6ZM-1506.7 721.7C-1498.6 723.9-1511.2 727.5-1520 727L-1511.1 725.5 -1519.4 724.4 -1524.5 725.2C-1522 723.5-1514.9 719.5-1506.7 721.7ZM-1557.5 724.5C-1557.5 722.9-1555.3 720.9-1550.2 721 -1544.4 721.1-1532.7 724.1-1534.2 724.4 -1535.7 724.8-1537.1 725-1538.4 725.3L-1543.9 724.3 -1553 725.5 -1548.3 726.5C-1554.6 726.7-1557.5 725.5-1557.5 724.5Z" style="fill-rule:nonzero;fill:rgb(40,82,124)"/>
    	</g>
    </svg>

    <!--cat-->
    <svg id="cat" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0" y="0" viewBox="0 -20 225.3 157.9" xml:space="preserve" enable-background="new 0 0 225.3 127.9">
      <style type="text/css">

    	.red-line{fill:none;stroke:#E9385C;stroke-width:6;stroke-miterlimit:10;}
    	.st2{fill:#C8C8CC;}
    	.st3{fill:none;stroke:#4D4D4D;stroke-miterlimit:10;}
    	.tail{fill:none;stroke:#202123;stroke-width:9;stroke-linecap:round;stroke-miterlimit:10;}
    	.st5{fill:#202123;}
    	.st6{fill:#4D4D4D;}
    	.st7{fill:#E7B75C; stroke: none;}
    	.pattern{fill:url(#SVGID_2_);}

      </style>
      <pattern x="-0.1" y="127.9" width="59" height="66" patternUnits="userSpaceOnUse" id="SVGID_1_" viewBox="0 -66 59 66" overflow="visible" fill="#E7B75C">
        <rect fill="#E7B75C" y="-66" class="st0" width="59" height="66"/>
        <polygon points="0 0 59 0 59 -66 0 -66 "/>
        <line class="red-line" x1="6" y1="0" x2="6" y2="-66"/>
        <line class="red-line" x1="18" y1="0" x2="18" y2="-66"/>
        <line class="red-line" x1="29" y1="0" x2="29" y2="-66"/>
        <line class="red-line" x1="41" y1="0" x2="41" y2="-66"/>
        <line class="red-line" x1="53" y1="0" x2="53" y2="-66"/>
      </pattern>
      <g id="cat-prints" class="shadow">
        <ellipse cx="102.9" cy="124.9" rx="6.9" ry="2"/>
        <ellipse cx="118.9" cy="124.9" rx="6.9" ry="2"/>
        <ellipse cx="156.9" cy="124.9" rx="6.9" ry="2"/>
        <ellipse cx="174.9" cy="125.9" rx="6.9" ry="2"/>
        <ellipse cx="74.9" cy="124.9" rx="6.9" ry="2"/>
        <ellipse cx="51.9" cy="120.9" rx="6.9" ry="2"/>
        <ellipse cx="27.9" cy="124.9" rx="6.9" ry="2"/>
        <ellipse cx="6.9" cy="120.9" rx="6.9" ry="2"/>
      </g>
      <g id="whiskers-back">
        <line class="st3" x1="208.3" y1="37.5" x2="198.5" y2="40.5"/>
        <line class="st3" x1="206.9" y1="33.8" x2="197.8" y2="38.5"/>
        <line class="st3" x1="209.8" y1="41.2" x2="199.4" y2="42.5"/>
      </g>
      <path id="tail" class="tail" d="M64.8 4.5c11.3 2.2 15 11.1 13.5 29.2s20.4 26.6 20.4 26.6"/>
      <path id="cat-body" class="st5" d="M98.6 90.9v34.6c1 0.2 3 0.3 4.6 0.2 1.9-0.1 3.4-0.6 5.4-0.7V99.3c1.6 1 3.3 1.9 5 2.7v23.7c1 0.1 1.5 0.3 2.1 0.4 2.9 0.5 5.9-0.2 8.9-0.9v-19.5c8.6 1.9 18 2.2 27 1.3v18.1c2 0.1 3 1.2 4.5 1.1 2.1-0.1 4.5-0.5 6.5-0.6v-20.3c2.4-0.5 4.8-1.1 7-1.8V126c4 0.4 7 1.2 11-0.2V98.3c14-11.1 9.5-35.2 7.7-41.7l0 0c8-3.4 13.6-11.3 13.6-20.5 0-5.8-2.2-11-5.8-15l-1.2-13.5c0-0.8-1-1.2-1.6-0.7l-8.7 7.4c-1.6-0.3-3.2-0.5-4.9-0.5 -6.7 0-12.7 3-16.8 7.7l-10.2-0.6c-0.8 0-1.3 0.8-0.9 1.5l6 10.2c-0.2 1.1-0.3 2.3-0.3 3.4 0 4.6 1.4 8.9 3.8 12.4 -4.3 4.6-7.6 10.9-26.9 3 -21.6-8.8-35.2-3.6-40.9 6.9C88 68.4 88.5 79.6 98.6 90.9z"/>
      <path id="cat-nose" class="st6" d="M196.8 39.7l-1.1 3.1c-0.2 0.7-1.1 1.1-1.8 0.8l-3-1.3c-1.2-0.5-1.3-2.3-0.1-2.8l4.1-1.8C196 37.1 197.2 38.4 196.8 39.7z"/>
      <g id="cat-eyes_1_">
    		<g id="eye1">
        <path class="st7" d="M195 28.3c2.9-0.6 5.9 1.8 5.9 1.8s-1.7 3.4-4.7 4.1c-2.9 0.6-5.9-1.8-5.9-1.8S192 29 195 28.3z"/>
        <circle class="st5" cx="195.6" cy="31.3" r="2.1"/>
    		</g>
    		<g id="eye2">
        <path class="st7" d="M176 33.6c3.2-0.7 6.6 2 6.6 2s-1.9 3.9-5.2 4.6c-3.2 0.7-6.6-2-6.6-2S172.7 34.3 176 33.6z"/>
        <circle class="st5" cx="176.7" cy="36.9" r="2.3"/>
    		</g>
      </g>
      <g id="whiskers-front">
        <line class="st3" x1="173.4" y1="50.8" x2="182.3" y2="46"/>
        <line class="st3" x1="171.6" y1="47.2" x2="181.4" y2="44.1"/>
        <line class="st3" x1="174.9" y1="54.6" x2="183.1" y2="48"/>
      </g>
      <path id="ear-inner" class="st6" d="M163.9 25.2h-4.2c-0.8 0-1.3 0.9-0.8 1.6l3.5 4.6C160.8 26.9 163.9 25.3 163.9 25.2z"/>
      <g id="cracker">
        <rect x="170" y="49.4" transform="matrix(0.9789 -0.2043 0.2043 0.9789 -7.416 40.0652)" class="st7" width="40.7" height="13.1"/>
        <polygon class="st7" points="172.6 59.4 164.1 54.6 155.3 56.5 157.4 59.3 156.6 62.9 158.7 65.7 158 69.2 166.7 67.4 "/>
        <polygon class="st7" points="208 52 213.9 44.2 222.7 42.4 221.9 45.9 224 48.8 223.2 52.2 225.3 55.1 216.6 57 "/>
        <pattern id="SVGID_2_" xlink:href="#SVGID_1_" patternTransform="matrix(0.3902 -0.3127 -0.3127 -0.3902 -5895.5879 -10519.8652)"/>
        <polygon class="pattern" points="211.6 58.2 171.8 66.5 169.1 53.7 208.9 45.4 "/>
      </g>
      <path id="mouth-front" class="st5" d="M199 46c-0.3-1.1-2-1.7-3.7-1.2 -1.1 0.3-1.9 0.9-2.3 1.6 -0.7-0.5-1.8-0.7-3-0.5 -1.8 0.4-3 1.7-2.7 2.9 0.3 1.2 1.9 1.9 3.7 1.5 1.1-0.3 2-0.9 2.5-1.6 0.7 0.5 1.9 0.6 3 0.3C198.1 48.5 199.3 47.2 199 46z"/>
    </svg>

      <!--fireworks-->
      <div class="container--snowflakes">
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Layer_2" x="0" y="0" viewBox="0 0 1243 258" xml:space="preserve">
      <style type="text/css">

    	.st0{fill:none;}
    	.firework-yellow{fill:#E7B75C;}
    	.firework-red{fill:#EA385C;}
    	.firework-white {fill:#F9E6B7;}

      </style>
      <g id="firework5" class="firework5">
        <g id="circle1" class="circle1 firework-red">
          <rect x="123" y="82.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="126.8" cy="86.2" r="3.9"/>
          <rect x="116.3" y="67.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="120.2" cy="71" r="3.9"/>
          <rect x="105.6" y="54.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="109.5" cy="58.1" r="3.9"/>
          <rect x="91.5" y="44.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="95.4" cy="48.6" r="3.9"/>
          <rect x="75" y="41.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="78.8" cy="45.1" r="3.9"/>
          <rect x="58.3" y="42.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="62.2" cy="46.2" r="3.9"/>
          <rect x="42.6" y="47.6" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="46.4" cy="51.5" r="3.9"/>
          <rect x="28.7" y="57.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="32.6" cy="61.2" r="3.9"/>
          <rect x="19.2" y="71.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="23.1" cy="75.2" r="3.9"/>
          <rect x="14.4" y="87.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="18.3" cy="91.3" r="3.9"/>
          <rect x="14" y="104" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="17.8" cy="107.9" r="3.9"/>
          <rect x="18" y="120.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="21.8" cy="124.2" r="3.9"/>
          <rect x="27.4" y="134.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="31.3" cy="138.3" r="3.9"/>
          <rect x="40.7" y="144.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="44.6" cy="148.6" r="3.9"/>
          <rect x="56.2" y="150.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="60.1" cy="154.7" r="3.9"/>
          <rect x="72.8" y="152.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="76.7" cy="156.6" r="3.9"/>
          <rect x="89.4" y="149.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="93.3" cy="153.3" r="3.9"/>
          <rect x="103.8" y="140.6" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="107.6" cy="144.5" r="3.9"/>
          <rect x="115" y="128.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="118.9" cy="132.2" r="3.9"/>
          <rect x="122.5" y="113.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="126.4" cy="117.3" r="3.9"/>
          <rect x="125.9" y="96.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="129.8" cy="100.7" r="3.9"/>
        </g>
        <g id="circle2" class="circle2 firework-yellow">
          <rect x="89.4" y="83" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="93.3" cy="86.9" r="3.9"/>
          <rect x="77.2" y="74.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="81.1" cy="78" r="3.9"/>
          <rect x="62.2" y="73.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="66.1" cy="77.7" r="3.9"/>
          <rect x="49.8" y="82.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="53.6" cy="86.1" r="3.9"/>
          <rect x="44.4" y="96.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="48.3" cy="100.3" r="3.9"/>
          <rect x="49.2" y="110.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="53.1" cy="114.7" r="3.9"/>
          <rect x="61.5" y="119.6" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="65.4" cy="123.4" r="3.9"/>
          <rect x="76.5" y="119.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="80.4" cy="123.5" r="3.9"/>
          <rect x="88.9" y="111.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="92.7" cy="115" r="3.9"/>
          <rect x="94" y="96.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="97.9" cy="100.7" r="3.9"/>
        </g>
      </g>
      <g id="firework4" class="firework4">
        <g id="circle1_1_" class="circle1 firework-yellow">
          <rect x="1160.9" y="54" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1164.8" cy="57.9" r="3.9"/>
          <rect x="1154.2" y="38.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1158.1" cy="42.6" r="3.9"/>
          <rect x="1143.5" y="25.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1147.4" cy="29.8" r="3.9"/>
          <rect x="1129.5" y="16.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1133.4" cy="20.3" r="3.9"/>
          <rect x="1112.9" y="12.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1116.8" cy="16.8" r="3.9"/>
          <rect x="1096.3" y="13.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1100.2" cy="17.8" r="3.9"/>
          <rect x="1080.5" y="19.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1084.4" cy="23.2" r="3.9"/>
          <rect x="1066.6" y="29" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1070.5" cy="32.8" r="3.9"/>
          <rect x="1057.1" y="43" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1061" cy="46.9" r="3.9"/>
          <rect x="1052.3" y="59" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1056.2" cy="62.9" r="3.9"/>
          <rect x="1051.9" y="75.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1055.8" cy="79.5" r="3.9"/>
          <rect x="1055.9" y="92" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1059.8" cy="95.9" r="3.9"/>
          <rect x="1065.4" y="106.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1069.3" cy="109.9" r="3.9"/>
          <rect x="1078.6" y="116.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1082.5" cy="120.2" r="3.9"/>
          <rect x="1094.1" y="122.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1098" cy="126.4" r="3.9"/>
          <rect x="1110.7" y="124.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1114.6" cy="128.3" r="3.9"/>
          <rect x="1127.4" y="121" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1131.2" cy="124.9" r="3.9"/>
          <rect x="1141.7" y="112.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1145.6" cy="116.1" r="3.9"/>
          <rect x="1153" y="100" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1156.8" cy="103.8" r="3.9"/>
          <rect x="1160.4" y="85.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1164.3" cy="88.9" r="3.9"/>
          <rect x="1163.8" y="68.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1167.7" cy="72.3" r="3.9"/>
        </g>
        <g id="circle2_1_" class="circle2 firework-red">
          <rect x="1127.3" y="54.6" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1131.2" cy="58.5" r="3.9"/>
          <rect x="1115.1" y="45.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1119" cy="49.6" r="3.9"/>
          <rect x="1100.2" y="45.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1104" cy="49.3" r="3.9"/>
          <rect x="1087.7" y="53.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1091.6" cy="57.7" r="3.9"/>
          <rect x="1082.3" y="68.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1086.2" cy="71.9" r="3.9"/>
          <rect x="1087.2" y="82.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1091" cy="86.4" r="3.9"/>
          <rect x="1099.4" y="91.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1103.3" cy="95.1" r="3.9"/>
          <rect x="1114.4" y="91.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1118.3" cy="95.2" r="3.9"/>
          <rect x="1126.8" y="82.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1130.7" cy="86.6" r="3.9"/>
          <rect x="1132" y="68.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1135.9" cy="72.3" r="3.9"/>
        </g>
      </g>
      <g id="firework3" class="firework3">
        <g id="circle1_3_" class="circle1 firework-white">
          <rect x="894.8" y="167.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="898.7" cy="171.2" r="3.9"/>
          <rect x="888.1" y="152.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="892" cy="156" r="3.9"/>
          <rect x="877.5" y="139.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="881.3" cy="143.1" r="3.9"/>
          <rect x="863.4" y="129.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="867.3" cy="133.7" r="3.9"/>
          <rect x="846.8" y="126.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="850.7" cy="130.2" r="3.9"/>
          <rect x="830.2" y="127.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="834.1" cy="131.2" r="3.9"/>
          <rect x="814.4" y="132.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="818.3" cy="136.5" r="3.9"/>
          <rect x="800.5" y="142.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="804.4" cy="146.2" r="3.9"/>
          <rect x="791.1" y="156.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="794.9" cy="160.3" r="3.9"/>
          <rect x="786.2" y="172.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="790.1" cy="176.3" r="3.9"/>
          <rect x="785.8" y="189" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="789.7" cy="192.9" r="3.9"/>
          <rect x="789.8" y="205.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="793.7" cy="209.3" r="3.9"/>
          <rect x="799.3" y="219.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="803.2" cy="223.3" r="3.9"/>
          <rect x="812.6" y="229.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="816.5" cy="233.6" r="3.9"/>
          <rect x="828" y="235.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="831.9" cy="239.7" r="3.9"/>
          <rect x="844.7" y="237.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="848.5" cy="241.6" r="3.9"/>
          <rect x="861.3" y="234.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="865.2" cy="238.3" r="3.9"/>
          <rect x="875.6" y="225.6" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="879.5" cy="229.5" r="3.9"/>
          <rect x="886.9" y="213.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="890.8" cy="217.2" r="3.9"/>
          <rect x="894.4" y="198.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="898.3" cy="202.3" r="3.9"/>
          <rect x="897.7" y="181.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="901.6" cy="185.7" r="3.9"/>
        </g>
        <g id="circle2_3_" class="circle1 firework-yellow">
          <rect x="861.3" y="168" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="865.1" cy="171.9" r="3.9"/>
          <rect x="849.1" y="159.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="853" cy="163" r="3.9"/>
          <rect x="834.1" y="158.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="838" cy="162.7" r="3.9"/>
          <rect x="821.6" y="167.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="825.5" cy="171.1" r="3.9"/>
          <rect x="816.3" y="181.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="820.2" cy="185.3" r="3.9"/>
          <rect x="821.1" y="195.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="825" cy="199.8" r="3.9"/>
          <rect x="833.4" y="204.6" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="837.2" cy="208.5" r="3.9"/>
          <rect x="848.3" y="204.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="852.2" cy="208.6" r="3.9"/>
          <rect x="860.7" y="196.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="864.6" cy="200" r="3.9"/>
          <rect x="865.9" y="181.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="869.8" cy="185.7" r="3.9"/>
        </g>
      </g>
      <g id="firework2" class="firework2">
        <g id="circle1_5_" class="circle1 firework-yellow">
          <rect x="204.5" y="139" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="208.4" cy="142.9" r="3.9"/>
          <rect x="197.8" y="123.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="201.7" cy="127.7" r="3.9"/>
          <rect x="187.2" y="110.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="191" cy="114.8" r="3.9"/>
          <rect x="173.1" y="101.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="177" cy="105.3" r="3.9"/>
          <rect x="156.5" y="97.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="160.4" cy="101.8" r="3.9"/>
          <rect x="139.9" y="99" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="143.8" cy="102.8" r="3.9"/>
          <rect x="124.1" y="104.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="128" cy="108.2" r="3.9"/>
          <rect x="110.2" y="114" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="114.1" cy="117.9" r="3.9"/>
          <rect x="100.8" y="128.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="104.6" cy="131.9" r="3.9"/>
          <rect x="95.9" y="144.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="99.8" cy="147.9" r="3.9"/>
          <rect x="95.5" y="160.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="99.4" cy="164.6" r="3.9"/>
          <rect x="99.5" y="177" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="103.4" cy="180.9" r="3.9"/>
          <rect x="109" y="191.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="112.9" cy="195" r="3.9"/>
          <rect x="122.3" y="201.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="126.1" cy="205.3" r="3.9"/>
          <rect x="137.7" y="207.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="141.6" cy="211.4" r="3.9"/>
          <rect x="154.3" y="209.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="158.2" cy="213.3" r="3.9"/>
          <rect x="171" y="206.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="174.8" cy="209.9" r="3.9"/>
          <rect x="185.3" y="197.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="189.2" cy="201.1" r="3.9"/>
          <rect x="196.6" y="185" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="200.5" cy="188.9" r="3.9"/>
          <rect x="204.1" y="170.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="207.9" cy="174" r="3.9"/>
          <rect x="207.4" y="153.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="211.3" cy="157.3" r="3.9"/>
        </g>
        <g id="circle2_5_" class="circle2 firework-white">
          <rect x="170.9" y="139.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="174.8" cy="143.5" r="3.9"/>
          <rect x="158.8" y="130.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="162.6" cy="134.7" r="3.9"/>
          <rect x="143.8" y="130.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="147.7" cy="134.4" r="3.9"/>
          <rect x="131.3" y="138.9" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="135.2" cy="142.7" r="3.9"/>
          <rect x="126" y="153.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="129.8" cy="157" r="3.9"/>
          <rect x="130.8" y="167.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="134.7" cy="171.4" r="3.9"/>
          <rect x="143.1" y="176.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="146.9" cy="180.1" r="3.9"/>
          <rect x="158" y="176.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="161.9" cy="180.2" r="3.9"/>
          <rect x="170.4" y="167.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="174.3" cy="171.7" r="3.9"/>
          <rect x="175.6" y="153.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="179.5" cy="157.3" r="3.9"/>
        </g>
      </g>
      <g id="firework1_4_" class="firework1">
        <g id="circle1_7_" class="circle1 firework-red">
          <rect x="1066.1" y="98.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1070" cy="102.6" r="3.9"/>
          <rect x="1059.4" y="83.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1063.3" cy="87.4" r="3.9"/>
          <rect x="1048.7" y="70.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1052.6" cy="74.5" r="3.9"/>
          <rect x="1034.7" y="61.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1038.6" cy="65.1" r="3.9"/>
          <rect x="1018.1" y="57.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1022" cy="61.6" r="3.9"/>
          <rect x="1001.5" y="58.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1005.4" cy="62.6" r="3.9"/>
          <rect x="985.7" y="64.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="989.6" cy="67.9" r="3.9"/>
          <rect x="971.8" y="73.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="975.7" cy="77.6" r="3.9"/>
          <rect x="962.4" y="87.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="966.2" cy="91.7" r="3.9"/>
          <rect x="957.5" y="103.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="961.4" cy="107.7" r="3.9"/>
          <rect x="957.1" y="120.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="961" cy="124.3" r="3.9"/>
          <rect x="961.1" y="136.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="965" cy="140.7" r="3.9"/>
          <rect x="970.6" y="150.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="974.5" cy="154.7" r="3.9"/>
          <rect x="983.9" y="161.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="987.7" cy="165" r="3.9"/>
          <rect x="999.3" y="167.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1003.2" cy="171.2" r="3.9"/>
          <rect x="1015.9" y="169.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1019.8" cy="173" r="3.9"/>
          <rect x="1032.6" y="165.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1036.4" cy="169.7" r="3.9"/>
          <rect x="1046.9" y="157" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1050.8" cy="160.9" r="3.9"/>
          <rect x="1058.2" y="144.7" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1062.1" cy="148.6" r="3.9"/>
          <rect x="1065.7" y="129.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1069.5" cy="133.7" r="3.9"/>
          <rect x="1069" y="113.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1072.9" cy="117.1" r="3.9"/>
        </g>
        <g id="circle2_7_" class="circle2 firework-yellow">
          <rect x="1032.5" y="99.4" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1036.4" cy="103.3" r="3.9"/>
          <rect x="1020.4" y="90.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1024.2" cy="94.4" r="3.9"/>
          <rect x="1005.4" y="90.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1009.3" cy="94.1" r="3.9"/>
          <rect x="992.9" y="98.6" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="996.8" cy="102.5" r="3.9"/>
          <rect x="987.6" y="112.8" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="991.4" cy="116.7" r="3.9"/>
          <rect x="992.4" y="127.3" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="996.3" cy="131.2" r="3.9"/>
          <rect x="1004.7" y="136" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1008.5" cy="139.9" r="3.9"/>
          <rect x="1019.6" y="136.1" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1023.5" cy="140" r="3.9"/>
          <rect x="1032" y="127.5" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1035.9" cy="131.4" r="3.9"/>
          <rect x="1037.2" y="113.2" class="st0" width="7.8" height="7.8"/>
          <circle class="st1" cx="1041.1" cy="117.1" r="3.9"/>
        </g>
      </g>
    </svg>

    </div>

    <!--robin-->
    <svg id="robin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 287.67 198.61"><defs><style>.cls-1{fill:#634e3f;}.cls-2{fill:#202123;}.cls-3{fill:#42352c;}.cls-4{fill:#ea385c;}</style></defs><title>robin</title>
    	<path id="body" class="cls-1" d="M235.69,107.9c-25.61,3.63-62.29-8.21-73-45.4C153.1,29.34,134.69,0,100.17,0a62.5,62.5,0,0,0-62.5,62.5c0,69,56,133.61,125,136,57.69,2,108.92-22,125-114C287.67,84.5,276.37,102.14,235.69,107.9Z"/>
    	<circle id="eye" class="cls-2" cx="91.46" cy="63.17" r="15.14"/>
    	<polygon id="beak" class="cls-2" points="40.91 90.13 45.08 67.73 39.92 45.34 0 67.73 40.91 90.13"/>
    	<path id="breast" class="cls-4" d="M44.19,103.38c13.14,40.79,45,75.19,84.84,88.86,4.26-17.4-.85-39.91-15.21-59.41C94.52,106.61,65.06,94.72,44.19,103.38Z"/>
    	<path id="wing" class="cls-3" d="M268.46,127.43c-11.47,33-49,50.94-91.8,45.34-23.45-3.07-42.82-19.17-42.82-42.82a42.82,42.82,0,0,1,42.82-42.82c25.75,0,37.58,14.5,57,26.86C255.58,128,268.46,127.43,268.46,127.43Z"/></svg>
    </div>

    <script>
    const rnd = (min, max) => Math.floor(Math.random() * (max - min + 1) + min)


    for (let ndx=0; ndx < 50; ndx++) {
      const flake = document.createElement('span')
      flake.innerText = '*'
      flake.setAttribute('class', 'snow-flake')
      const style = `left:${rnd(-10,window.innerWidth+10)}px; animation-duration: ${rnd(10,25)}s, ${rnd(5,10)}s; animation-delay: ${rnd(0,5)}s; font-size: ${rnd(5,30)}px`
      flake.setAttribute('style', style)
      document.body.appendChild(flake)
    }
    </script>
  </body>
</html>
