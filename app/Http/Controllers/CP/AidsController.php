<?php

namespace App\Http\Controllers\CP;

use App\Http\Controllers\Controller;
use App\Models\Aid;
use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AidsController extends Controller
{
    public function index()
    {
        return view('CP.aids.index');
    }

    public function show_form($id = null)
    {
        $data = [];
        $data['record'] = null;
        if ($id) {
            $data['record'] = Aid::query()->findOrFail($id);
        }

        return response()->json([
            'success' => TRUE,
            'page' => view('CP.aids.form', $data)->render()
        ]);

    }
    public function add_edit (Request $request)
    {
        Aid::query()->updateOrCreate(['id'=>$request->id],$request->except('_token'));

        return response()->json([
            'success' => TRUE,
            'message' => $request->id ? 'تمت عملية التعديل بنجاح' : 'تمت عملية الإدخال بنجاح' ,
        ]);

    }
    public function list (Request $request)
    {

        $items=Aid::query();
        return DataTables::of($items)
            ->addColumn('actions',function($item){
                $edit = '<a class="dropdown-item" onclick="showModal(\''.route('aids.show_form', ['id'=>$item->id ?? '']).'\''.')" >
                            <i class="flaticon-edit" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">تعديل </span>
                        </a>';

                return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                ' . $edit . '



                            </div>
                        </div>';
            })  ->addColumn('enabled', function ($item) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch">';
                $is_enabled .= '<label style="margin: 5px 0 0">';
                $is_enabled .= '<label style="margin: 0">';
                if ($item->enabled) {
                    $is_enabled .= '<input onclick="change_status(' . $item->id . ',\'' . route( 'aids.enabled') . '\')" type="checkbox" checked="checked" name="">';
                } else {
                    $is_enabled .= '<input onclick="change_status(' . $item->id . ',\'' . route( 'aids.enabled') . '\')" type="checkbox" name="">';
                }
                $is_enabled .= '<span></span>';
                $is_enabled .= '</label>';
                $is_enabled .= '</div>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })

            ->rawColumns(['actions','enabled'])
            ->make(true);

    }
    public function enabled(Request $request)
    {

        $id = $request->get('id');
        $item = Aid::query()->find($id);

        if ($item->enabled) {
            $item->enabled = 0;
            $item->save();
        } else {
            $item->enabled = 1;
            $item->save();
        }

        return response()->json([
            'success' => TRUE,
            'message' => 'تم التعديل بنجاح'
        ]);
    }
}
