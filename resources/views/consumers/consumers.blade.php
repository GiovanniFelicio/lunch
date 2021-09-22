@extends('layouts.default')
@section('content')
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
        $(".alert").fadeTo(5200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
    </script>
    @if(Auth::user()->level >= 3)
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{route('addconsumidor')}}" style="color: white" class="au-btn au-btn-icon btn btn-info">
                    <i class="zmdi zmdi-plus"></i>add consumidor</a>
            </div>
        </div>
    @endif
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center fachada">Consumidores</h2>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <div>
                <table style="width: 100%" id="table" class="consumer tableStyleGio datatablesStyle display nowrap">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>NIS</th>
                        <th>CPF</th>
                        <th>RG</th>
                        <th>Tipo</th>
                        <th>Opções</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <br>
    @if(Auth::user()->level >= 2)
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
                        Deseja deletar este Funcionario mesmo ?
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
                        <form method="POST" action="" id="updateCons">
                            @csrf
                            <input id="reference" hidden name="reference">
                            <label for="name" class="col-sm-2 col-form-label">NOME:</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input id="name" class="form-control" type="text" required name="name" placeholder="Ex: Lucas Silva" autofocus>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label for="cpf" class="col-sm-2 col-form-label">CPF:</label>
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                        </div>
                                        <input id="cpf" name="cpf" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="rg" class="col-sm-2 col-form-label">RG:</label>
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                        </div>
                                        <input id="rg" name="rg" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="produto" class="col-sm-12 col-form-label">NASCIMENTO:</label>
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input id="nasc" class="form-control" name="nasc" type="date">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label for="produto" class="col-sm-2 col-form-label">EMAIL:</label>
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">@</span>
                                        </div>
                                        <input id="email" name="email" type="email" class="form-control" placeholder="Ex: lucas@exemplo.com">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="categoria" class="col-sm-2 col-form-label">CATEGORIA:</label>
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                        </div>
                                        <input disabled class="form-control" id="categoria">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Atualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        $(document).ready(function () {
            let table = $('#table').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdataconsumers')}}',
                "columns":[
                    {"data": 'nome'},
                    {"data": 'nis'},
                    {"data": 'cpf'},
                    {"data": 'rg'},
                    {"data": 'categoria'},
                    {"data": 'action'}
                ],
                "lengthMenu": [[5, 10, 25, 100, -1], [5, 10, 25, 100, "All"]],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
            @if(Auth::user()->level >= 3)
                let idEncrypt;
                $('.consumer').on('click', '.del', function () {
                    idEncrypt = $(this).data('id');
                    $('#myModal').modal('show');
                });

                $('.yes').on('click', function () {
                    window.location.replace("{{url('/consumidores/delete')}}" + '/'+idEncrypt);
                });

                $('#table tbody').on('dblclick', 'tr', function () {
                    let dato = table.row( this ).data();
                    $.ajax({
                        type: "GET",
                        url: "{{url('')}}/consumidores/search/"+dato['idd'],
                        success: function( data )
                        {
                            $('#name').val(data['nome']);
                            $('#cpf').val(data['cpf']);
                            $('#rg').val(data['rg']);
                            $('#email').val(data['email']);
                            $('#nasc').val(data['nasc']);
                            $('#categoria').val(data['categoria']);
                            $('#reference').val(dato['idd']);
                        }
                    });
                    $('#myModalEdit').modal('show');
                });
                $('#updateCons').submit(function(){
                    var dados = $( this ).serialize();
                    $.ajax({
                        type: "POST",
                        url: "{{route('updateCons')}}",
                        data: dados,
                        success: function(data){
                            if (data == 1){
                                $('#myModalEdit').modal('hide');
                                $('#table').DataTable().ajax.reload();
                                alert('Sucesso !!')
                            }
                            else{
                                alert(data)
                            }
                        }
                    });
                    return false;
                });
            @endif
        });
    </script>

@endsection

