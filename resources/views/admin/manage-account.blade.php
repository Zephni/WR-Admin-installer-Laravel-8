@extends('admin.layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="my-0 py-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ str_replace('ufield ', '', $error) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <h2>Manage account</h2>
            <hr>

            <form name="{{ $tableName }}Form" id="{{ $tableName }}Form" method="post" enctype="multipart/form-data" action="{{ route('admin-manage-account-action') }}">
                @csrf
                <input type="hidden" name="modelRef" value="{{ $modelRef }}">
                <input type="hidden" name="id" value="{{ $id }}">

                @foreach($tableFields as $k => $v)
                
                    {!! $v->Render() !!}

                @endforeach

                <button class="btn btn-lg btn-primary my-3" type="submit">Update profile</button>

            </form>

            <br /><br /><br />
            <br /><br /><br />

        </div>
    </div>

@endsection