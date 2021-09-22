@extends('layouts.default')
@section('content')
<script src="{{asset('js/jquery.maskMoney.js')}}" type="text/javascript"></script>
    <style>
        .modal{
            background-color: rgba(0, 0, 0, 0.5);
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
    <script>
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
    </script>
    @if(Auth::user()->level >= 3)
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{route('addcategorias')}}" style="color: white" class="au-btn au-btn-icon btn btn-info">
                    <i class="zmdi zmdi-plus"></i>add categoria</a>
            </div>
        </div>
    @endif
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center fachada">Controle de Categorias</h2>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <div>
                <table style="width: 100%" id="myTable" class="categoria tableStyleGio datatablesStyle display nowrap">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <br>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> DELETAR </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    Deseja deletar esta Categoria mesmo ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">NAO</button>
                    <button type="button" class="btn btn-primary yes">SIM</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> EDITAR </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updatecateg" method="POST" action="">
                        @csrf
                        <input hidden name="reference" id="reference">
                        <div class="form-group row">
                            <label for="produto" class="col-sm-3 col-form-label">NOME:</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input id="nome" disabled class="form-control">
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
                                    <input id="descricao" class="form-control" name="descricao">
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
                        <br>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sucesso" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col align-self-center">
                        <img src="{{asset('images/sucesso.png')}}" alt="Sucesso">
                    </div>
                    <div class="col align-self-center">
                        <h1 class="text-success align-items-center">Sucesso !!</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="erro" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col align-self-center">
                        <img src="{{asset('images/error.png')}}" alt="Erro">
                    </div>
                    <div class="col align-self-center">
                        <h1 class="text-success align-items-center" id="erromsg"></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#money').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'', affixesStay: false});
            let table = $('#myTable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("getdatacateg")}}',
                "columns":[
                    {"data": 'codigo'},
                    {"data": 'nome'},
                    {"data": 'descricao'},
                    {"data": 'preco'}
                ],
                "lengthMenu": [[5, 10, 25, 100, -1], [5, 10, 25, 100, "All"]],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
            let idEncrypt;

            $('.categoria').on('click', '.del', function () {

                idEncrypt = $(this).data('id');
                $('#myModal').modal('show');
            });

            $('.yes').on('click', function () {
                window.location.replace("{{url('/categorias/delete')}}" + '/'+idEncrypt);
            });

            $('#myTable tbody').on('dblclick', 'tr', function () {
                let dato = table.row( this ).data();
                $.ajax({
                    type: "GET",
                    url: "{{url('')}}/categorias/search/"+dato['reference']+'/2',
                    success: function( data )
                    {
                        if(data == 404){
                            alert('Dados não encontrado');
                        }
                        else{
                           $('#nome').val(data['nome']);
                            $('#descricao').val(data['descricao']);
                            $('#money').val(data['money']);
                            $('#reference').val(dato['reference']);
                        }
                    }
                });
                $('#myModalEdit').modal('show');
            });
            $('#updatecateg').submit(function(){
                var dados = $( this ).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{route('updatecateg')}}",
                    data: dados,
                    success: function(data){
                        if (data == 200){
                            $('#myModalEdit').modal('hide');
                            $('#sucesso').modal('show');
                            $('#myTable').DataTable().ajax.reload();
                        }
                        else if(data == 500){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Erro Interno do Servidor');
                            $('#erro').modal('show');
                        }
                        else if(data == 404){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Categoria não encontrada');
                            $('#erro').modal('show');
                        }
                        else if(data == 403){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Você não tem permissão');
                            $('#erro').modal('show');
                        }
                    }
                });

                return false;
            });
        });
    </script>

@endsection

