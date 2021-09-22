@extends('layouts.default')
@section('content')
<br>
    <h2 class="text-center">Dados da Secretaria</h2>
    <div class="row">
        <div class="col-sm-12 text-center">
            <br><br>
            <div class="table-responsive m-b-30">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nome da Sec/Aut:</th>
                            <td>{{$local->name}}</td>
                        </tr>
                        <tr>
                            <th>E-mail do Local</th>
                            <td>{{$local->email}}</td>
                        </tr>
                        <tr>
                            <th>Data de Criação</th>
                            <td>{{\Carbon\Carbon::parse($local->created_at)->format('d/m/Y')}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
        </div>
    </div>
    <br>
    <h2 class="text-center">Gerenciamento de Permissões</h2>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <table style="width: 100%" id="tableAuths" class="tableStyleGio datatablesStyle display nowrap">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Local</th>
                    <th>Opções</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <br><br>
    <h2 class="text-center">Designação de Locais</h2>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <table style="width: 100%" id="tableAuthsBairros" class="tableStyleGio datatablesStyle display nowrap">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Opções</th>
                </tr>
                </thead>
            </table>
            <br><br><br>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableAuths').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("getdataAdms", encrypt($local->id))}}',
                "columns":[
                    {"data": 'name'},
                    {"data": 'local'},
                    {"data": 'action'}

                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
            $('#tableAuths').on('change', '.selectStaffs', function () {
                let val = this.value;
                let idEncrypt = $(this).data('id');
                let CSRF_TOKEN = '{{csrf_token()}}';
                $.ajax({
                    url: '{{route("changerole")}}',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, usuario:idEncrypt, role:val},
                    dataType: 'JSON',
                    success: function (data) { 
                        if(data == 0){
                            alert('Erro ao fazer a atualização de permissão !!');
                        }
                        else if(data != 1){
                            alert(data);
                        }
                    }
                }); 
            });

            $('#tableAuthsBairros').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("getdataBairros", encrypt($local->id))}}',
                "columns":[
                    {"data": 'nome'},
                    {"data": 'action'}

                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });

            $('#tableAuthsBairros').on('click', '.toggleAuth', function () {
                let val = this.checked ? this.value : 'off';
                let idEncrypt1 = $(this).data('id');
                let CSRF_TOKEN1 = '{{csrf_token()}}';
                let Local = '{{encrypt($local->id)}}';
                if(val == 'on'){
                    $.ajax({
                        url: '{{route("authBairro")}}',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN1, bairro:idEncrypt1, local:Local},
                        dataType: 'JSON',
                        success: function (data) { 
                            if(data == 2){
                                alert('Não foi possível autorizar !!');
                            }
                            else if(data == 1){
                                //
                            }
                            else{
                                alert(data);
                            }
                        }
                    }); 
                }
                else if(val == 'off'){
                    $.ajax({
                        url: '{{route("disallowanceBairro")}}',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN1, bairro:idEncrypt1, local:Local},
                        dataType: 'JSON',
                        success: function (data){
                            if(data == 2){
                                alert('Não foi possível desautorizar !!');
                            }
                            else if(data == 1){
                                //
                            }
                            else{
                                alert(data);
                            }
                        }
                    }); 
                }
            });

        });

    </script>

@endsection
