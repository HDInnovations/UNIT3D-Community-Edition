@extends('errors.layout')

@section('title', 'Error 503: Service Unavailable!')

@section('description', $exception->getMessage() ?: 'Sorry, we are doing some maintenance. Please check back soon.')
