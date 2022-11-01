@extends('layouts.app')
@section('content')
    <contas token="{{ csrf_token() }}"></contas>
@endsection