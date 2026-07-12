@extends('errors.layout')
@section('code', '503')
@section('message', app()->getLocale() === 'ar' ? 'الموقع تحت الصيانة' : 'Service Unavailable')
