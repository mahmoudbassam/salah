<?php

namespace App\Http\Controllers\CP;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeneficiaryRequest;
use App\Models\Beneficiary;
use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BeneficiaryController extends Controller
{

    public function index(){
        $data['regions']=Region::all();
        return view('CP.beneficiaries.index',$data);
    }
    public function show_form($id=null)
    {
        $data['record']=null;
        if($id){
            $data['record']=Beneficiary::query()->findOrFail($id);
        }
        $data['regions']=Region::all();
        return view('CP.beneficiaries.form',$data);
    }

    public function add_edit(BeneficiaryRequest $request)
    {
       Beneficiary::query()->create(
           $request->except('_token')
       );

       return redirect()->back()->with('success','تمت عملية الإضافة بنجاح');
    }
    public function list(Request $request)
    {

        $beneficiaries= Beneficiary::query()->with('region');
        if($request->get('name')){
            $beneficiaries->where(function($q)use ($request){
                $q->where('first_name','like','%' . $request->get('name') .'%')
                    ->orWhere('second_name','like','%' . $request->get('name') .'%')
                    ->orWhere('third_name','like','%' . $request->get('name') .'%')
                    ->orWhere('last_name','like','%' . $request->get('name') .'%');
            });
        }
        if($request->get('id_number')){
            $beneficiaries->where(function($q)use ($request){
                $q->where('id_number','like','%' . $request->get('id_number') .'%');
            });
        }

        if($request->get('region_id')){
            $beneficiaries->where('region_id', $request->get('region_id'));
        }
     return   DataTables::of($beneficiaries)->make(true);

    }
}
