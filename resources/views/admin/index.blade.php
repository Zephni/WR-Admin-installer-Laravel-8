@extends('admin.layouts.admin')

@section('content')

<h2>{{ config('app.name') }}</h2>
<hr>
<div class="row">
    <div class="col-md-12 mb-3">
        <h3>How to use</h3>
        <p>Find the navigation on the left hand panel, this can be collpased with the X button for smaller screens or devices.</p>
        <p>Under the 'Content Managment' tab you will find the editable sections for the frontend of this website, by clicking an option it will
            take you to the browse page where you can see a list of and search all of the content you have created within that section.</p>
        <p>If you click the small drop down icon next to the option, you can create a new one. This can also be done on the browse page.</p>
        
        <div class="form-text text-muted alert alert-info">
            <b class="text-primary">Note:</b>
            Blue shaded boxes like these will give helpfull information on different pages, sections and fields. So look out for these if you get stuck!
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <h3>Managing your account</h3>
        <p> If you need to manage your profile, or change your password you can go to <a href="{{ route('admin-manage-account') }}" target="_blank">
            Manage account</a> in the sidebar, you can set a profile image, change your username, email and password there.</p>
    </div>
</div>

{{-- <h5 class="mt-5">Content statistics</h5> --}}
{{-- <hr> --}}
{{-- <div class="row"> --}}
    {{-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"> --}}
        {{--  --}}
    {{-- </div> --}}
{{-- </div> --}}

<hr>
    
<footer class="text-center mt-5">
    <div class="mb-2">
    <small>
        © 2020 made with <i class="fa fa-heart" style="color:red"></i> by - <a href="https://github.com/Zephni" target="_blank">Craig Dennis</a>
    </small>
    </div>
    <div>
    <a href="https://github.com/Zephni" target="_blank">
        <img alt="GitHub followers" src="https://img.shields.io/github/followers/azouaoui-med?label=github&style=social" />
    </a>
    <a href="https://twitter.com/_Zephni" target="_blank">
        <img alt="Twitter Follow" src="https://img.shields.io/twitter/follow/azouaoui_med?label=twitter&style=social" />
    </a>
    </div>
</footer>

@endsection
