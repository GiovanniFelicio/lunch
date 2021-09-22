@extends("layouts.default")
@section("content")
    <link href="{{asset('css/dataTable/jquery.dataTables.min.css')}}" rel="stylesheet">
    @include('flash::message')
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
    <hr>
    <h2 style="text-align: center">Últimos Acessos</h2>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="table-data__tool">
                <div class="table-data__tool-right">
                    <a id="back" class="au-btn au-btn-icon au-btn--blue au-btn--small" href="{{route("start")}}"><b>&#8592;</b></a>
                </div>
            </div>
        </div>
    </div>
    <style>
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
        tbody tr td{
            font-size: 14px;
            font-family: "Bradley Hand", cursive;
        }
        thead tr th{
            font-size: 20px;
            font-family: "Arial Black";
        }
        #cnpj{
            color: #3f5efb;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive table-responsive-data2">
                <div class="col-lg-12">
                    <input class="col-md-2" id="myInput" type="text" placeholder="Search..">
                </div>
                <table id="myTable" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>E-mail</th>
                        <th>Acesso</th>
                        <th>Ip</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($allLogins as $logins)
                        <tr class="tr-shadow">
                            <td>{{$logins->name}}</td>
                            <td>
                                <span class="block-email">{{$logins->email}}</span>
                            </td>
                            @if($date['hours'] == ($logins->last_login_atHr) and $date['mday'] == $logins->last_login_atDay and $date['mon'] == $logins->last_login_atMonth and $date['year'] == $logins->last_login_atYear)
                                @if($date['minutes'] - $logins->last_login_atMin == 0)
                                    <td>Agora Mesmo</td>
                                @elseif($date['minutes'] - $logins->last_login_atMin == 1)
                                    <td>{{$date['minutes'] - $logins->last_login_atMin}} Minuto atrás</td>
                                @else
                                    <td>{{$date['minutes'] - $logins->last_login_atMin}} Minutos atrás</td>
                                @endif
                            @elseif($date['mday'] == $logins->last_login_atDay and $date['mon'] == $logins->last_login_atMonth and $date['year'] == $logins->last_login_atYear)
                                @if($date['hours'] - $logins->last_login_atHr == 0)
                                    <td>1 Hora atrás</td>
                                @else
                                    <td>{{$date['hours'] - ($logins->last_login_atHr) + 1}} Horas atrás</td>
                                @endif
                            @elseif($date['mon'] == $logins->last_login_atMonth and $date['year'] == $logins->last_login_atYear)
                                @if(($date['mday'] - $logins->last_login_atDay) == 0)
                                    <td>1 Dia atrás</td>
                                @elseif(($date['mday'] - $logins->last_login_atDay) < 7 and ($date['mday'] - $logins->last_login_atDay) > 0)
                                    @if(date("l", mktime(0, 0, 0, $logins->last_login_atMonth, $logins->last_login_atDay, $logins->last_login_atYear)) == 'Monday')
                                        <td>Segunda-Feira às {{($logins->last_login_atHr-3).'h'.$logins->last_login_atMin.'min'}}</td>
                                    @elseif(date("l", mktime(0, 0, 0, $logins->last_login_atMonth, $logins->last_login_atDay, $logins->last_login_atYear)) == 'Tuesday')
                                        <td>Terça-Feira às {{($logins->last_login_atHr-3).'h'.$logins->last_login_atMin.'min'}}</td>
                                    @elseif(date("l", mktime(0, 0, 0, $logins->last_login_atMonth, $logins->last_login_atDay, $logins->last_login_atYear)) == 'Wednesday')
                                        <td>Quarta-Feira às {{($logins->last_login_atHr-3).'h'.$logins->last_login_atMin.'min'}}</td>
                                    @elseif(date("l", mktime(0, 0, 0, $logins->last_login_atMonth, $logins->last_login_atDay, $logins->last_login_atYear)) == 'Thursday')
                                        <td>Quinta-Feira às {{($logins->last_login_atHr-3).'h'.$logins->last_login_atMin.'min'}}</td>
                                    @elseif(date("l", mktime(0, 0, 0, $logins->last_login_atMonth, $logins->last_login_atDay, $logins->last_login_atYear)) == 'Friday')
                                        <td>Sexta-Feira às {{($logins->last_login_atHr-3).'h'.$logins->last_login_atMin.'min'}}</td>
                                    @elseif(date("l", mktime(0, 0, 0, $logins->last_login_atMonth, $logins->last_login_atDay, $logins->last_login_atYear)) == 'Saturday')
                                        <td>Sábado às {{($logins->last_login_atHr-3).'h'.$logins->last_login_atMin.'min'}}</td>
                                    @elseif(date("l", mktime(0, 0, 0, $logins->last_login_atMonth, $logins->last_login_atDay, $logins->last_login_atYear)) == 'Sunday')
                                        <td>Domingo às {{($logins->last_login_atHr-3).'h'.$logins->last_login_atMin.'min'}}</td>
                                    @endif
                                @else
                                    <td>{{$date['mday'] - $logins->last_login_atDay}} Dias atrás</td>
                                @endif
                            @elseif($date['year'] == $logins->last_login_atYear)
                                @if(($date['mon'] - $logins->last_login_atMonth) == 1)
                                    <td>1 Mês atrás</td>
                                @else
                                    <td>{{$date['mon'] - ($logins->last_login_atMonth)}} Meses atrás</td>
                                @endif
                            @else
                                @if(($date['year'] - $logins->last_login_atYear) == 1)
                                    <td>1 Ano atrás</td>
                                @else
                                    <td>{{$date['year'] - ($logins->last_login_atYear)}} Anos atrás</td>
                                @endif
                            @endif
                            <td>{{$logins->last_login_ip}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$allLogins->links()}}
            </div>
        </div>
    </div>
    <hr>



    <style>
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <script src="{{asset('js/dataTable/jquery-3.3.1.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/dataTable/jquery.dataTables.min.js')}}" type="text/javascript"></script>
@endsection

