<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"
                id="exampleModalLongTitle">@if($record) تعديل مساعدة @else انشاء مساعدة@endif</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('aids.add_edit')}}" method="post" id="add_edit_form">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="name">اسم المساعدة</label>
                            <div class="col-12">
                                <input type="text" name="name" id="name" class="form-control" required
                                       placeholder="اسم المساعدة"  value="@if($record){{$record->name}} @endif" autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="name_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="implementation_body">جهة التنفيذ</label>
                            <div class="col-12">
                                <input type="text" name="implementation_body" id="implementation_body" class="form-control"
                                       placeholder="جهة التنفيذ" value="@if($record){{$record->implementation_body}} @endif" autocomplete="off">
                            </div>

                        </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="name_ar">وصف المساعدة</label>
                            <div class="col-12">
                                <input type="text" name="description" id="description" class="form-control"
                                       placeholder="وصف المساعدة" value="@if($record){{$record->description}} @endif" autocomplete="off">
                            </div>

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


        postData(new FormData($('#add_edit_form').get(0)), '{{route('aids.add_edit')}}');
    });
</script>
