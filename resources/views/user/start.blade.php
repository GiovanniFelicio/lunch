@extends("layouts.default")
@section("content")
    <style>
        #lastLogin{
            font-family: 'Times New Roman';
            font-style: italic;
        }
        #nameUser{
            color: #000000;
            font-family: 'Arial Black', cursive;
        }
        #titlePage{
            color: #3d4146;
        }
    </style>
    <hr>
    <h1 id="titlePage" style="text-align: center; padding-top: 10px">Bem-Vindo(a) ao SysLunch <span id="nameUser">{{$name}}!</span></h1>
    <hr>
    <div style="text-align: center">
        <img src="{{asset('images/fundetecLogo2.png')}}" alt="logo-fundetec">
    </div>
    <hr>

@endsection
