@extends('index')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">إضافة المستفيدين</h3>
                    <div class="card-toolbar">

                    </div>
                </div>

                <form method="post" action="{{route('beneficiaries.add_edit')}}">
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label for="first_name">رقم الهوية
                                <span class="text-danger">*</span></label>
                            <input type="number" class="form-control  @error('id_number')  is-invalid  @enderror" name="id_number" id="id_number" placeholder="رقم الهوية">
                            @error('id_number')   <div class="invalid-feedback" style="font-size: 1.5rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group ">
                            <div class="row">

                                <label class="col-12" for="country_id">المنطقة <span
                                        class="text-danger">*</span></label>
                                <div class="col-12">
                                    <select name="region_id" id="region_id"
                                            class="selectpicker form-control  @error('region_id')  is-invalid  @enderror"
                                            data-live-search="true">
                                        <option value="">اختيار</option>
                                        @foreach($regions as $region)
                                            <option value="{{$region->id}}"
                                                    @if($record && $record->region_id == $region->id)   selected @endif>{{$region->name }}</option>

                                        @endforeach


                                    </select>
                                    @error('region_id')   <div class="invalid-feedback" style="font-size: 1.5rem">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12 text-danger" id="service_id_error"></div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first_name">الإسم الاول
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control   @error('first_name')  is-invalid  @enderror" name="first_name" id="first_name" placeholder="الإسم الاول">
                            @error('first_name')   <div class="invalid-feedback" style="font-size: 1.5rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label for="second_name">اسم الاب
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control   @error('second_name')  is-invalid  @enderror" name="second_name"  id="second_name" placeholder="اسم الاب ">
                            @error('second_name')   <div class="invalid-feedback" style="font-size: 1.5rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label for="third_name">اسم الجد
                                <span class="text-danger"></span></label>
                            <input type="text" class="form-control" name="third_name" id="third_name" placeholder="اسم الجد">
                        </div>
                        <div class="form-group">
                            <label for="last_name">العائلة
                                <span class="text-danger"></span></label>
                            <input type="text" class="form-control @error('last_name')  is-invalid  @enderror" name="last_name" id="last_name" placeholder="العائلة">
                            @error('last_name')   <div class="invalid-feedback" style="font-size: 1.5rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">الجوال
                                <span class="text-danger"></span></label>
                            <input type="number" class="form-control" name="phone" id="phone" placeholder="الجوال">
                        </div>


                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">حفظ</button>

                    </div>
                </form>
            </div>

        </div>

    </div>
    @endsection

 @section('js')
   <script>
       @if(session('success'))
       showAlertMessage('success', '{{session('success')}}');
       @endif
   </script>
@endsection
