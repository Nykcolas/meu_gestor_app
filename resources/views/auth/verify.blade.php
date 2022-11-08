@extends('layouts.app')

@section('content')
    <Verificar token="{{ csrf_token() }}"></Verificar>
@endsection
