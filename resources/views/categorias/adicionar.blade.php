@extends('layouts.default')
@section('content')
    <script src="{{asset('js/jquery.maskMoney.js')}}" type="text/javascript"></script>
    @if(session('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
    <div class="row">
        <div class="login-wrap">
            <div class="login-content" id="contentLogin">
                <div class="login-logo">
                    <h1 class="text-center">Adicionar Categoria</h1>
                </div>
                <hr>
                <div class="login-form">
                    <div class="col-12">
                        @if (count($errors) > 0)
                            <div class = "alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif  
                        <form method="POST" action="{{route('novacategoria')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="produto" class="col-sm-3 col-form-label">NOME:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-building"></i></span>
                                        </div>
                                        <input id="produto" class="form-control" name="namecateg">
                                    </div>
                                    <small class="form-text text-muted">Coloque o nome da categoria</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="produto" class="col-sm-3 col-form-label">DESCRIÇÃO:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-building"></i></span>
                                        </div>
                                        <input id="produto" class="form-control" name="descricao">
                                    </div>
                                    <small class="form-text text-muted">Coloque o descrição da categoria</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="money" class="col-sm-3 col-form-label">VALOR:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$$</span>
                                        </div>
                                        <input id="money" class="form-control" name="preco">
                                    </div>
                                    <small class="form-text text-muted">Coloque valor da categoria</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="forma" id="forma1" value="1">
                                            <label class="form-check-label" for="forma1">Faixa de Valor &nbsp;</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="forma" id="forma2" value="2">
                                            <label class="form-check-label" for="forma2">Valor Acima</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Faixa de Preço</small>
                                </div>
                            </div>
                            <div class="form-group row faixa">
                                <label for="money" class="col-sm-3 col-form-label">PER CAPITA:</label>
                                <div class="col-lg-4">
                                    <label for="inicial" class="col-lg-3 col-form-label">De:</label>
                                    <div class="input-group mb-3">
                                        <input id="inicial" class="form-control money" name="inicial">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <label for="inicial" class="col-lg-3 col-form-label">Até:</label>
                                    <div class="input-group mb-3">
                                        <input id="final" class="form-control money" name="final">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row acima">
                                <label for="money" class="col-sm-3 col-form-label">PER CAPITA:</label>
                                <div class="acima">
                                    <div class="col-lg-9">
                                        <label for="inicial" class="col-lg-12 col-form-label">Acima de:</label>
                                        <div class="input-group mb-3">
                                            <input id="acima" class="form-control money" name="acima">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <script>
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
        $(function() {
            $('#inicial').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'', affixesStay: false});
        })
        $(function() {
            $('#final').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'', affixesStay: false});
        })
        $(function() {
            $('#acima').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'', affixesStay: false});
        })
        $(document).ready(function(){
            $('.acima').hide();$('.faixa').hide();
            $( "#inicial" ).prop( "disabled", true);$( "#final" ).prop( "disabled", true);
            $( "#acima" ).prop( "disabled", true);
            $('input[name="forma"]').change(function () {
                hideshow();
            });
        });
        function hideshow(){
            if($('input[name="forma"]:checked').val() == 1){
                $('.faixa').show();$( "#inicial" ).prop( "disabled", false);$( "#final" ).prop( "disabled", false);
                $('.acima').hide();$( "#acima" ).prop( "disabled", true );
                quantidade();
            }
            else if($('input[name="forma"]:checked').val() == 2){
                $('.faixa').hide();$( "#inicial" ).prop( "disabled", true);$( "#final" ).prop( "disabled", true);
                $('.acima').show();$( "#acima" ).prop( "disabled", false );
                valorpagar();
            }
        }
    </script>
@endsection
