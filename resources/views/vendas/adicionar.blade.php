@extends('layouts.default')
@section('content')
    <link href="{{asset('css/selectize.bootstrap3.css')}}" rel="stylesheet" media="all">
    <script src="{{asset("js/selectize.js")}}"></script>
    <script src="{{asset("js/selectize-standalone.js")}}"></script>
    <script src="{{asset('js/jquery.maskMoney.js')}}" type="text/javascript"></script>
    <style>
        .select2-container {
            width: 100% !important;
            padding: 0;
        }
    </style>
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
    <div class="row">
        <div class="login-wrap">
            <div class="login-content" id="contentLogin">
                <div class="login-logo">
                    <h1 class="text-center">Área de Venda</h1>
                </div>
                <hr>
                <div class="login-form">
                    <div class="col-12">
                        <form id="formName" method="POST" action="{{route('novavenda')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="produto" class="col-sm-3 col-form-label">NOME:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <select class="form-control col-xs-12" id="cliente" name="cliente">
                                            <option value="" disabled selected> Selecione o Cliente</option>
                                            @foreach ($consumers as $consumer)
                                                <option value="{{$consumer->reference}}">{{$consumer->name}} - {{$consumer->cpf}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="form-text text-muted">Selecione ou digite o nome do cliente</small>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row"></div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" checked type="radio" name="forma" id="forma1" value="1">
                                            <label class="form-check-label" for="forma1">Quantidade &nbsp;</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="forma" id="forma2" value="2">
                                            <label class="form-check-label" for="forma2">Valor a Pagar</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Selecione por Quantidade ou Valor a pagar</small>
                                </div>
                            </div>
                            <div class="form-group row quant">
                                <label for="quantidade" class="col-sm-4 col-form-label">QUANTIDADE:</label>
                                <div class="col-sm-8">
                                    <div class="input-group mb-3">
                                        <input id="quantidade" class="form-control" type="number" name="quant">
                                    </div>
                                    <small class="form-text text-muted">Coloque a quantidade</small>
                                </div>
                            </div>
                            <div class="form-group row pagar">
                                <label for="valorpagar" class="col-sm-4 col-form-label">VALOR A PAGAR:</label>
                                <div class="col-sm-8">
                                    <div class="input-group mb-3">
                                        <input disabled id="valorpagar" class="form-control" type="text" name="quant">
                                    </div>
                                    <small class="form-text text-muted">Coloque o valor a pagar</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total" class="col-sm-4 col-form-label">TOTAL:</label>
                                <div class="col-sm-8">
                                    <div class="input-group mb-3">
                                        <input disabled class="form-control" type="text" id="total">
                                    </div>
                                    <small class="form-text text-muted">Aqui será mostrado o total</small>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.pagar').hide();
        $(document).ready(function() {

            $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
                $(".alert").slideUp(500);
            });
            $('#cliente').selectize({
                create: true,
                sortField: 'text'
            });
            $(function() {
                $('#valorpagar').maskMoney({allowNegative: true, thousands:'', affixesStay: false});
            })
            $("#procura").on("keyup change", function() {
                var value = $(this).val().toLowerCase();
                $("#mytable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $('input[name="forma"]').change(function () {
                hideshow();
            });
            $('#cliente').change(function () {
                if($('input[name="forma"]:checked').val() == 1){
                    quantidade();
                }
                else if($('input[name="forma"]:checked').val() == 2){
                    valorpagar();
                }
            });
            $('#quantidade').on('keyup keypress change', function(){
                quantidade();
            });
            $('#valorpagar').on('keyup keypress change', function(){
                valorpagar();
            });
        });
        function quantidade(){
            let idEncrypt = $('#cliente').val();
            $.ajax({
                type: "GET",
                url: "{{url('')}}/categorias/search/"+idEncrypt+'/1',
                success: function( data )
                {
                    if($.isNumeric(data)){
                        let final = $('#quantidade').val()*data;
                        $('#total').val('R$ '+parseFloat(final.toFixed(2)));
                    }
                    else{
                        alert('Erro de valor do produto');
                    }
                }
            });
        }
        function valorpagar(){
            let idEncrypt = $('#cliente').val();
            $.ajax({
                type: "GET",
                url: "{{url('')}}/categorias/search/"+idEncrypt+'/1',
                success: function( data )
                {
                    if($.isNumeric(data)){
                        let final = $('#valorpagar').val()/data;
                        $('#total').val(parseFloat(final.toFixed(2)) + ' de créditos');
                    }
                    else{
                        alert('Erro de valor do produto');
                    }
                }
            });
        }
        function hideshow(){
            if($('input[name="forma"]:checked').val() == 1){
                $('.quant').show();$( "#quantidade" ).prop( "disabled", false);
                $('.pagar').hide();$( "#valorpagar" ).prop( "disabled", true );
                quantidade();
            }
            else if($('input[name="forma"]:checked').val() == 2){
                $('.quant').hide();$( "#quantidade" ).prop( "disabled", true );
                $('.pagar').show();$( "#valorpagar" ).prop( "disabled", false );
                valorpagar();
            }
        }
    </script>
@endsection

