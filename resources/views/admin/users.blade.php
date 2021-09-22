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
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
    </script>
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{route('register')}}" style="color: white" class="au-btn au-btn-icon btn btn-info">
                <i class="zmdi zmdi-plus"></i>add usuário</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <h1 class="fachada text-center">Usuários</h1>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <div>
                <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle display nowrap func">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Local</th>
                        <th>E-mail</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
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
                    <input type="hidden" value="" name="idFunc" class="idFunc">
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
                    <form method="POST" action="" id="updateFunc">
                        @csrf
                        <input id="reference" hidden name="reference">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Nome do Funcionário</label>
                                <input class="au-input au-input--full" id="nameFunc" type="text" name="nameFunc" placeholder="Ex: Lucas Silva" autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Local</label>
                                        <select id="local" name="local" required  class="form-control">
                                            <option selected>Selecione o Local</option>
                                            @foreach($locais as $local)
                                                <option value="{{encrypt($local->id)}}">{{$local->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label>E-mail</label>
                                <input class="au-input au-input--full" id="inputEmail" disabled placeholder="Ex: lucas@exemplo.com" type="email">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Matricula</label>
                                <input required class="au-input au-input--full" id="inputMatricula" name="matricula" placeholder="Ex: 11.111-1" type="text">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Permissão</label>
                                <select required id="level" name="level"  class="form-control">
                                    <option value="1">Moderador</option>
                                    <option value="2">Administrador</option>
                                </select>
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
                    <img src="{{asset('images/error.png')}}" alt="Erro">
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            
            let idEncrypt;

            $('.func').on('click', '.del', function () {

                idEncrypt = $(this).data('id');
                $('#myModal').modal('show');
            });

            $('.yes').on('click', function () {
                window.location.replace("{{url('/admin/delete')}}" + '/'+idEncrypt);
            });

            let table = $('#myTable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdatausers')}}',
                "columns":[
                    {"data": 'id'},
                    {"data": 'name'},
                    {"data": 'local'},
                    {"data": 'email'},
                    {"data": 'action'}
                ],
                "lengthMenu": [[5, 10, 25, 100, -1], [5, 10, 25, 100, "All"]],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
            $('#myTable tbody').on('dblclick', 'tr', function () {
                let dato = table.row( this ).data();
                $.ajax({
                    type: "GET",
                    url: "{{url('')}}/admin/searchuser/"+dato['idd'],
                    success: function( data )
                    {
                        $('#nameFunc').val(data['func']);
                        $('#inputEmail').val(data['email']);
                        $('#inputMatricula').val(data['matricula']);
                        $('#level').val(data['level']);
                        $('#local option:contains(' + data['local'] + ')').attr('selected', 'selected');
                        $('#reference').val(dato['idd']);
                    }
                });
                $('#myModalEdit').modal('show');
            });
            $('#updateFunc').submit(function(){
                var dados = $( this ).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{route('updateUser')}}",
                    data: dados,
                    success: function(data){
                        if (data == 1){
                            $('#myModalEdit').modal('hide');
                            $('#sucesso').modal('show');
                            $('#myTable').DataTable().ajax.reload();
                        }
                        else if(data == 2){
                            $('#myModalEdit').modal('hide');
                            $('#erro').modal('show');
                        }
                        else{
                            alert(' Error no Sistema !! ')
                        }
                    }
                });

                return false;
            });
        });
    </script>
@endsection

