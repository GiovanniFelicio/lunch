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
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <div class="login-logo">
                    <h1>Adicionar Usuário</h1>
                </div>
                
                <div class="login-form">
                    <form method="POST" action="{{route('saveUser')}}">
                        @csrf
                        <hr>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label for="produto" class="col-sm-2 col-form-label">NOME:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input id="produto" class="form-control" type="text" required name="name" placeholder="Ex: Lucas Silva" autofocus>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="local" class="col-sm-2 col-form-label">LOCAL:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                    </div>
                                    <select id="local" name="local" required  class="form-control">
                                        <option selected>Selecione o Local</option>
                                        @foreach($locais as $local)
                                            <option value="{{encrypt($local->id)}}">{{$local->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-lg-5">
                                <label for="produto" class="col-sm-2 col-form-label">EMAIL:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input required class="form-control " name="email" placeholder="Ex: lucas@exemplo.com" type="email">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="produto" class="col-sm-2 col-form-label">MATRÍCULA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-registered"></i></span>
                                    </div>
                                    <input required class="form-control " name="matricula" placeholder="Ex: 11.111-1" type="text">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="produto" class="col-sm-2 col-form-label">PERMISSÃO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                    </div>
                                    <select required id="level" name="level"  class="form-control">
                                        <option value="0">Estabelecimento</option>
                                        <option value="1">Moderador</option>
                                        <option value="2">Administrador</option>
                                    </select>
                                </div>
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
@endsection