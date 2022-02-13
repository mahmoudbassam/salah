<?php

namespace App\Http\Controllers\CP;

use App\Http\Controllers\Controller;
use App\Models\region;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class RegionsController extends Controller
{
    public function index(){
        return view('CP.regions.index');
    }
    public function list(){
       return DataTables::of(region::query())
           ->addColumn('actions',function($item){
               $edit = '<a class="dropdown-item" onclick="showModal(\''.route('regions.show_form', ['id'=>$item->id ?? '']).'\''.')" >
                            <i class="flaticon-edit" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">تعديل </span>
                        </a>';
               $delete = '<a href="javascript:;" class="dropdown-item" onclick="">
                            <i class="flaticon-eye" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">إضهار المستفيدين</span>
                        </a>';
               return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                ' . $edit . '
                                ' . $delete . '


                            </div>
                        </div>';
           }) ->rawColumns(['actions'])
           ->make(true);
    }
    public function show_form($id=null){
        $data=[];
        $data['record']=null;
        if ($id) {
            $data['record'] = Region::query()->findOrFail($id);
        }
        // $data['city_id']=$city;
        return response()->json([
            'success' => TRUE,
            'page' => view('CP.regions.form', $data)->render()
        ]);

    }

    public function add_edit(Request $request){
       if($request->id){
           $region=Region::query()->findOrFail($request->id);
       }
       region::query()->updateOrCreate(['id'=>$request->id],$request->except('_tokec'));
        return response()->json([
            'success' => TRUE,
            'message' => $request->id ? 'تمت عملية التعديل بنجاح' : 'تمت عملية الإدخال بنجاح' ,
        ]);
    }
}
