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
            
            $(".command-form").on('submit', function(e){
                e.preventDefault();
                
                var commandTriggerObject = $(this).find('.command-trigger');

                if(busyWorking)
                {
                    return;
                }

                setBusyWorking(true, commandTriggerObject);
                
                var postData = Object.fromEntries(new FormData(e.target).entries());
                commandToRun = postData['command'];

                $.ajax({
                    type: "POST",
                    url: '{{ route("admin") }}/command-run',
                    cache: false,
                    data: postData,
                    success: function(data){
                        var finalHTML = data;
                        $('.'+commandToRun.replace(':', '')+'-command-output').fadeIn().css({'background':'#E7E7E7', 'color': 'black'}).html(finalHTML);
                        setBusyWorking(false, commandTriggerObject);
                    },
                    error: function(data){
                        var finalHTML = data.responseText;
                        $('.'+commandToRun.replace(':', '')+'-command-output').fadeIn().css({'background':'#FF6699', 'color':'black'}).html(finalHTML);
                        setBusyWorking(false, commandTriggerObject);
                    }
                });
                
                return false;
            });
        });
    </script>

    <h1>Custom Artisan commands</h1>
    <hr>
    <br />

    <div class="commands-content">
        @foreach ($commands as $command => $description)
            <p class="d-inline pr-1">
                <kbd class="command">{{ $command }}</kbd>
            </p>

            <form class="command-form d-inline" action="#" method="post">
                @csrf
                <input type="hidden" name="command" value="{{ $command }}">
                <button class="btn btn-sm btn-primary command-trigger">Run command</button>
            </form>
        
            <p>{{ $description }}</p>
            <div style="background: #E7E7E7; font-size: 1rem; white-space: nowrap; overflow: auto; font-family: 'Courier New', monospace;" class="command-output p-3 {{ Str::replace(':', '', $command) }}-command-output">
                
            </div>
            <hr />
        @endforeach
    </div>

@endsection
