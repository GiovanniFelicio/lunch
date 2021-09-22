<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>SYS LUNCH - FUNDETEC</title>
    <!-- Fontfaces CSS-->
    @yield('head')
    <link href="{{asset('vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{asset('vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/wow/animate.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('css/theme.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('css/dataTable/jquery.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{asset('/css/dataTable/responsive.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{asset('/css/my.css')}}" rel="stylesheet">
    <script src="{{asset('/js/jquery.js')}}"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader .inner').fadeOut();
            $('#preloader').delay(350).fadeOut('slow');
            $('body').delay(350).css({'overflow': 'visible'});
        })
    </script>
</head>

<body>
    <style>
    
    ul li{
            display: inline-block;
            position: relative;
            line-height: 21px;
            text-align: left;
        }
        ul li a{
            display: block;
            padding: 8px 25px;
            text-decoration: none;
        }
        ul li a:hover{
            color: #fff;
        }
        ul li ul.dropdown{
            width: 100%; /* Set width of the dropdown */
            background: #007b5e;
            display: none;
            position: absolute;
            z-index: 999;
            left: 0;
        }
        ul li:hover ul.dropdown{
            display: block; /* Display the dropdown */
        }
        ul li ul.dropdown li{
            display: block;
        }
    
        #preloader {
            position:fixed;
            top:0;
            left:0;
            right:0;
            bottom:0;
            background-color:#bdbbbb; /* cor do background que vai ocupar o body */
            z-index:999; /* z-index para jogar para frente e sobrepor tudo */
        }
        #preloader .inner {
            position: absolute;
            top: 50%; /* centralizar a parte interna do preload (onde fica a animação)*/
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .load{
            color: #0c0c0c;
        }
        .bolas > div {
            display: inline-block;
            background-color: #fff;
            width: 25px;
            height: 25px;
            border-radius: 100%;
            margin: 3px;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            animation-name: animarBola;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
    
        }
        .bolas > div:nth-child(1) {
            animation-duration:0.75s ;
            animation-delay: 0;
        }
        .bolas > div:nth-child(2) {
            animation-duration: 0.75s ;
            animation-delay: 0.12s;
        }
        .bolas > div:nth-child(3) {
            animation-duration: 0.75s  ;
            animation-delay: 0.24s;
        }
    
        @keyframes animarBola {
            0% {
                -webkit-transform: scale(1);
                transform: scale(1);
                opacity: 1;
            }
            16% {
                -webkit-transform: scale(0.1);
                transform: scale(0.1);
                opacity: 0.7;
            }
            33% {
                -webkit-transform: scale(1);
                transform: scale(1);
                opacity: 1;
            }
        }
        /* end: Preloader */
    </style>
    <div id="preloader">
        <div class="inner">
            <!-- HTML DA ANIMAÇÃO MUITO LOUCA DO SEU PRELOADER! -->
            <div class="bolas">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
<div class="page-wrapper">
    <!-- HEADER DESKTOP-->
    <header class="header-desktop3 d-none d-lg-block">
        <div class="section__content section__content--p35">
            <div class="header3-wrap">
                <div class="header__logo">
                    <a href="{{route("start")}}">
                        <img src="{{asset("images/fundetecLogo.png")}}" alt="fundetecLogo" />
                    </a>
                </div>
                <div class="header__navbar">
                    <ul class="list-unstyled">
                        <li class="has-sub">
                            <a href="{{route('start')}}"><i class="fas fa-play"></i><span class="bot-line"></span>Início</a>
                        </li>
                        <li>
                        <a href="{{route('vendas')}}"><i class="fas fa-dollar-sign"></i><span class="bot-line"></span>Vendas</a>
                        </li>
                        <li>
                            <a href="{{route('categorias')}}"><i class="fas fa-building"></i><span class="bot-line"></span>Categorias</a>
                        </li>
                        <li class="has-sub">
                            <a href="{{route('consumidores')}}"><i class="fas fa-copy"></i><span class="bot-line"></span>Consumidores</a>
                        </li>
                        <li class="has-sub">
                            <a href="{{route('crt')}}"><i class="fas fa-copy"></i><span class="bot-line"></span>CRT</a>
                        </li>
                        @if(Auth::user()->level >= 2)
                            <li class="has-sub">
                                <a class="itens" href="#"><i class="fas fa-plus"></i><span class="bot-line"></span>Mais</a>
                                <ul class="dropdown">
                                        <li><a class="subDropdown" href="{{route('locais')}}"></span>Locais</a></li> 
                                        <li><a class="subDropdown" href="{{route('relatorios')}}"></span>Relatório</a></li>  
                                        <li><a class="subDropdown" href="{{route('users')}}"></span>Usuários</a></li>  
                                </ul>
                            </li>
                        @endif  
                    </ul>
                </div>
                <div class="header__tool">
                    <div class="header-button-item js-item-menu">
                        <i style="color: #ffffff" class="zmdi zmdi-settings"></i>
                        <div class="account-wrap">
                            <div class="account-item account-item--style2 clearfix js-item-menu">
                                <div class="account-dropdown js-dropdown">
                                    <div class="account-dropdown__item">
                                        <a href="{{route('editConta')}}"><i class="zmdi zmdi-account"></i>Account</a>
                                        <a href="#"><i class="zmdi zmdi-settings"></i>Setting</a>
                                        <a href="{{route("logout")}}"><i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER DESKTOP-->
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div> 
        </div>
        <nav class="navbar-mobile">
            <div class="container-fluid">
                <ul class="navbar-mobile__list list-unstyled">
                    <li>
                        <a href="{{route('start')}}">
                            <i class="fas fa-tachometer-alt"></i>Início</a>
                    </li>
                    <li>
                        <a href="{{route('vendas')}}"><i class="fas fa-copy"></i>Vendas</a>
                    </li>
                    <li>
                        <a href="{{route('categorias')}}"><i class="fas fa-desktop"></i>Categorias</a>
                    </li>
                    <li>
                        <a href="{{route('consumidores')}}"> <i class="fas fa-desktop"></i>Consumidores</a>
                    </li>
                    <li>
                        <a href="{{route('crt')}}"> <i class="fas fa-desktop"></i>CRT</a>
                    </li>
                    @if(Auth::user()->level >= 2)
                        <li class="has-sub">
                            <a class="js-arrow" href="#"><i class="fas fa-plus"></i>Mais</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li><a class="subDropdown" href="{{route('locais')}}"></span>Locais</a></li>
                                <li><a class="subDropdown" href="{{route('relatorios')}}"></span>Relatório</a></li>
                                <li><a class="subDropdown" href="{{route('users')}}"></span>Usuários</a></li>
                            </ul>
                        </li>
                    @endif  
                </ul>
            </div>
        </nav>
    </header>
    <!-- HEADER MOBILE-->
    <div class="sub-header-mobile-2 d-block d-lg-none">
        <div class="header__tool">
            <div class="account-wrap">
                <div class="account-item account-item--style2 clearfix js-item-menu">
                    <div class="content">
                        <a class="js-acc-btn" href="#">#</a>
                    </div>
                    <div class="account-dropdown js-dropdown">
                        <div class="account-dropdown__footer">
                            <a href="{{route("logout")}}"><i class="zmdi zmdi-power"></i>Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END HEADER MOBILE -->
    <div id="conteudoTudo">
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>

    </style>

    <!-- PAGE CONTENT-->
    <section id="footer">
        <div class="container">
            <div class="row text-center text-xs-center text-sm-left text-md-left">
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <ul class="list-unstyled quick-links">
                        <li><a href="https://www.fundetec.org.br"><i class="fa fa-angle-double-right" target="_blank"></i>Site Oficial</a></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <ul class="list-unstyled quick-links">
                        <li><a href="#"><i class="fa fa-angle-double-right"></i>Contato</a></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <ul class="list-unstyled quick-links">
                        <li><a href="#"><i class="fa fa-angle-double-right"></i>Reportar</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
                    <p><u><a href="https://www.nationaltransaction.com/">A Fundetec é uma Fundação Pública do Município de Cascavel que tem o objetivo de promover o desenvolvimento científico e tecnológico e fomentar o empreendedorismo e a inovação na região Oeste do Paraná por meio de suas áreas de atuação: Bioenergia, Biotecnologia, Ciências Agrárias, Tecnologias para o Agronegócio e Tecnologia da Informação e Comunicação.</p>
                    <p class="h6">Copyright © 2019<a class="text-green ml-2" href="https://www.fundetec.org.br" target="_blank">Fundetec</a></p>
                </div>
            </div>
        </div>
    </section>


    <!-- ./Footer -->
    <!-- Footer -->

</div>
<h4></h4>
<!-- Jquery JS-->

<script src="{{asset('js/dataTable/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('/js/dataTable/dataTables.responsive.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap JS-->
<script src="{{asset('vendor/bootstrap-4.1/popper.min.js')}}"></script>

<script src="{{asset("/vendor/bootstrap-4.1/bootstrap.min.js")}}"></script>
<!-- Vendor JS       -->
<script src="{{asset("/vendor/slick/slick.min.js")}}"></script>
<script src="{{asset("/vendor/wow/wow.min.js")}}"></script>
<script src="{{asset("/vendor/animsition/animsition.min.js")}}"></script>
<script src="{{asset("/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js")}}">
</script>
<script src="{{asset("/vendor/counter-up/jquery.waypoints.min.js")}}"></script>
<script src="{{asset("/vendor/counter-up/jquery.counterup.min.js")}}">
</script>
<script src="{{asset("/vendor/circle-progress/circle-progress.min.js")}}"></script>
<script src="{{asset("/vendor/perfect-scrollbar/perfect-scrollbar.js")}}"></script>
<script src="{{asset("/vendor/chartjs/Chart.bundle.min.js")}}"></script>
<script src="{{asset("/vendor/select2/select2.min.js")}}"></script>

<!-- Main JS-->
<script src="{{asset("/js/main.js")}}"></script>
@yield("js")
</body>

</html>
<!-- end document-->
