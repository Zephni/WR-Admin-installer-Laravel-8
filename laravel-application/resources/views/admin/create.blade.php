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

            <h2>Creating {{ $tableAlias }}</h2>
            <hr>

            <form name="{{ $tableName }}Form" id="{{ $tableName }}Form" method="post" enctype="multipart/form-data" action="{{ route('admin-create-action', ['table' => $tableName]) }}">
                @csrf
                <input type="hidden" name="modelRef" value="{{ $modelRef }}">

                @foreach($tableFields as $k => $v)
                
                    {!! $v->Render() !!}

                @endforeach

                <button class="btn btn-lg btn-primary my-3" type="submit">Create {{ $tableAlias }}</button>

            </form>

            <br /><br /><br />
            <br /><br /><br />

        </div>
    </div>

@endsection