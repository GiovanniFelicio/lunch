@extends('layouts.default')
@section('content')
<script src="{{asset('js/jquery.maskMoney.js')}}" type="text/javascript"></script>
<style>
    .subtitle{
        border-top: 1px solid #cfcfcf;
        border-bottom: 1px solid #cfcfcf;
        padding: 20px;
    }
</style>
<script src="{{asset('js/webcam.js')}}"></script>
    @if(session('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{session('error')}}
        </div>
    @endif
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <div class="login-logo">
                    <h1>Adicionar Consumidor</h1>
                </div>
            <div class="col-lg-12 ">
                <div class="row">
                    <div class="col">

                    </div>
                    <div class="col text-center foto">
                    <div id="my_camera"><img src="{{asset('images/semfoto.jpg')}}"></div>
                        <br>
                        <input type="button" class="btn btn-info" value="Configure" onClick="configure()">
                        <input type="button" class="btn btn-info" value="Take Snapshot" onClick="take_snapshot()">
                    </div>
                    <div class="col foto">
                        <div id="results"></div>
                    </div>
                    <div class="col">

                    </div>
                </div>
            </div>
                <div class="login-form">
                    @if (count($errors) > 0)
                        <div class = "alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{route('createConsumer')}}">
                        @csrf
                        <div id="imagemconsumer"></div>
                        <h3 class="subtitle">Dados Pessoais</h3>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label for="nome" class="col-sm-2 col-form-label">NOME:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input id="nome" class="form-control" type="text" required name="name" placeholder="Ex: Lucas Silva" autofocus>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="cpf" class="col-sm-2 col-form-label">CPF:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input id="cpf" name="cpf" placeholder="111.111.111-11" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="rg" class="col-sm-2 col-form-label">RG:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input required id="rg" name="rg" placeholder="111.111-1" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="nis" class="col-sm-2 col-form-label">NIS:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input id="nis" name="nis" placeholder="Número de Identificação Social" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="produto" class="col-sm-12 col-form-label">DATA DE NASCIMENTO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input class="form-control " name="nasc" type="date">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-sm-2 col-form-label">SEXO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sexo" id="feminino" value="f">
                                        <label class="form-check-label" for="feminino">Feminino &nbsp;</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sexo" id="masculino" value="m">
                                        <label class="form-check-label" for="masculino">Masculino</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="produto" class="col-sm-2 col-form-label">EMAIL:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input class="form-control " name="email" placeholder="Ex: lucas@exemplo.com" type="email">
                                </div>
                            </div>
                        </div>
                        <h3 class="subtitle">Endereço</h3>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label for="cep" class="col-sm-2 col-form-label">CEP:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input required maxlength="10" onkeypress="formatar('##.###-###', this)" onchange="formatar('##.###-###', this)" type="text" id="cep" class="form-control" name="cep">&nbsp;
                                    <button type="button" id="searchcep" class="btn btn-info"><i class="fas fa-search"></i>&nbsp;Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label for="complemento" class="col-sm-2 col-form-label">LOGRADOURO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input id="rua" disabled placeholder="Ex: Rua Brilhante" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="complemento" class="col-sm-2 col-form-label">COMPLEMENTO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input id="complemento" name="complemento" placeholder="Ex: Casa, Quadra" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="numero" class="col-sm-2 col-form-label">N°:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input  type="number" id="numero" name="numero" placeholder="Ex: 716" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="bairro" class="col-sm-2 col-form-label">BAIRRO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input id="bairro" disabled class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="localidade" class="col-sm-2 col-form-label">LOCALIDADE:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input id="localidade" disabled class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="uf" class="col-sm-2 col-form-label">UF:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <input id="uf" disabled placeholder="Ex: PR" class="form-control">
                                </div>
                            </div>
                        </div>
                        <h3 class="subtitle">Adicionais</h3><br>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="tipo" class="col-sm-2 col-form-label">RENDA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input required class="form-control categoriaVal" id="renda" name="renda">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="tipo" class="col-sm-2 col-form-label">DEPENDENTES:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="dependentes" id="forma1" value="1">
                                        <label class="form-check-label" for="forma1">Sim &nbsp;</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" checked type="radio" name="dependentes" id="forma2" value="2">
                                        <label class="form-check-label" for="forma2">Nâo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 dependentes">
                                <label for="numeroDepen" class="col-sm-8 col-form-label">&nbsp;</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <select disabled id="numeroDepen" class="form-control categoriaVal" name="numeroDepen">
                                        <option disabled selected>N° de Dependentes</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="tipo" class="col-sm-2 col-form-label">CATEGORIA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                    </div>
                                    <input id="categoria" class="form-control" disabled placeholder="Ex: D - Alta Renda">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row nisdepen">
                            <div class="col-sm-2">
                                <label for="tipo" class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="input-group mb-3" id="nisdepen">

                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <script language="JavaScript">
        function formatar(mascara, documento){
            let i = documento.value.length;
            let saida = mascara.substring(0,1);
            let texto = mascara.substring(i)// Remove acentos

            if (texto.substring(0,1) != saida){
                documento.value += texto.substring(0,1);
            }
        }
        const replaceSpecialChars = (str) => {
            return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/([^\w]+|\s+)/g, '')
                .replace(/\-\-+/g, '')
                .replace(/(^-+|-+$)/, '');
        }
        $(document).ready(function(){
            $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
                $(".alert").slideUp(500);
            });
            $(function() {
                $('#renda').maskMoney({ allowNegative: true, thousands:'', affixesStay: false});
            })
            $('.categoriaVal').on('change keyup',function () {
                let final = percapita();
                changeCateg(final);
            });
            $('#searchcep').on('click', function () {
                let cep = $('#cep').val();
                $.ajax({
                    type: "GET",
                    url: "{{url('')}}/consumidores/searchcep/"+cep,
                    success: function( data )
                    {
                        $('#rua').val(data['rua']);
                        $('#bairro').val(data['bairro']);
                        $('#localidade').val(data['localidade']);
                        $('#uf').val(data['uf']);
                    }
                });
            });
            $('input[name="dependentes"]').change(function () {
                hideshow();
            });
        });
        // Configure a few settings and attach camera
        function configure(){
         Webcam.set({
          width: 320,
          height: 240,
          image_format: 'jpeg',
          jpeg_quality: 90
         });
         Webcam.attach( '#my_camera' );
        }

        function take_snapshot() {

            // take snapshot and get image data
            Webcam.snap( function(data_uri) {
                $("#results").html('<img id="imageprev" src="'+data_uri+'"/>');
                $('#imagemconsumer').html('<input hidden type="text" value="'+data_uri+'" id="base_img" name="photo"/>');
            });
        }
        function hideshow(){
            if($('input[name="dependentes"]:checked').val() == 1){
                $( "#numeroDepen" ).prop( "disabled", false);
            }
            else if($('input[name="dependentes"]:checked').val() == 2){
                $( "#numeroDepen" ).prop( "disabled", true );
                let percapita = $('#renda').val();
                changeCateg(percapita);
            }
        }
        function percapita(){
            let renda = $('#renda').val();
            let dependentes = $('#numeroDepen').val();
            if(dependentes == null || dependentes == 0){
                dependentes = 1;
            }
            else{
                dependentes++;
            }
            return renda/dependentes;
        }
        function changeCateg(final){
            $.ajax({
                type: "GET",
                url: "{{url('')}}/consumidores/searchcateg/"+final,
                success: function( data )
                {
                    $("#categoria").val(data);
                }
            });
        }
       </script>
@endsection
