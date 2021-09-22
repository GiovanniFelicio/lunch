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
            <a href="{{route('addvendas')}}" style="color: white" class="au-btn au-btn-icon btn btn-info">
                <i class="zmdi zmdi-plus"></i>add venda</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center fachada">Controle de Vendas</h2>
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
                            <th>N°</th>
                            <th>Consumidor</th>
                            <th>Categoria</th>
                            <th>Valor</th>
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
                "ajax": '{{route('getdatavendas')}}',
                "columns":[
                    {"data": 'id'},
                    {"data": 'consumidor'},
                    {"data": 'categ'},
                    {"data": 'preco'},
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

