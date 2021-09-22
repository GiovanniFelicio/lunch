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
        <div class="col-md-12 text-right">
            <a href="{{route('adicionarlocais')}}" style="color: white" class="au-btn au-btn-icon btn btn-info">
                <i class="zmdi zmdi-plus"></i>add local</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center fachada">Locais</h2>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <div>
                <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle display nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <br>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdatalocais')}}',
                "columns":[
                    {"data": 'id'},
                    {"data": 'name'},
                    {"data": 'email'},
                    {"data": 'action'}
                ],
                "lengthMenu": [[5, 10, 25, 100, -1], [5, 10, 25, 100, "All"]],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
        });
    </script>

@endsection

