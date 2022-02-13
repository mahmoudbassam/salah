<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"
                id="exampleModalLongTitle">@if($record) تعديل منطقة @else انشاء منطقة@endif</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('regions.add_edit')}}" method="post" id="add_edit_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="name_en">اسم المنطقة</label>
                            <div class="col-12">
                                <input type="text" name="name" id="name_en" class="form-control" required
                                       placeholder="اسم المنطقة"  value="@if($record){{$record->name}} @endif" autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="name_error"></div>

                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="name_ar">مسؤول المنطقة</label>
                            <div class="col-12">
                                <input type="text" name="administrator" id="administrator" class="form-control" required
                                       placeholder="مسؤول المنطقة" value="@if($record){{$record->administrator}} @endif" autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="administrator_error"></div>

                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                @if($record)
                    <input type="hidden" name="id" value="{{$record->id}}">
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('constants.cancel')</button>
                <button type="button" class="btn btn-primary submit_btn">@lang('constants.submit')</button>
            </div>
        </form>
    </div>
</div>
<script>

    $('#add_edit_form').validate({
        rules: {
            'name':{
                required: true,
            }

        },
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: true,
        errorPlacement: function (error, element) {
            $(element).addClass("is-invalid");
            error.appendTo('#' + $(element).attr('id') + '_error');
        },
        success: function (label, element) {
            $(element).removeClass("is-invalid");
        }
    });
    $('.submit_btn').click(function(e){
        e.preventDefault();

        if (!$("#add_edit_form").valid())
            return false;


        postData(new FormData($('#add_edit_form').get(0)), '{{route('regions.add_edit')}}');
    });
</script>
