@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
{{-- @section('message', __('Server Error')) --}}
@section('message', 'Terjadi Kendala. Mohon Kontak Admin pindayapp@gmail.com.')
