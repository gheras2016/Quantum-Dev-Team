@extends('errors.layout')
@section('code', '403')
@section('message', app()->getLocale() === 'ar' ? 'ليست لديك صلاحية الوصول' : 'Access Denied')
