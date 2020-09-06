<?PHP
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html amp>
    <head>
        <?=Yii::$app->params['header.tags']?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <style amp-boilerplate>
            body {
                -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
                -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
                -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
                animation: -amp-start 8s steps(1, end) 0s 1 normal both
            }

            @-webkit-keyframes -amp-start {
                from {
                    visibility: hidden
                }
                to {
                    visibility: visible
                }
            }

            @-moz-keyframes -amp-start {
                from {
                    visibility: hidden
                }
                to {
                    visibility: visible
                }
            }

            @-ms-keyframes -amp-start {
                from {
                    visibility: hidden
                }
                to {
                    visibility: visible
                }
            }

            @-o-keyframes -amp-start {
                from {
                    visibility: hidden
                }
                to {
                    visibility: visible
                }
            }

            @keyframes -amp-start {
                from {
                    visibility: hidden
                }
                to {
                    visibility: visible
                }
            }
        </style>
        <noscript>
            <style amp-boilerplate>
                body {
                    -webkit-animation: none;
                    -moz-animation: none;
                    -ms-animation: none;
                    animation: none
                }
            </style>
        </noscript>
        <!--<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&subset=cyrillic" rel="stylesheet">-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
        <style amp-custom>

            div,
            span,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p,
            blockquote,
            a,
            ol,
            ul,
            li,
            figcaption {
                font: inherit
            }

            * {
                box-sizing: border-box
            }

            body {
                position: relative;
                font-style: normal;
                line-height: 1.5
            }

            section {
                background-color: transparent;
                background-position: 50% 50%;
                background-repeat: no-repeat;
                background-size: cover
            }

            .vacancy_table{
                width: 100%;
                text-align: center;
                margin-top: 40px;
                margin-bottom: 40px;
            }

            section,
            .container,
            .container-fluid {
                position: relative;
                word-wrap: break-word
            }

            a.mbr-iconfont:hover {
                text-decoration: none
            }

            h1,
            h2,
            h3 {
                margin: auto
            }

            h1,
            h3,
            p {
                padding: 10px 0;
                margin-bottom: 15px
            }

            p,
            li,
            blockquote {
                color: #15181b;
                letter-spacing: 0.5px;
                line-height: 1.7
            }

            ul,
            ol,
            pre,
            blockquote {
                margin-bottom: 0;
                margin-top: 0
            }

            pre {
                background: #f4f4f4;
                padding: 10px 24px;
                white-space: pre-wrap
            }

            p {
                margin-top: 0
            }

            a {
                font-style: normal;
                font-weight: 400;
                cursor: pointer
            }

            a,
            a:hover {
                text-decoration: none
            }

            figure {
                margin-bottom: 0
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            .h1,
            .h2,
            .h3,
            .h4,
            .h5,
            .h6,
            .display-1,
            .display-2,
            .display-3,
            .display-4 {
                line-height: 1;
                word-break: break-word;
                word-wrap: break-word
            }

            b,
            strong {
                font-weight: bold
            }

            blockquote {
                padding: 10px 0 10px 20px;
                position: relative;
                border-left: 3px solid
            }

            input:-webkit-autofill,
            input:-webkit-autofill:hover,
            input:-webkit-autofill:focus,
            input:-webkit-autofill:active {
                transition-delay: 9999s;
                transition-property: background-color, color
            }

            html,
            body {
                height: auto;
                min-height: 100vh
            }


            [class*="-iconfont"] {
                display: inline-flex;
            }


            .mbr-section-title{font-style:normal;line-height:1.2}
            .mbr-section-subtitle{line-height:1.3}
            .mbr-text{font-style:normal;line-height:1.6}
            .btn{font-weight:400;border-width:2px;border-style:solid;font-style:normal;letter-spacing:2px;margin:.4rem .8rem;white-space:normal;transition:all .3s ease-in-out,box-shadow 2s ease-in-out;display:inline-flex;align-items:center;justify-content:center;word-break:break-word;-webkit-align-items:center;-webkit-justify-content:center;display:-webkit-inline-flex}
            .btn-form{border-radius:0}
            .btn-form:hover{cursor:pointer}
            .mbr-figure img,.mbr-figure iframe{display:block;width:100%}
            .card{background-color:transparent;border:none}
            .card-wrapper{flex:1;-webkit-flex:1}
            .card-img{text-align:center;flex-shrink:0;-webkit-flex-shrink:0}
            .media{max-width:100%;margin:0 auto}
            .mbr-figure{-ms-flex-item-align:center;-ms-grid-row-align:center;-webkit-align-self:center;align-self:center}
            .media-container>div{max-width:100%}
            .mbr-figure img,.card-img img{width:100%}
            @media (max-width: 991px) {
                .media-size-item{width:auto}
                .media{width:auto}
                .mbr-figure{width:100%}
            }
            .hidden{visibility:hidden}
            .super-hide{display:none}
            .inactive{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;pointer-events:none;-webkit-user-drag:none;user-drag:none}
            textarea[type="hidden"]{display:none}
            #scrollToTop{display:none}
            .popover-content ul.show{min-height:155px}
            .mbr-white{color:#fff}
            .mbr-black{color:#000}
            .mbr-bg-white{background-color:#fff}
            .mbr-bg-black{background-color:#000}
            .align-left{text-align:left}
            .align-center{text-align:center}
            .align-right{text-align:right}
            @media (max-width: 767px) {
                .align-left,.align-center,.align-right,.mbr-section-btn,.mbr-section-title{text-align:center}
            }
            .mbr-light{font-weight:300}
            .mbr-regular{font-weight:400}
            .mbr-semibold{font-weight:500}
            .mbr-bold{font-weight:700}
            .mbr-section-btn{margin-left:-.25rem;margin-right:-.25rem;font-size:0}
            nav .mbr-section-btn{margin-left:0;margin-right:0}
            .btn .mbr-iconfont,.btn.btn-sm .mbr-iconfont{cursor:pointer;margin-right:.5rem}
            .btn.btn-md .mbr-iconfont,.btn.btn-md .mbr-iconfont{margin-right:.8rem}
            [type="submit"]{-webkit-appearance:none}
            .mbr-fullscreen .mbr-overlay{min-height:100vh}
            .mbr-fullscreen{display:flex;display:-webkit-flex;display:-moz-flex;display:-ms-flex;display:-o-flex;align-items:center;-webkit-align-items:center;min-height:100vh;box-sizing:border-box;padding-top:3rem;padding-bottom:3rem}
            section.sidebar-open:before{content:'';position:fixed;top:0;bottom:0;right:0;left:0;background-color:rgba(0,0,0,0.2);z-index:1040}
            amp-img img{max-height:100%;max-width:100%}
            img.mbr-temp{width:100%}
            .is-builder .nodisplay+img[async],.is-builder .nodisplay+img[decoding="async"],.is-builder amp-img>a+img[async],.is-builder amp-img>a+img[decoding="async"]{display:none}
            html:not(.is-builder) amp-img>a{position:absolute;top:0;bottom:0;left:0;right:0;z-index:1}
            .is-builder .temp-amp-sizer{position:absolute}
            .is-builder amp-youtube .temp-amp-sizer,.is-builder amp-vimeo .temp-amp-sizer{position:static}
            .is-builder section.horizontal-menu .ampstart-btn{display:none}
            @media (max-width: 991px) {
                .is-builder section.horizontal-menu .navbar-toggler{display:block}
            }
            .is-builder section.horizontal-menu .dropdown-menu{z-index:auto;opacity:1;pointer-events:auto}
            .is-builder section.horizontal-menu .nav-dropdown .link.dropdown-toggle[aria-expanded="true"]{margin-right:0;padding:.667em 1em}
            .mobirise-spinner{position:absolute;top:50%;left:40%;margin-left:10%;-webkit-transform:translate3d(-50%,-50%,0);z-index:4}
            .mobirise-spinner em{width:24px;height:24px;border-radius:100%;display:inline-block;-webkit-animation:slide 1s infinite}
            .mobirise-spinner em:nth-child(1){-webkit-animation-delay:.1s}
            .mobirise-spinner em:nth-child(2){-webkit-animation-delay:.2s}
            .mobirise-spinner em:nth-child(3){-webkit-animation-delay:.3s}
            @-moz-keyframes slide {
            0%{-webkit-transform:scale(1)}
            50%{opacity:.3;-webkit-transform:scale(2)}
            100%{-webkit-transform:scale(1)}
            }
            @-webkit-keyframes slide {
            0%{-webkit-transform:scale(1)}
            50%{opacity:.3;-webkit-transform:scale(2)}
            100%{-webkit-transform:scale(1)}
            }
            @-o-keyframes slide {
            0%{-webkit-transform:scale(1)}
            50%{opacity:.3;-webkit-transform:scale(2)}
            100%{-webkit-transform:scale(1)}
            }
            @keyframes slide {
            0%{-webkit-transform:scale(1)}
            50%{opacity:.3;-webkit-transform:scale(2)}
            100%{-webkit-transform:scale(1)}
            }
            .mobirise-loader .amp-active>div{display:none}
            .mbr-container{max-width:1200px;padding:0 10px;margin:0 auto;position:relative}
            .container{padding-right:15px;padding-left:15px;width:100%;margin-right:auto;margin-left:auto}
            @media (max-width: 767px) {
                .container{max-width:540px}
            }
            @media (min-width: 768px) {
                .container{max-width:720px}
            }
            @media (min-width: 992px) {
                .container{max-width:960px}
            }
            @media (min-width: 1200px) {
                .container{max-width:1140px}
            }
            .mbr-row{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}
            .mbr-justify-content-center{justify-content:center;-webkit-justify-content:center}
            @media (max-width: 767px) {
                .mbr-col-sm-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 100%}
                .mbr-row{margin:0}
            }
            @media (min-width: 768px) {
                .mbr-col-md-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 25%}
                .mbr-col-md-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 33.333333%}
                .mbr-col-md-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 41.666667%}
                .mbr-col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 50%}
                .mbr-col-md-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 58.333333%}
                .mbr-col-md-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%;padding-left:15px;padding-right:15px;-webkit-flex:0 0 66.666667%}
                .mbr-col-md-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%;padding-left:15px;padding-right:15px;-webkit-flex:0 0 83.333333%}
                .mbr-col-md-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 100%}
            }
            @media (min-width: 992px) {
                .mbr-col-lg-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 16.666667%}
                .mbr-col-lg-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 25%}
                .mbr-col-lg-4{-ms-flex:0 0 33.33%;flex:0 0 33.33%;max-width:33.33%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 33.33%}
                .mbr-col-lg-5{-ms-flex:0 0 41.666%;flex:0 0 41.666%;max-width:41.666%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 41.666%}
                .mbr-col-lg-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 50%}
                .mbr-col-lg-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%;padding-left:15px;padding-right:15px;-webkit-flex:0 0 66.666667%}
                .mbr-col-lg-10{-ms-flex:0 0 83.3333%;flex:0 0 83.3333%;max-width:83.3333%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 83.3333%}
                .mbr-col-lg-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 100%}
            }
            @media (min-width: 1200px) {
                .mbr-col-xl-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%;padding-right:15px;padding-left:15px;-webkit-flex:0 0 58.333333%}
                .mbr-col-xl-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%;padding-left:15px;padding-right:15px;-webkit-flex:0 0 66.666667%}
            }
            amp-sidebar{background:transparent}
            #scrollToTopMarker{position:absolute;width:0;height:0;top:300px}
            #scrollToTopButton{position:fixed;bottom:25px;right:25px;opacity:.4;z-index:5000;font-size:32px;height:60px;width:60px;border:none;border-radius:3px;cursor:pointer}
            #scrollToTopButton:focus{outline:none}
            #scrollToTopButton a:before{content:'';position:absolute;height:40%;top:36%;width:2px;left:calc(50% - 1px)}
            #scrollToTopButton a:after{content:'';position:absolute;border-top:2px solid;border-right:2px solid;width:40%;height:40%;left:calc(30% - 1px);bottom:30%;transform:rotate(-45deg);-webkit-transform:rotate(-45deg)}
            .is-builder #scrollToTopButton a:after{left:30%}
            body{font-family:'Roboto'}
            blockquote{border-color:#4ea2e3}
            .display-1{font-family:'Roboto',sans-serif;font-size:3.5rem}
            .display-2{font-family:'Roboto',sans-serif;font-size:2.2rem}
            .display-4{font-family:'Roboto',sans-serif;font-size:.9rem}
            .display-5{font-family:'Roboto',sans-serif;font-size:1.8rem}
            .display-7{font-family:'Roboto',sans-serif;font-size:1.1rem}
            @media (max-width: 768px) {
                .display-1{font-size:3.6rem;font-size:calc(2.225rem + (4.5 - 2.225) * ((100vw - 20rem) / (48 - 20)));line-height:calc(1.4 * (2.225rem + (4.5 - 2.225) * ((100vw - 20rem) / (48 - 20))))}
                .display-2{font-size:1.76rem;font-size:calc(1.42rem + (2.2 - 1.42) * ((100vw - 20rem) / (48 - 20)));line-height:calc(1.4 * (1.42rem + (2.2 - 1.42) * ((100vw - 20rem) / (48 - 20))))}
                .display-4{font-size:.72rem;font-size:calc(0.965rem + (0.9 - 0.965) * ((100vw - 20rem) / (48 - 20)));line-height:calc(1.4 * (0.965rem + (0.9 - 0.965) * ((100vw - 20rem) / (48 - 20))))}
                .display-5{font-size:1.44rem;font-size:calc(1.28rem + (1.8 - 1.28) * ((100vw - 20rem) / (48 - 20)));line-height:calc(1.4 * (1.28rem + (1.8 - 1.28) * ((100vw - 20rem) / (48 - 20))))}
            }
            .btn{padding:1rem 2rem;border-radius:0}
            .btn-sm{border-width:1px;padding:.6rem .8rem;border-radius:0}
            .btn-md{font-weight:600;padding:1rem 2rem;border-radius:0}
            .bg-primary{background-color:#4ea2e3}
            .bg-success{background-color:#0dcd7b}
            .bg-info{background-color:#8282e7}
            .bg-warning{background-color:#767676}
            .bg-danger{background-color:#a0a0a0}
            .btn-primary,.btn-primary:active,.btn-primary.active{background-color:#4ea2e3;border-color:#4ea2e3;color:#fff}
            .btn-primary:hover,.btn-primary:focus,.btn-primary.focus{color:#fff;background-color:#1f7dc5;border-color:#1f7dc5}
            .btn-primary.disabled,.btn-primary:disabled{color:#fff;background-color:#1f7dc5;border-color:#1f7dc5}
            .btn-secondary,.btn-secondary:active,.btn-secondary.active{background-color:#4addff;border-color:#4addff;color:#003c4a}
            .btn-secondary:hover,.btn-secondary:focus,.btn-secondary.focus{color:#003c4a;background-color:#00cdfd;border-color:#00cdfd}
            .btn-secondary.disabled,.btn-secondary:disabled{color:#003c4a;background-color:#00cdfd;border-color:#00cdfd}
            .btn-info,.btn-info:active,.btn-info.active{background-color:#8282e7;border-color:#8282e7;color:#fff}
            .btn-info:hover,.btn-info:focus,.btn-info.focus{color:#fff;background-color:#4242db;border-color:#4242db}
            .btn-info.disabled,.btn-info:disabled{color:#fff;background-color:#4242db;border-color:#4242db}
            .btn-success,.btn-success:active,.btn-success.active{background-color:#0dcd7b;border-color:#0dcd7b;color:#fff}
            .btn-success:hover,.btn-success:focus,.btn-success.focus{color:#fff;background-color:#088550;border-color:#088550}
            .btn-success.disabled,.btn-success:disabled{color:#fff;background-color:#088550;border-color:#088550}
            .btn-warning,.btn-warning:active,.btn-warning.active{background-color:#767676;border-color:#767676;color:#fff}
            .btn-warning:hover,.btn-warning:focus,.btn-warning.focus{color:#fff;background-color:#505050;border-color:#505050}
            .btn-warning.disabled,.btn-warning:disabled{color:#fff;background-color:#505050;border-color:#505050}
            .btn-danger,.btn-danger:active,.btn-danger.active{background-color:#a0a0a0;border-color:#a0a0a0;color:#fff}
            .btn-danger:hover,.btn-danger:focus,.btn-danger.focus{color:#fff;background-color:#7a7a7a;border-color:#7a7a7a}
            .btn-danger.disabled,.btn-danger:disabled{color:#fff;background-color:#7a7a7a;border-color:#7a7a7a}
            .btn-black,.btn-black:active,.btn-black.active{background-color:#333;border-color:#333;color:#fff}
            .btn-black:hover,.btn-black:focus,.btn-black.focus{color:#fff;background-color:#0d0d0d;border-color:#0d0d0d}
            .btn-black.disabled,.btn-black:disabled{color:#fff;background-color:#0d0d0d;border-color:#0d0d0d}
            .btn-white,.btn-white:active,.btn-white.active{background-color:#fff;border-color:#fff;color:gray}
            .btn-white:hover,.btn-white:focus,.btn-white.focus{color:gray;background-color:#d9d9d9;border-color:#d9d9d9}
            .btn-white.disabled,.btn-white:disabled{color:gray;background-color:#d9d9d9;border-color:#d9d9d9}
            .btn-white,.btn-white:active,.btn-white.active{color:#333}
            .btn-white:hover,.btn-white:focus,.btn-white.focus{color:#333}
            .btn-white.disabled,.btn-white:disabled{color:#333}
            .btn-primary-outline,.btn-primary-outline:active,.btn-primary-outline.active{background:none;border-color:#1c6faf;color:#1c6faf}
            .btn-primary-outline:hover,.btn-primary-outline:focus,.btn-primary-outline.focus{color:#fff;background-color:#4ea2e3;border-color:#4ea2e3}
            .btn-primary-outline.disabled,.btn-primary-outline:disabled{color:#fff;background-color:#4ea2e3;border-color:#4ea2e3}
            .btn-secondary-outline,.btn-secondary-outline:active,.btn-secondary-outline.active{background:none;border-color:#00b8e3;color:#00b8e3}
            .btn-secondary-outline:hover,.btn-secondary-outline:focus,.btn-secondary-outline.focus{color:#003c4a;background-color:#4addff;border-color:#4addff}
            .btn-secondary-outline.disabled,.btn-secondary-outline:disabled{color:#003c4a;background-color:#4addff;border-color:#4addff}
            .btn-info-outline,.btn-info-outline:active,.btn-info-outline.active{background:none;border-color:#2c2cd7;color:#2c2cd7}
            .btn-info-outline:hover,.btn-info-outline:focus,.btn-info-outline.focus{color:#fff;background-color:#8282e7;border-color:#8282e7}
            .btn-info-outline.disabled,.btn-info-outline:disabled{color:#fff;background-color:#8282e7;border-color:#8282e7}
            .btn-success-outline,.btn-success-outline:active,.btn-success-outline.active{background:none;border-color:#076d41;color:#076d41}
            .btn-success-outline:hover,.btn-success-outline:focus,.btn-success-outline.focus{color:#fff;background-color:#0dcd7b;border-color:#0dcd7b}
            .btn-success-outline.disabled,.btn-success-outline:disabled{color:#fff;background-color:#0dcd7b;border-color:#0dcd7b}
            .btn-warning-outline,.btn-warning-outline:active,.btn-warning-outline.active{background:none;border-color:#434343;color:#434343}
            .btn-warning-outline:hover,.btn-warning-outline:focus,.btn-warning-outline.focus{color:#fff;background-color:#767676;border-color:#767676}
            .btn-warning-outline.disabled,.btn-warning-outline:disabled{color:#fff;background-color:#767676;border-color:#767676}
            .btn-danger-outline,.btn-danger-outline:active,.btn-danger-outline.active{background:none;border-color:#6d6d6d;color:#6d6d6d}
            .btn-danger-outline:hover,.btn-danger-outline:focus,.btn-danger-outline.focus{color:#fff;background-color:#a0a0a0;border-color:#a0a0a0}
            .btn-danger-outline.disabled,.btn-danger-outline:disabled{color:#fff;background-color:#a0a0a0;border-color:#a0a0a0}
            .btn-black-outline,.btn-black-outline:active,.btn-black-outline.active{background:none;border-color:#000;color:#000}
            .btn-black-outline:hover,.btn-black-outline:focus,.btn-black-outline.focus{color:#fff;background-color:#333;border-color:#333}
            .btn-black-outline.disabled,.btn-black-outline:disabled{color:#fff;background-color:#333;border-color:#333}
            .btn-white-outline,.btn-white-outline:active,.btn-white-outline.active{background:none;border-color:#fff;color:#fff}
            .btn-white-outline:hover,.btn-white-outline:focus,.btn-white-outline.focus{color:#333;background-color:#fff;border-color:#fff}
            .text-primary{color:#4ea2e3}
            .text-secondary{color:#4addff}
            .text-success{color:#0dcd7b}
            .text-info{color:#8282e7}
            .text-warning{color:#767676}
            .text-danger{color:#a0a0a0}
            .text-white{color:#fff}
            .text-black{color:#000}
            a.text-primary:hover,a.text-primary:focus{color:#1c6faf}
            a.text-secondary:hover,a.text-secondary:focus{color:#00b8e3}
            a.text-success:hover,a.text-success:focus{color:#076d41}
            a.text-info:hover,a.text-info:focus{color:#2c2cd7}
            a.text-warning:hover,a.text-warning:focus{color:#434343}
            a.text-danger:hover,a.text-danger:focus{color:#6d6d6d}
            a.text-white:hover,a.text-white:focus{color:#b3b3b3}
            a.text-black:hover,a.text-black:focus{color:#4d4d4d}
            .alert-success{background-color:#0dcd7b}
            .alert-info{background-color:#8282e7}
            .alert-warning{background-color:#767676}
            .alert-danger{background-color:#a0a0a0}
            a,a:hover{color:#ffffff}
            .mbr-plan-header.bg-primary .mbr-plan-subtitle,.mbr-plan-header.bg-primary .mbr-plan-price-desc{color:#feffff}
            .mbr-plan-header.bg-success .mbr-plan-subtitle,.mbr-plan-header.bg-success .mbr-plan-price-desc{color:#acfad9}
            .mbr-plan-header.bg-info .mbr-plan-subtitle,.mbr-plan-header.bg-info .mbr-plan-price-desc{color:#fff}
            .mbr-plan-header.bg-warning .mbr-plan-subtitle,.mbr-plan-header.bg-warning .mbr-plan-price-desc{color:#b6b6b6}
            .mbr-plan-header.bg-danger .mbr-plan-subtitle,.mbr-plan-header.bg-danger .mbr-plan-price-desc{color:#e0e0e0}
            .mobirise-spinner em:nth-child(1){background:#4ea2e3}
            .mobirise-spinner em:nth-child(2){background:#4addff}
            .mobirise-spinner em:nth-child(3){background:#0dcd7b}
            #scrollToTopMarker{display:none}
            #scrollToTopButton{background-color:#4ea2e3}
            #scrollToTopButton a:before{background:#fff}
            #scrollToTopButton a:after{border-top-color:#fff;border-right-color:#fff}
            .cid-r8CfILVckJ{padding-top:120px;padding-bottom:30px;background-image:url(../../assets/img/back.png);background-position: 50% 0px;background-size: cover;background-repeat: no-repeat;background-attachment: fixed;height: 100%;}
            .cid-r8CfILVckJ .mbr-title{padding-bottom:1rem}
            .cid-r8CfILVckJ .mbr-section-btn{padding-top:1.5rem}
            .engine{position:absolute;text-indent:-2400px;text-align:center;padding:0;top:0;left:-2400px}
            .breadcrumb_1{display:inline-flex;list-style:none;padding: 0;}
            .breadcrumb_1 li{font-size:20px}
            .breadcrumb_1 li a{color:#fff}
            .breadcrumb_1 li+li:before{padding:8px;color:#fff;content:"/\00a0"}
            .cid-r8CeLT8a09 amp-sidebar{min-width:260px;z-index:1050;background-color:#fff}
            .cid-r8CeLT8a09 amp-sidebar.open:after{content:'';position:absolute;top:0;left:0;bottom:0;right:0;background-color:red}
            .cid-r8CeLT8a09 .open{transform:translateX(0%);display:block;-webkit-transform:translateX(0%)}
            .cid-r8CeLT8a09 .builder-sidebar{background-color:#fff;position:relative;min-height:100vh;z-index:1030;padding:1rem 2rem;max-width:20rem}
            .cid-r8CeLT8a09 .headerbar{display:-webkit-flex;flex-direction:column;justify-content:center;padding:.5rem 1rem;min-height:70px;align-items:center;background:#fff;-webkit-flex-direction:column;-webkit-justify-content:center;-webkit-align-items:center}
            .cid-r8CeLT8a09 .headerbar.sticky-nav{position:fixed;z-index:1000;width:100%}
            .cid-r8CeLT8a09 button.sticky-but{position:fixed}
            .cid-r8CeLT8a09 .brand{display:-webkit-flex;align-items:center;align-self:flex-start;padding-right:30px;-webkit-align-items:center;-webkit-align-self:flex-start}
            .cid-r8CeLT8a09 .brand p{margin:0;padding:0}
            .cid-r8CeLT8a09 .brand-name{color:#197bc6}
            .cid-r8CeLT8a09 .sidebar{padding:1rem 0;margin:0}
            .cid-r8CeLT8a09 .sidebar > li{list-style:none;display:-webkit-flex;flex-direction:column;-webkit-flex-direction:column}
            .cid-r8CeLT8a09 .sidebar a{display:block;text-decoration:none;margin-bottom:10px}
            .cid-r8CeLT8a09 .close-sidebar{width:30px;height:30px;position:relative;cursor:pointer;background-color:transparent;border:none}
            .cid-r8CeLT8a09 .close-sidebar:focus{outline:2px auto #4ea2e3}
            .cid-r8CeLT8a09 .close-sidebar span{position:absolute;left:0;width:30px;height:2px;border-right:5px;background-color:#197bc6}
            .cid-r8CeLT8a09 .close-sidebar span:nth-child(1){transform:rotate(45deg);-webkit-transform:rotate(45deg)}
            .cid-r8CeLT8a09 .close-sidebar span:nth-child(2){transform:rotate(-45deg);-webkit-transform:rotate(-45deg)}
            @media (min-width: 992px) {
                .cid-r8CeLT8a09 .brand-name{min-width:8rem}
                .cid-r8CeLT8a09 .builder-sidebar{margin-left:auto}
                .cid-r8CeLT8a09 .builder-sidebar .sidebar li{flex-direction:row;flex-wrap:wrap;-webkit-flex-direction:row;-webkit-flex-wrap:wrap}
                .cid-r8CeLT8a09 .builder-sidebar .sidebar li a{padding:.5rem;margin:0}
                .cid-r8CeLT8a09 .builder-overlay{display:none}
            }
            .cid-r8CeLT8a09 .hamburger{position:absolute;top:25px;right:20px;margin-left:auto;width:30px;height:20px;background:none;border:none;cursor:pointer;z-index:1000}
            @media (min-width: 768px) {
                .cid-r8CeLT8a09 .hamburger{top:calc(0.5rem + 55 * 0.5px - 10px)}
            }
            .cid-r8CeLT8a09 .hamburger:focus{outline:none}
            .cid-r8CeLT8a09 .hamburger span{position:absolute;right:0;width:30px;height:2px;border-right:5px;background-color: #079ea5;}
            .cid-r8CeLT8a09 .hamburger span:nth-child(1){top:0;transition:all .2s}
            .cid-r8CeLT8a09 .hamburger span:nth-child(2){top:8px;transition:all .15s}
            .cid-r8CeLT8a09 .hamburger span:nth-child(3){top:8px;transition:all .15s}
            .cid-r8CeLT8a09 .hamburger span:nth-child(4){top:16px;transition:all .2s}
            .cid-r8CeLT8a09 amp-img{margin-right:1rem;display:-webkit-flex;align-items:center;-webkit-align-items:center;height:30px;width:170px}
            @media (max-width: 768px) {
                .cid-r8CeLT8a09 amp-img{max-height:30px;max-width:170px}
            }
            .cid-r8CgwSzLYz{padding-top:30px;padding-bottom:30px;background-color:#fff}
            .cid-r8CgwSzLYz .mbr-text{padding:0 0}
            .cid-qN7nkHa8tL{padding-top:40px;padding-bottom:40px;background-color:#099ca4;background-image: url(../../assets/img/back.png)}
            .cid-qN7nkHa8tL .img-wrap{width:80px;padding-bottom:1rem}
            .cid-qN7nkHa8tL .container-fluid{padding-left:15px;padding-right:15px}
            .cid-qN7nkHa8tL .area{width:100%}
            .cid-qN7nkHa8tL textarea{width:100%;color:#fff;background-color:transparent;border:1px solid #989898}
            .cid-qN7nkHa8tL input{margin-bottom:2rem;width:100%;color:#fff;background-color:transparent;border:1px solid #989898}
            .cid-qN7nkHa8tL .group-title{color:#c1c1c1;margin-bottom:0;text-align:left}
            .cid-qN7nkHa8tL .items{padding-right:0;padding-left:0;padding-bottom:0}
            .cid-qN7nkHa8tL .list-item{display:flex;padding-top:15px;padding-bottom:15px;}
            .cid-qN7nkHa8tL .mbr-iconfont{padding-right:1rem;font-size: 0.8rem;color: #ffffff;margin:auto}
            .cid-qN7nkHa8tL .mbr-iconfont-c{padding-right:1rem;font-size:1.4rem;color:#fff;margin:auto}
            .cid-qN7nkHa8tL h5{margin:0;width:100%}
            .cid-qN7nkHa8tL .contacts-ico{display:flex;align-items:center;padding-top:15px;border-bottom:1px dotted rgba(255,255,255,0.2);padding-bottom:15px;color:#fff;text-align:left}
            @media (min-width: 992px) {
                .cid-qN7nkHa8tL .contacts-ico{margin-right:30px}
                .cid-qN7nkHa8tL .items{margin-right:30px}
            }
            .cid-qN7nkHa8tL .text{text-align:left;color:#fff}
            .cid-qP6waPBqRw{padding-top:10px;padding-bottom:50px;background-color:#fff;margin-bottom: 30px;}
            .cid-qP6waPBqRw .wrapper{flex-direction:row;display:flex;align-items:center;justify-content:center;flex-wrap:wrap}
            .cid-qN7nkHa8tL_56 { background-color: #007f93;}
            .style1000 {color: #ffffff; padding: 20px 0;font-size: 16px;}
            .articleTitle {font-size: 24px;font-weight: 700;line-height: 30px;}
            .articlePhoto_1 {width: 100%;}
            .breadcrumb_1,.breadcrumb_1 * {font-size: 15px;}
            .reading_count { color: #848484; }
            .sidebar li a { font-family: 'Roboto',sans-serif;color: #000; }
            .cid-r8CeLT8a09 .sidebar a:hover { color: #007f93; }
            .cid-r8CeLT8a09 .close-sidebar span { background-color: #1e8e9f; }
            .mbr-section-subtitle { margin-bottom: 0; padding: 0; }
            @media (max-width: 768px){
                .mbr-title.display-1 {
                    font-size: 24px;
                    text-transform: uppercase;
                    padding: 0;
                    margin: 0;
                    line-height: 49px;
                }
                .cid-r8CfILVckJ {
                    padding-top: 81px;
                    padding-bottom: 20px;
                }
            }
            .breadcrumb_1 li {
                font-size: 15px;
                color: #ffffff;
            }
            .youtube-embed-wrapper {
                display: none;
            }
            .menuList {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                padding-left: 0;
                margin-bottom: 0;
                list-style: none;
            }
            .menuList a {
                flex: 1 1 auto;
                text-align: center;
                position: relative;
                background-color: #00bfb2;
                color: #fff;
                font-size: 14px;
                font-weight: 700;
                line-height: 24px;
                text-transform: uppercase;
                display: block;
                padding: .5rem 1rem;
                border-bottom: 1px solid rgba(0, 124, 116, .2);
                border-right: 1px solid rgba(0, 124, 116, .2);
            }
            .menuList a.active {
                background-color: #00a99d;
            }
            p {
                padding: 0;
                margin-bottom: 5px;
            }
            .content {
                margin: 20px 0;
            }
            .m20 {
                margin-top: 20px;
            }
            .mb0 {
                margin-bottom: 0;
            }
            .specialist {
                color: #00867c;
            }
            .promotionList {

            }
            .promotionList .item a {
                color: #000;
            }
            .sectionTitle {
                background: #ff8600;
                color: #ffffff;
                padding: .5rem 1rem;
                font-size: 14px;
                font-weight: 700;
                line-height: 24px;
                text-align: center;
            }
            .promotionList .item {
                width: 100%;
                padding: .5rem 1rem;
                margin-bottom: 10px;
                border: solid 1px #d4d4d4;
                box-sizing: border-box;
            }
            .prmTitle {
                margin-bottom: 5px;
            }
            .prmTitle a {
                font-size: 17px;
                font-weight: bold;
            }
            .prmPrice {
                font-weight: bold;
                margin-bottom: 5px;
                color: #e67900;
            }
            .prmPrice .discount {
                text-decoration: line-through;
                font-weight: normal;
                color: #969696;
            }
            .prmExpires {
                font-size: 13px;
                color: #008c83;
            }
        </style>

        <script async src="https://cdn.ampproject.org/v0.js"></script>
        <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>
        <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
        <script async custom-element="amp-animation" src="https://cdn.ampproject.org/v0/amp-animation-0.1.js"></script>
        <script async custom-element="amp-position-observer" src="https://cdn.ampproject.org/v0/amp-position-observer-0.1.js"></script>
        <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
        <script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script>
        <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
        <script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>
        <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
        <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
        <script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
        <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
        <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>

    </head>
    <body>

    <div id="top-page"></div>
    <amp-animation id="showScrollToTopAnim" layout="nodisplay">
        <script type="application/json"> { "duration": "200ms", "fill": "both", "iterations": "1", "direction": "alternate", "animations": [ { "selector": "#scrollToTopButton", "keyframes": [ { "opacity": "0.4", "visibility": "visible" } ] } ] } </script>
    </amp-animation>
    <amp-animation id="hideScrollToTopAnim" layout="nodisplay">
        <script type="application/json"> { "duration": "200ms", "fill": "both", "iterations": "1", "direction": "alternate", "animations": [ { "selector": "#scrollToTopButton", "keyframes": [ { "opacity": "0", "visibility": "hidden" } ] } ] } </script>
    </amp-animation>
    <div id="scrollToTopMarker">
        <amp-position-observer on="enter:hideScrollToTopAnim.start; exit:showScrollToTopAnim.start" layout="nodisplay"> </amp-position-observer>
    </div>
    <!--
    <amp-sidebar id="sidebar" class="cid-r8CeLT8a09" layout="nodisplay" side="right">
        <div class="builder-sidebar" id="builder-sidebar">

            <button on="tap:sidebar.close" class="close-sidebar">
                <span></span>
                <span></span>
            </button>

            <div class="sidebar mbr-white" data-app-modern-menu="true">
                <?PHP
                $notShow = [3,5];
                $menus = $data['menus']['parent'][0];
                $count = count($menus);
                if(!empty($menus))
                {
                    for($x=0;$x<$count;$x++)
                    {
                        $val  = $menus[$x];
                        if(!in_array($val['id'],$notShow))
                        {
                            $link = $val['link'];

                            if($val['type'] == 2){
                                $link = Yii::$app->params['site.enterprise_slug'].'/'.$val['link'].'-'.$val['id'];
                            }elseif($val['type'] == 3){
                                $link = 'kateqoriya/'.$val['link'];
                            }else if($val['type'] == 1){
                                if(!in_array($val['id'],$notStaticPrefix))
                                {
                                    $link = Yii::$app->params['site.static_slug'].'/'.$val['link'];
                                }
                            }

                            $link = Url::base(true).'/'.$link;
//                          $link = isset($data[$val['id']]) ? "javascript:void(0);" : $link;

                            echo "<li> <a href=\"{$link}\">{$val['name']}</a></li>";

                        };
                    };
                }
                ?>
            </div>
        </div>
    </amp-sidebar>
    -->
    <section class="menu cid-r8CeLT8a09" id="menu1-0">

        <nav class="headerbar sticky-nav">
            <div class="brand">
                    <span class="brand-logo">
                  <amp-img src="/assets/img/logo.png" width="128" height="32" alt="E-tib.az | Logo" class="mobirise-loader">
                      <div placeholder="" class="placeholder">
                                    <div class="mobirise-spinner">
                                        <em></em>
                                        <em></em>
                                        <em></em>
                                    </div></div>

                  </amp-img>
              </span>
                <!-- <p class="brand-name mbr-fonts-style display-2">Mobirise AMP</p> -->
            </div>
        </nav>

<!--        <button on="tap:sidebar.toggle" class="ampstart-btn hamburger sticky-but">-->
<!--            <span></span>-->
<!--            <span></span>-->
<!--            <span></span>-->
<!--            <span></span>-->
<!--        </button>-->
    </section>