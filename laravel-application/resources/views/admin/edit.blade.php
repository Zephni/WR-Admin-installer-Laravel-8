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
                    {!! session()->get('success') !!}
                </div>
            @endif

            <h2>Editing {{ $tableAlias }} item</h2>
            @if(method_exists($instance, 'getFrontendURL'))
                <a href="{{ $instance->getFrontendURL() }}" target="_blank" type="button" class="btn btn-sm btn-success mx-1"><span class="pr-1 fa fa-eye"></span> View</a>
            @endif
            <hr>

            <form name="{{ $tableName }}Form" id="{{ $tableName }}Form" method="post" enctype="multipart/form-data" action="{{ route('admin-edit-action', ['table' => $tableName, 'id' => $id]) }}">
                @csrf
                <input type="hidden" name="modelRef" value="{{ $modelRef }}">
                <input type="hidden" name="id" value="{{ $id }}">

                @foreach($tableFields as $k => $v)
                
                    {!! $v->Render() !!}

                @endforeach

                <button class="btn btn-lg btn-primary my-3" type="submit">Save changes</button>

            </form>

            <br /><br /><br />
            <br /><br /><br />

        </div>
    </div>

@endsection