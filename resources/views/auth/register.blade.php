@extends('layouts.app')

@section('content')
<Registrar token="{{ csrf_token() }}"></Registrar>
@endsection
