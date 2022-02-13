@extends('index')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">المناطق</h3>
                    <div class="card-toolbar">
                        <a type="button" class="btn btn-primary" id="add_edit_btn" onclick="showModal('{{ route('regions.show_form')}}')" >
                            <i class="flaticon-plus"></i>اضافة منطقة
                        </a>
                    </div>
                </div>
                <!--begin::Form-->

                    <div class="card-body">


                        <table id="items_table" class="table-responsive-lg" >
                            <thead>
                            <tr>
                                <th>اسم المنطقة</th>
                                <th>مسؤول المنطقة</th>
                                <th>الخيارات</th>

                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>


            </div>

        </div>

    </div>
    <div class="modal fade bd-example-modal-lg" id="page_modal" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

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
                    url: '{{route('regions.list')}}',
                    type: 'GET',
                    "data": function (d) {

                    }

                },
                columns: [
                    {className: 'text-center', data: 'name', name: 'name',},
                    {className: 'text-center', data: 'administrator', name: 'administrator'},
                    {className: 'text-center', data: 'actions', name: 'actions'},

                ],

                language: {
                    "url": "{{url('/')}}/assets/plugins/custom/datatables/Arabic.json"
                },

            });

        });
        $('.search_btn').click(function (ev) {
            $('#name').val('');
            $('#code').val('');
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

