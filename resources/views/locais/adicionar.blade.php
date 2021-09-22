@extends('layouts.default')
@section('content')
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
    <script>
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
    </script>
    <div class="row">
        <div class="login-wrap">
            <div class="login-content" id="contentLogin">
                <div class="login-logo">
                    <h1 class="text-center">Adicionar Local</h1>
                </div>
                <hr>
                <div class="login-form">
                    <div class="col-12">
                        <form method="POST" action="{{route('createlocal')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="produto" class="col-sm-2 col-form-label">NOME:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-building"></i></span>
                                        </div>
                                        <input id="produto" class="form-control" name="name">
                                    </div>
                                    <small class="form-text text-muted">Coloque o nome do Local</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="money" class="col-sm-2 col-form-label">EMAIL:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                        </div>
                                        <input id="money" class="form-control" name="email">
                                    </div>
                                    <small class="form-text text-muted">Coloque e-mail do Local</small>
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
@endsection
