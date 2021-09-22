@extends("layouts.default")
@section("content")
    <style>
        .dados{
            background-color: rgba(220, 220, 220, 0.7);
            border-radius: 20px;
            border-style: ridge;
        }
        .dados span{
            font-size: 100px;
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            color: black;
        }
        .mensageminit{
            background-color: white;
            font-size: 50px;
            color: black;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .saudacao{
            color: black;
            font-size: 50px;
            padding-top: 10px;
            padding-bottom: 10px;
            font-weight: bold;
            font-family: Impact, Haettenschweiler, "Franklin Gothic Bold", Charcoal, "Helvetica Inserat", "Bitstream Vera Sans Bold", "Arial Black", "sans serif";
            font-style: italic;
        }
        .logos{
            padding-bottom: 10px; 
            background-color: white;
            border-radius: 20px;
        }
    </style>
    <div class="container">
        <div class="dados">
            <div class="logos">
                <div class="row">
                   {{--<div class="col text-center"><img src="{{asset('images/fundetec.png')}}"></div>
                    <div class="col text-center"><img src="{{asset('images/seaso.png')}}"></div>--}}
                    <div class="col text-center"><img src="{{asset('images/fundetecSeaso.png')}}"></div>
                </div>
            </div>
            <div>
                <h1 class="text-center saudacao">Seja Bem-Vindo(a)</h1>
                <h1 class="text-center saudacao">ao Restaurante Popular</h1>
                <h1 class="text-center saudacao">-- SANTA CRUZ --</h1>
            </div>
            <div class="mensagem">
                <h1 class="text-center mensageminit">PASSE O CARTÃO NO LEITOR</h1>
            </div>
            <div class="text-center">
                <span id="hours"></span>
                <span class="colon">:</span>
                <span id="minutes"></span>
                <span class="colon">:</span>
                <span id="seconds"></span>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col">
                    
            </div>
            <div class="col">
                <form id="crtvalidation" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="input-group mb-3">
                                <input autocomplete="off" autofocus class="form-control" name="name">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col">

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let toggle = true;
            setInterval(function() {
                let d = new Date().toLocaleTimeString('pt-BR', { hour12: false, hour: 'numeric', minute: 'numeric', second: 'numeric'});
                let parts = d.split(":");
                $('#hours').text(parts[0]);
                $('#minutes').text(parts[1]);
                $(".colon").css({ visibility: toggle?"visible":"hidden"});
                $('#seconds').text(parts[2]);
                toggle=!toggle;
            },500);

            $('#crtvalidation').submit(function(){
                let dados = $( this ).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{route('crtvalidation')}}",
                    data: dados,
                    success: function(data){
                       switch(data[0]){
                           case 100:
                                mensagem(0, 'Erro de Validação !!', 0);
                           break;
                           case 101:
                                mensagem(0, 'Consumidor(a) não Encontrado(a) !!', 0);
                           break;
                           case 102:
                                mensagem(0, 'Categoria Não Encontrada !!', 0);
                           break;
                           case 103:
                                mensagem(0, 'Saldo Insuficiente !!', 0);
                           break;
                           case 104:
                                mensagem(1, 'Autorizado !!', data[1]);
                           break;
                           case 500:
                                mensagem(0, 'Error Interno ', 0);
                           break;
                           case 501:
                                mensagem(0, 'Limite máximo diário excedido', 0);
                           break;
                           default:
                                mensagem(0, 'Erro Não Identificado', 0);
                           break;
                       }
                    }
                });

                return false;
            });
        });
        function mensagem(retorno, mensagem, saldo){
            if(retorno == 1){
                $('.mensagem').css({"background-color": "#004f05"});
                $('.mensagem').html(''+
                '<div class="sucesso row">\n'+
                    '<div class=" col-4 align-self-center">\n'+
                       '<img src="{{asset("images/sucesso.png")}}" alt="Sucesso">\n'+
                    '</div>\n'+
                   ' <div class="col-8 align-self-center">\n'+
                        '<h1 class="text-success align-items-start text-center">'+mensagem+'</h1>\n'+
                        '<h2 class="text-success align-items-end text-center">Seu saldo atual é de: R$ '+saldo+'</h2>\n'+
                    '</div>\n'+
                '</div>');
                $(".sucesso").fadeTo(3200, 800).slideUp(1000, function(){
                    $(".sucesso").slideUp(500);
                    $('.mensagem').css({"background-color": "white"});
                    $('.mensagem').html('<h1 class="text-center mensageminit">PASSE O CARTÃO NO LEITOR</h1>');
                });
            }
            else{
                $('.mensagem').css({"background-color": "#590000"});
                $('.mensagem').html(''+
                '<div class="error row">\n'+
                    '<div class="col-4 align-self-center">\n'+
                       '<img src="{{asset("images/error.png")}}" alt="Erro">\n'+
                    '</div>\n'+
                   ' <div class="col-8 align-self-center">\n'+
                        '<h1 style="color:#ff1212; font-size:60px;" class="align-items-center text-center">'+mensagem+'</h1>\n'+
                    '</div>\n'+
                '</div>');
                $(".error").fadeTo(3200, 800).slideUp(1000, function(){
                    $(".error").slideUp(500);
                    $('.mensagem').css({"background-color": "white"});
                    $('.mensagem').html('<h1 class="text-center mensageminit">PASSE O CARTÃO NO LEITOR</h1>');
                });
            }
        }
    </script>
@endsection
