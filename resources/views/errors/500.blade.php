@extends('errors.layout')
@section('code', '500')
@section('message', app()->getLocale() === 'ar' ? 'حدث خطأ ما' : 'Something Went Wrong')
