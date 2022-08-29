@extends('errors.layout')

@section('title', 'Error 405: Method Not Allowed!')

@section('description', $exception->getMessage() ?: 'The method you are trying is use, is not allowed by this server.')
