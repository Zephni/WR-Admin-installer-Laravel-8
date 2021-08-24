@extends('admin.layouts.admin')

@section('content')

    <style>
        .commands-content {font-size: 1.1rem;}
        .commands-content .command-output p {margin-bottom: 0px;}
        .commands-content kbd {display: inline-block; padding: 2px 8px 3px 8px;}
    </style>

    <script>
        $(document).ready(function(){
            var busyWorking = false;
            
            function setBusyWorking(boolean, buttonElement)
            {
                busyWorking = boolean;

                if(busyWorking)
                    $(buttonElement).removeClass('btn-primary').addClass('btn-secondary').text('Working...').css({'cursor':'default'});
                else
                    $(buttonElement).removeClass('btn-secondary').addClass('btn-primary').text('Run command').css({'cursor':'pointer'});
            }

            $('.command-output').hide();
            
            $(".commands-content .command-trigger").on('click', function(){
                var thisObj = this;

                if(busyWorking)
                {
                    return;
                }

                setBusyWorking(true, thisObj);
                
                var commandToRun = $(this).attr('data');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin-command-run") }}',//'/admin/command-run',
                    cache: false,
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'command':commandToRun
                    },
                    success: function(data){
                        var finalHTML = data;
                        $('.'+commandToRun.replace(':', '')+'-command-output').fadeIn().css({'background':'#E7E7E7', 'color': 'black'}).html(finalHTML);
                        setBusyWorking(false, thisObj);
                    },
                    error: function(data){
                        var finalHTML = data.responseText;
                        $('.'+commandToRun.replace(':', '')+'-command-output').fadeIn().css({'background':'#FF6699', 'color':'black'}).html(finalHTML);
                        setBusyWorking(false, thisObj);
                    }
                });
            });
        });
    </script>

    <h1>Custom Artisan commands</h1>
    <hr>
    <br />

    <div class="commands-content">
        @foreach ($commands as $command => $description)
            <p>
                <kbd class="command">{{ $command }}</kbd>
                <button class="btn btn-sm btn-primary command-trigger" data="{{ $command }}">Run command</button>
            </p>
            <p>{{ $description }}</p>
            <div style="background: #E7E7E7; font-size: 1rem; white-space: nowrap; overflow: auto; font-family: 'Courier New', monospace;" class="command-output p-3 {{ Str::replace(':', '', $command) }}-command-output">
                
            </div>
            <hr />
        @endforeach
    </div>

@endsection
