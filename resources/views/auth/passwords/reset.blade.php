@extends('layouts.app')

@section('content')
<Redefinirsenha token-route="{{$token}}" token="{{ csrf_token() }}"></Redefinirsenha>

@endsection
