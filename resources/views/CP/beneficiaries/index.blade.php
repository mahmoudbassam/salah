@extends('index')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">المستفيدين</h3>
                    <div class="card-toolbar">
                        <a type="button" class="btn btn-primary" id="add_edit_btn" href="{{route('beneficiaries.show_form')}}" >
                            <i class="flaticon-plus"></i>اضافة مستفيد
                        </a>
                    </div>
                </div>
                <!--begin::Form-->

                    <div class="card-body">

                        <div>
                            <div class="card card-body">
                                <div class="row mb-6">
                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                        <label>الاسم</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="الاسم">

                                    </div>
                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                        <label>رقم الهوية</label>
                                        <input type="text" class="form-control" name="id_number" id="id_number" placeholder="رقم الهوية">
                                    </div>
                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                        <label>المنطقة</label>
                                        <select name="region_id" id="region_id"
                                                class=" form-control"
                                                data-live-search="true">
                                            <option value="">اختيار</option>
                                            @foreach($regions as $region)
                                                <option value="{{$region->id}}">{{$region->name}}</option>
                                                @endforeach

                                        </select>
                                    </div>

                                </div>
                                <div class="col-lg-12">
                                    <button class="btn btn-primary btn-primary--icon search_btn" id="search_btn">
													<span>
														<i class="la la-search"></i>
														<span>@lang('constants.search')</span>
													</span>
                                    </button>&nbsp;&nbsp;
                                    <button class="btn btn-secondary btn-secondary--icon reset_search" id="reset_search">
													<span>
														<i class="la la-close"></i>
														<span>@lang('constants.cancel')</span>
													</span>
                                    </button></div>
                            </div>
                            <br>
                        </div>
                        <table id="items_table" class="table-responsive-lg" >
                            <thead>
                            <tr>
                                <th>رقم الهوية</th>
                                <th>الاسم</th>
                                <th>رقم الجوال</th>
                                <th>المنطقة</th>
                                <th>الخيارات</th>

                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>


            </div>

        </div>

    </div>

    @endsection

@section('js')
    <script>
        $.fn.dataTable.ext.errMode = 'none';
        $(function () {
            $('#items_table').DataTable({
                "dom": 'tpi',

                "searching": false,
                "processing": true,
                'stateSave': true,
                "serverSide": true,
                ajax: {
                    url: '{{route('beneficiaries.list')}}',
                    type: 'GET',
                    "data": function (d) {
                        d.name=$('#name').val();
                        d.id_number=$('#id_number').val();
                        d.region_id=$('#region_id').val();
                    }

                },
                columns: [
                    {className: 'text-center', data: 'id_number', name: 'id_number',},
                    {className: 'text-center', data: 'full_name', name: 'full_name',},
                    {className: 'text-center', data: 'phone', name: 'phone'},
                    {className: 'text-center', data: 'region.name', name: 'region'},
                    {className: 'text-center', data: 'actions', name: 'actions'},

                ],

                language: {
                    "url": "{{url('/')}}/assets/plugins/custom/datatables/Arabic.json"
                },

            });

        });
        $('.search_btn').click(function (ev) {
            $('#items_table').DataTable().ajax.reload(null, false);
        });

        $('.reset_search').click(function () {
            $('#name').val('');
            $('#code').val('');
            $(".selectpicker").val('default');

            $(".selectpicker").selectpicker("refresh");
            $('#items_table').DataTable().ajax.reload(null, false);
        });
    </script>

@endsection

