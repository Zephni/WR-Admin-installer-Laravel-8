@extends('admin.layouts.admin')

@section('content')

<style>
    .documentation-content p {
        font-size: 1.1rem;
    }   

    .documentation-content kbd {
        display: inline-block;
        padding: 2px 8px 3px 8px;
    } 
</style>

<h1>{{ $heading ?? 'Documentation' }}</h1>
@if($subject != '')
    <a href='/admin/documentation'><i class="fas fa-book mr-1"></i> index</a>
    / {{ $subject }}
@endif
<hr>
<br />

<div class="documentation-content">
    {{ Illuminate\Mail\Markdown::parse($content) }}
</div>

@endsection
