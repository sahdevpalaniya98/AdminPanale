<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhoneSeries;
use App\Models\Brand;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class PhoneSeriesController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:phone-series-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:phone-series-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:phone-series-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:phone-series-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Phone Series List';
            if  (Auth::user()->can('phone-series-add')) {
                $data['btnadd'][]       = array(
                    'link'  => route('admin.phone.series.add'),
                    'title' => 'Add Phone Series'
                );
            }
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.phone-series.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        $series = PhoneSeries::query()->with('brand');
        if(isset($request->filter['brand_id']) && $request->filter['brand_id'] != null){
            $series->where('brand_id',$request->filter['brand_id']);
        }
        if(isset($request->filter['category_id']) && $request->filter['category_id'] != null){
            $series->where('category_id',$request->filter['category_id']);
        }
        return DataTables::eloquent($series)
            ->addColumn('action', function ($series) {
                $action      = '';
                if (Auth::user()->can('phone-series-edit')) {
                    $action .= '<a href="'.route('admin.phone.series.edit', $series->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('phone-series-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.phone.series.destroy').'" data-id="'.$series->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('created_at', function ($series) {
                return date('d/m/Y h:i A', strtotime($series->created_at));
            })
            ->editColumn('brand_id', function ($series) {
                return (isset($series->brand) && $series->brand->brand_name != "") ? $series->brand->brand_name : '-';
            })
            ->editColumn('category_id', function ($series) {
                return (isset($series->category) && $series->category->category_name != "") ? $series->category->category_name : '-';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action','address','brand_id','category_id'])->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Add Phone Series';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('phone-series-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.phone.series.index'),
                    'title' => 'Phone Series'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            $data['brands']              = Brand::get();
            return view('admin.phone-series.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $seriesId   = ($request->series_id) ? $request->series_id : '';
            $rules = [
                'series_name'=> 'required',
                'brand_id'=> 'required',
            ];
        
            $messages = [
                'brand_id.required' => 'The brand id field is required.',
                'series_name.required' => 'The phone series name field is required.',
            ];
            $validator      = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                if ($seriesId != '') {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                
                if($seriesId != "")
                {
                    $series   = PhoneSeries::where('id', $request->series_id)->whereNull('deleted_at')->first();
                    $action     = "Update";
                }else{
                    $series   = new PhoneSeries();
                    $action     = "Added";
                }
                $series->brand_id       = $request->brand_id; 
                $series->series_name    = $request->series_name; 
                $series->remark         = $request->remark;
                $series->category_id    = (isset($request->category_id) && $request->category_id != "") ? $request->category_id : "";
                
               if($series->save())
               {
                Session::flash('alert-message', 'Phone Series '.$action.' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.phone.series.index');
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
            $data                       = [];
            $data['page_title']         = 'Edit Phone Series';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('phone-series-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.phone.series.index'),
                    'title' => 'Phone Series'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Edit'
            );
            $series                       = PhoneSeries::where('id', $id)->first();
            $data['brands']               = Brand::get();
            if ($series) {
                $data['series']           = $series;
                return view('admin.phone-series.edit', $data);
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
                $series = PhoneSeries::with('phone_series')->where('id', $request->id)->first();

                if ($series) {
                    if($series->phone_series->count() > 0) 
                    {
                        $return['success']        = false;
                        $return['message']        = "You're not able to delete the phone series because there are some phone models available.";
                        return response()->json($return);
                    }
                    $series->delete();
                    $return['success'] = true;
                    $return['message'] = "Phone Series deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Phone Series not deleted.";
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
