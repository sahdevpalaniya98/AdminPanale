<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use Auth;
use Validator;
use Yajra\DataTables\Facades\DataTables;
use Session;

class VariantController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:variant-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:variant-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:variant-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:variant-delete', ['only' => ['destroy']]);
        $this->middleware('permission:variant-view', ['only' => ['view']]);
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Variant List';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('variant-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.variant.add'),
                    'title' => 'Add Variant'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'List'
            );
            return view('admin.variant.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        $variant = Variant::query();
        return DataTables::eloquent($variant)
            ->addColumn('action', function ($variant) {
                $action = '';
                if (Auth::user()->can('variant-edit')) {
                    $action .= '<a href="' . route('admin.variant.edit', $variant->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('variant-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="' . route('admin.variant.destroy') . '" data-id="' . $variant->id . '" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })

            ->editColumn('type', function ($variant) {
                return ($variant->type == 'color') ? "Color" : "Space";
            })

            ->editColumn('created_at', function ($variant) {
                return ($variant->created_at) ? date('d-m-Y', strtotime($variant->created_at)) : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(variants.created_at,'%d-%m-%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['type', 'action'])->addIndexColumn()
            ->make(true);
    }


    public function create()
    {
        try {
            $data = [];
            $data['page_title'] = 'Add Variant';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('variant-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.variant.index'),
                    'title' => 'Variant'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'Add'
            );
            return view('admin.variant.add', $data);
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
            $data['page_title'] = 'Edit Variant';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('variant-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.variant.index'),
                    'title' => 'Variant'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'Edit'
            );
            $data['variant'] = Variant::where('id', $id)->first();

            return view('admin.variant.add', $data);

        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'type' => 'required',
                'title' => 'required',
            ];
            $message = [
                'type.required' => 'The variant type is required',
                'title.required' => 'The variant title is required',
            ];
            $validator = Validator::make($request->all(), $rules, $message);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {

                if ($request->has('variant_id')) {
                    $variant = Variant::where('id', $request->variant_id)->first();
                    $action = 'updated';
                } else {
                    $variant = new Variant();
                    $action = 'added';
                }

                $variant->type = $request->type;
                $variant->title = $request->title;
                $variant->save();

                Session::flash('alert-message', 'Variant ' . $action . ' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.variant.index');
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
                $variant = Variant::where('id', $request->id)->first();
                if ($variant) {
                    $variant->delete();
                    $return['success'] = true;
                    $return['message'] = "Variant deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Variant not deleted.";
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