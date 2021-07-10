<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    <!-- DateTimePicker -->
    <script src="{{ asset('js/datetimepicker.js') }}"></script>
    <link href="{{ asset('css/datetimepicker.css') }}" rel="stylesheet">
    <script>
        $(document).ready(function(){
            $('.datetimepicker').datetimepicker({
                format : "Y-m-d H:i:s",
                step: 15
            });
        });
    </script>

    <!--Wysiwyg-->
    <script src="https://cdn.tiny.cloud/1/126uh4v0nur2ag6fpa5vb60rduwp1skzx02vsmdww39mpva2/tinymce/5/tinymce.min.js" referrerpolicy="origin"/></script>
    <script>
        tinymce.init({
            selector: '.wysiwyg',
            menubar: false,
            paste_as_text: true,
            oninit : "setPlainText",
            height: '340',
            plugins: ["image", "searchreplace", "help", "paste", "code" ,'link'],
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | link unlink | image | code',
            default_link_target: '_blank',
            automatic_uploads: true,
            images_upload_handler: function (blobInfo, success, failure) {
                let data = new FormData();
                data.append('file', blobInfo.blob(), blobInfo.filename());
                axios.post('/admin/wysiwyg-upload', data)
                    .then(function (res) {
                        success(res.data.location);
                    })
                    .catch(function (err) {
                        failure('HTTP Error: ' + err.message);
                    });
            }

        });

        function setPlainText(ed) {
            //var ed = tinyMCE.get('elm1');

            ed.pasteAsPlainText = true;  

            //adding handlers crossbrowser
            if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
                ed.onKeyDown.add(function (ed, e) {
                    if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
                        ed.pasteAsPlainText = true;
                });
            } else {            
                ed.onPaste.addToTop(function (ed, e) {
                    ed.pasteAsPlainText = true;
                });
            }
        }
    </script>

    <!-- Tagify -->
    <link href="/js/tagify/tagify.css" rel="stylesheet">
    <script src="/js/tagify/jQuery.tagify.min.js"></script>
    <script>
        $(document).ready(function(){
            var tagsObject = $('input.tags').tagify({
                duplicates :false,
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
            });
        });
    </script>
    <style>
        .tagify{
            --tag-bg                  : #3490dc;
            --tag-hover               : #6cb2eb;
            --tag-text-color          : #FFFFFF;
            --tags-border-color       : silver;
            --tag-text-color--edit    : #FFFFFF;
            --tag-remove-bg           : #c44944;
            --tag-pad                 : 4px 8px;
            --tag-inset-shadow-size   : 1.3em;
            --tag-remove-btn-color    : #FFFFFF;
            --tag-remove-btn-bg--hover: #913633;
            border: 1px solid #ced4da;
        }
        .tagify__tag {
            margin: 8px 0 5px 9px;
        }
        .tagify__input {
            margin: 8px 5px;
        }
    </style>

    <!-- Custom -->
    <script>
        $(document).ready(function(){
            // Keep preview image aspect ratio
            $('.square-image-container').each(function(){
                var thisObject = $(this);
                var cw = thisObject.width();
                thisObject.css({'height':cw+'px'});

                $(window).resize(function(){
                    var cw = thisObject.width();
                    thisObject.css({'height':cw+'px'});
                });
            });

            // Password confirmation field appear on type
            $('input[type="password"]').keyup(function(){
                if($('input[type="password"]').val() != ''){
                    $('.password-confirmation').fadeIn(300);
                }else{
                    $('.password-confirmation').fadeOut(300);
                }
            });

            // Navigation search
            $('.search-menu').keyup(function(){                
                var navSearchQuery = $('.search-menu').val();

                if(navSearchQuery == ''){
                    $('.sidebar-menu ul li').show();
                    return;
                }

                var curSubHead = null;
                var subHeadsUsed = [];
                $('.sidebar-menu ul li').each(function(index){
                    if($(this).hasClass('header-menu')){
                        curSubHead = $(this);
                        curSubHead.hide();
                        return;
                    }

                    var menuItemText = $(this).find('span').text();
                    
                    if(menuItemText.toLowerCase().includes(navSearchQuery.toLowerCase())){
                        if(!subHeadsUsed.includes(curSubHead)){
                            $(curSubHead).show();
                            subHeadsUsed.push($(curSubHead).find('span').text());
                        }
                        $(this).show();
                    }else{
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    <style>
        .password-confirmation {display: none;}
    </style>
</head>

<body>
    <div id="app">

        <div class="container-fluid">
            <div class="row"> {{-- justify-content-center --}}

                <div class="page-wrapper chiller-theme toggled w-100">
                    <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
                        <i class="fas fa-bars"></i>
                    </a>
                    <nav id="sidebar" class="sidebar-wrapper">
                        <div class="sidebar-content">
                            
                            @include('admin.layouts._sidebar')

                        </div>
                    </nav>
                    <!-- sidebar-wrapper  -->
                    <main class="page-content">
                        <div class="container-fluid">

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @yield('content')

                        </div>
                    </main>
                    <!-- page-content" -->
                </div>
                <!-- page-wrapper -->
            </div>
        </div>

    </div>
</body>

</html>