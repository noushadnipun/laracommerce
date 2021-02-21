@extends('admin.layouts.master')

@section('site-title')
Medias
@endsection
  
@section('page-content')
    @include('admin.layouts.message')
    
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <form id="dropzoneForm" class="dropzone" action="">
                        @csrf
                    </form>
                    <div class="text-center">
                        <button type="button" class="btn btn-info" id="submit-all">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('cusjs')
    
    <script type="text/javascript">

    Dropzone.options.dropzoneForm = {
        autoProcessQueue : false,
        acceptedFiles : ".png,.jpg,.gif,.bmp,.jpeg",

        init:function(){
        var submitButton = document.querySelector("#submit-all");
        myDropzone = this;

        submitButton.addEventListener('click', function(){
            myDropzone.processQueue();
        });

        this.on("complete", function(){
            if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0)
            {
            var _this = this;
            _this.removeAllFiles();
            }
            load_images();
        });

        }

    };

    load_images();

    function load_images()
    {
        $.ajax({
        url:"google.com",
        success:function(data)
        {
            $('#uploaded_image').html(data);
        }
        })
    }

    $(document).on('click', '.remove_image', function(){
        var name = $(this).attr('id');
        $.ajax({
        url:"google.com",
        data:{name : name},
        success:function(data){
            load_images();
        }
        })
    });

    </script>
@endsection