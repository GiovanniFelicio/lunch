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
                    <h1 class="text-center">Gerador de Relatórios</h1>
                </div>
                <hr>
                @if (count($errors) > 0)
                    <div class = "alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="login-form">
                    <div class="col-12">
                        <form id="formName" method="POST" action="{{route('generate')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="produto" class="col-sm-3 col-form-label">Tipo:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <select class="form-control col-xs-12" id="select-tipo" name="tipo">
                                            <option value="" disabled selected> Selecione o Tipo</option>
                                            <option value="1">Vendas</option>
                                            <option value="2">Dados</option>
                                        </select>
                                    </div>
                                    <small class="form-text text-muted">Coloque qual será o relatório</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="money" class="col-sm-3 col-form-label">Categoria:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <select id="categoria" name="categoria" class="form-control">
                                            <option selected disabled="disabled">Selecione: </option>
                                            <option value="{{encrypt(0)}}">Todas</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{$categoria['id']}}">{{$categoria['nome']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="form-text text-muted">Coloque a categoria de filtro do relatório</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="money" class="col-sm-3 col-form-label">Período:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <select id="filtrarPor" name="filtroPor" class="form-control">
                                            <option selected disable0d="disabled">Selecione: </option>
                                            <option value="1">Dia</option>
                                            <option value="2">Mês</option>
                                            <option value="3">Ano</option>
                                        </select>
                                    </div>
                                    <small class="form-text text-muted">Coloque período de filtro do relatório</small>
                                </div>
                            </div>
                            <div id="tipofiltro" class="form-group row">

                            </div>
                            <div class="form-group row">
                                <label for="produto" class="col-sm-3 col-form-label">Clientes:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <select class="form-control col-xs-12" id="select-client" name="cliente">
                                            <option value="" disabled> Selecione o Cliente</option>
                                            <option selected value="{{encrypt(0)}}">Todos</option>
                                            @foreach ($consumers as $consumer)
                                                <option value="{{encrypt($consumer->id)}}">{{$consumer->name}} - {{$consumer->cpf}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Gerar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
 
            $(".alert").fadeTo(4200, 800).slideUp(1000, function(){
                $(".alert").slideUp(500);
            });
            $('#select-client').selectize({
                create: true,
                sortField: 'text'
            });
            $('#select-tipo').selectize({
                create: true,
                sortField: 'text'
            });
            $("#procura").on("keyup change", function() {
                var value = $(this).val().toLowerCase();
                $("#mytable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $('#filtrarPor').change(function () {
                let value = $('#filtrarPor').val();
                switch (value) {
                    case '1':
                        $('#tipofiltro').html(' ');
                        $('#tipofiltro').html('' +
                            '<label for="start" class="col-sm-4 col-form-label">Dia:</label>\n' +
                            '<div class="input-group mb-3 col-sm-8">'+
                                '<div class="input-group-prepend">'+
                                    '<input type="date" class="form-control col-12" name="day">'+
                            '</div>');
                        break;
                    case '2':
                        $('#tipofiltro').html(' ');
                        $('#tipofiltro').html('' +
                            '<label for="start" class="col-sm-4 col-form-label">Mês:</label>\n' +
                            '<div class="input-group mb-3 col-sm-8">'+
                                '<div class="input-group-prepend">'+
                                    '<input type="month" class="form-control col-12" name="month">'+
                            '</div>');
                        break;
                    case '3':
                        $('#tipofiltro').html(' ');
                        $('#tipofiltro').html('' +
                            '<label for="start" class="col-sm-4 col-form-label">Ano:</label>\n' +
                            '<div class="input-group mb-3 col-sm-8">'+
                                '<div class="input-group-prepend">'+
                                    '<input type="number" class="form-control col-12" name="year" min="2000" max="2020" value="2020">'+
                            '</div>');
                        break;
                }
            });
        });
        
    </script>
@endsection

