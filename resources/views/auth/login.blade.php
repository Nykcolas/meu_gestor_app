@extends('layouts.app')

@section('content')
    <Login token="{{ csrf_token() }}"></Login>
@endsection
