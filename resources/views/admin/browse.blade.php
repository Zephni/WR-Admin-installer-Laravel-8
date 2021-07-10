@extends('admin.layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">

            <h2>Browsing {{ $tableAlias }}</h2>

            {{-- Non mobile only --}}
            <div class="d-none d-md-block">
                <div class="text-muted float-left mt-2 pt-1">
                    @if($collection->count() > 0)
                        Found {{ $collection->count() }} result{{ ($collection->count() == 1) ? '' : 's' }}
                    @endif
                </div>

                <div class="float-right">
                    <a href="{{ route('admin-create', ['table' => $tableName]) }}" class='btn btn-success'>Create new {{ Str::singular(strtolower($tableAlias)) }}</a>
                </div>

                <form class="form float-right mr-3" action="{{ route('admin-browse',['table' => $tableName]) }}" method="get" style="width: 80%; max-width: 300px;">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="{{ $_GET['q'] ?? '' }}" placeholder="Filter search">
                        <div class="input-group-append">
                            <input type="submit" value="Search" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>

            {{-- Mobile only --}}
            <div class="d-block d-md-none">
                <div class="text-muted mt-2 pt-1 w-100">
                    @if($collection->count() > 0)
                        Found {{ $collection->count() }} result{{ ($collection->count() == 1) ? '' : 's' }}
                    @endif
                </div>

                <a class="w-100 d-block my-2 btn btn-success" href="{{ route('admin-create', ['table' => $tableName]) }}">Create new {{ Str::singular(strtolower($tableAlias)) }}</a>

                <form class="form w-100" action="{{ route('admin-browse',['table' => $tableName]) }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="{{ $_GET['q'] ?? '' }}" placeholder="Filter search">
                        <div class="input-group-append">
                            <input type="submit" value="Search" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>

            <div class="clearfix mb-3"></div>

            @if(isset($information) && $information != '')
                <div class='alert alert-info'>
                    {!! '<b>About '.strtolower($tableAlias).'</b><br />'.$information !!}
                </div>
            @endif

            <hr class='pb-3'>

            <table class="table table-hover w-100 shadow-lg bg-white rounded">
                <thead class="bg-primary text-light">
                    <tr>

                        @foreach ($tableFields as $fieldKey => $fieldAlias)
                            <th scope="col">{{ $fieldAlias }}</th>
                        @endforeach

                        {{-- Button group column --}}
                        <th></th>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($collection as $item)
                        <tr>
                            @foreach ($tableFields as $fieldKey => $fieldAlias)
                                @if(array_key_exists($fieldKey, $joinFields))
                                    <?php $relationshipName = $joinFields[$fieldKey]; ?>
                                    <td class="align-middle">{{ (is_object($item->$fieldKey)) ? $item->$fieldKey->$relationshipName : ' - not set - ' }}</td>
                                @elseif(array_key_exists($fieldKey, $selectFieldData))
                                    <td class="align-middle">{{ $selectFieldData[$fieldKey][$item->$fieldKey] }}</td>
                                @else
                                    <td class="align-middle">{{ $item->$fieldKey }}</td>
                                @endif
                            @endforeach
                        
                            <td class="text-right">
                                {{-- View --}}
                                @if(method_exists($item, 'getFrontendURL'))
                                    <a href="{{ $item->getFrontendURL() }}" target="_blank" type="button" class="btn btn-sm btn-success mx-1"><span class="pr-1 fa fa-eye"></span> View</a>
                                @endif
                                {{-- Edit --}}
                                <a href="{{ route('admin-edit', ['table' => $tableName, 'id' => $item->id]) }}" type="button" class="btn btn-sm btn-primary mx-1"><span class="pr-1 fa fa-edit"></span> Edit</a>
                                {{-- Delete --}}
                                <button type="button" class="btn btn-sm btn-danger mx-1" data-toggle="modal" data-target="#confirmDeleteModal" data-delete_id="{{ $item->id }}">
                                    <span class="pr-1 fa fa-trash"></span> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            @if(count($collection) == 0)
                <p class="lead text-muted text-center py-3">There hasn't been any {{ $tableName }} created yet,
                    <a href="{{ route('admin-create', ['table' => $tableName]) }}">create one here</a>
                </p>
            @endif

            <br />
            <br />
            
            <div class='pagination-container'>
                {{ $collection->withQueryString()->links() }}
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Are you sure you want to delete this {{ strtolower($tableAlias) }} item?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <div class="modal-body">
                ...
            </div> --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form name="delete-form" id="delete-form?" action="{{ route('admin-delete-action') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="modelRef" value="{{ $modelRef }}">
                    <input type="hidden" name='deleteID' id='inputDeleteID' value='?'>
                    <button type="button" id="delete-btn" class="btn btn-danger" onclick="event.preventDefault();">
                        <span class="pr-1 fa fa-trash"></span> Confirm Delete
                    </button>
                </form>

            </div>
            </div>
        </div>
    </div>

    <script>
        $('#confirmDeleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var deleteID = button.data('delete_id');
            var modal = $(this);
            modal.find('.modal-footer form').attr("id", "delete-form-"+deleteID);
            modal.find('.modal-footer #inputDeleteID').attr("value", deleteID);
            modal.find(".modal-footer button#delete-btn").click(function(){
                modal.find('.modal-footer form').submit();
            });
        })
    </script>

@endsection