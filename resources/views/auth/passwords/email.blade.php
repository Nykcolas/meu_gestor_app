@extends('layouts.app')

@section('content')
<Email token="{{ csrf_token() }}"></Email>
@endsection
