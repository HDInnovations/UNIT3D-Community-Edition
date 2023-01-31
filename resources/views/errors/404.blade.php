@extends('errors.layout')

@section('title', 'Error 404: Page Not Found')

@section('description', $exception->getMessage() ?: 'The requested page cannot be found! Not sure what you\'re looking for but check the address and try again!')
