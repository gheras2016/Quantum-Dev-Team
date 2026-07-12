@extends('errors.layout')
@section('code', '404')
@section('message', app()->getLocale() === 'ar' ? 'الصفحة غير موجودة' : 'Page Not Found')
