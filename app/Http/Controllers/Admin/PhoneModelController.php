<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhoneModel;
use App\Models\Brand;
use App\Models\PhoneSeries;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class PhoneModelController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:phone-model-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:phone-model-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:phone-model-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:phone-model-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Phone Model List';
            if (Auth::user()->can('phone-model-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.phone.model.add'),
                    'title' => 'Add Phone Model'
                );
            }
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][] = array(
                'title' => 'List'
            );
            return view('admin.phone-model.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        $model = PhoneModel::query()->with(['brand', 'series']);
        if(isset($request->filter['brand_id']) && $request->filter['brand_id'] != null){
            $model->where('brand_id',$request->filter['brand_id']);
        }
        if(isset($request->filter['category_id']) && $request->filter['category_id'] != null){
            $model->where('category_id',$request->filter['category_id']);
        }
        if(isset($request->filter['series_id']) && $request->filter['series_id'] != null){
            $model->where('series_id',$request->filter['series_id']);
        }

        return DataTables::eloquent($model)
            ->addColumn('action', function ($model) {
                $action = '';
                if (Auth::user()->can('phone-model-edit')) {
                    $action .= '<a href="' . route('admin.phone.model.edit', $model->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('phone-model-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="' . route('admin.phone.model.destroy') . '" data-id="' . $model->id . '" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('created_at', function ($model) {
                return date('d/m/Y h:i A', strtotime($model->created_at));
            })
            ->editColumn('brand_id', function ($series) {
                return (isset($series->brand) && $series->brand->brand_name != "") ? $series->brand->brand_name : '-';
            })
            ->editColumn('series_id', function ($series) {
                return (isset($series->series) && $series->series->series_name != "") ? $series->series->series_name : '-';
            })
            ->editColumn('category_id', function ($series) {
                return (isset($series->category) && $series->category->category_name != "") ? $series->category->category_name : '-';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action', 'address', 'brand_id', 'series_id', 'category_id'])->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        try {
            $data = [];
            $data['page_title'] = 'Add Phone Model';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('phone-model-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.phone.model.index'),
                    'title' => 'Phone Model'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'Add'
            );
            $data['brands'] = Brand::get();
            $data['series'] = PhoneSeries::get();

            return view('admin.phone-model.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $modelId = ($request->model_id) ? $request->model_id : '';
            $rules = [
                'model_name' => 'required',
                'brand_id' => 'required',
            ];

            $messages = [
                'brand_id.required' => 'The brand id field is required.',
                'model_name.required' => 'The phone model name field is required.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                if ($modelId != '') {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {

                if ($modelId != "") {
                    $model = PhoneModel::where('id', $request->model_id)->whereNull('deleted_at')->first();
                    $action = "Update";
                } else {
                    $model = new PhoneModel();
                    $action = "Added";
                }
                $model->brand_id = $request->brand_id;
                $model->series_id = $request->series_id;
                $model->model_name = $request->model_name;
                $model->remark = $request->remark;
                $model->category_id = (isset($request->category_id) && $request->category_id != "") ? $request->category_id : "";
                if ($model->save()) {
                    if ($request->variant_id != "") {
                        $model->phone_variant()->sync($request->variant_id);
                    }
                    Session::flash('alert-message', 'Phone Model ' . $action . ' successfully.');
                    Session::flash('alert-class', 'success');
                    return redirect()->route('admin.phone.model.index');
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $data = [];
            $data['page_title'] = 'Edit Phone Model';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('phone-model-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.phone.model.index'),
                    'title' => 'Phone model'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'Edit'
            );
            $model = PhoneModel::where('id', $id)->first();
            $data['brands'] = Brand::get();
            $data['series'] = PhoneSeries::get();

            if ($model) {
                $data['model'] = $model;
                return view('admin.phone-model.edit', $data);
            } else {
                return abort(404);
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = PhoneModel::with('inventory')->where('id', $request->id)->first();
                if ($model->inventory->isEmpty()) {
                    if ($model->delete()) {
                        $return['success'] = true;
                        $return['message'] = "Phone model deleted successfully.";
                    } else {
                        $return['success'] = false;
                        $return['message'] = "Phone model not deleted.";
                    }
                }else{
                    $return['success'] = false;
                    $return['message'] = "You are not able to delete this phone model because model has a inventories.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }
}