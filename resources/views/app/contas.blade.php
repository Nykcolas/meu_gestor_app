@extends('layouts.app')
@section('content')
    <Contas token="{{ csrf_token() }}"></Contas>
@endsection