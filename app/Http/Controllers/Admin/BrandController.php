<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Carbon\Carbon;
use DataTables;
use Validator;
use Session;
use File;
use Auth;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:brand-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:brand-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:brand-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:brand-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Brand';

            if(Auth::user()->can('brand-add')) {
            $data['btnadd'][]      = array(
                'link'  => route('admin.brand.add'),
                'title' => 'Add Brand'
            );
            }

            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Brand'
            );

            return view('admin.brand.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request)
    {
        $brand = Brand::query();

        return DataTables::eloquent($brand)
            ->addColumn('action', function ($brand) {
                $action      = '';
                if (Auth::user()->can('brand-edit')) {
                    $action .= '<a href="'.route('admin.brand.edit', $brand->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('brand-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.brand.destroy').'" data-id="'.$brand->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->addColumn('type', function ($brand) {
                return isset($brand->device_type) ? $brand->device_type->name : '';
            })
            ->editColumn('brand_icon', function ($atribute) {
                if (isset($atribute->brand_icon) && File::exists(public_path('uploads/brands/' . $atribute->brand_icon))) {
                    $image = '<img src="' . asset('uploads/brands/' . $atribute->brand_icon) . '" id="user_image" class="rounded-circle header-profile-user" alt="Avatar">';
                } else {
                    $image = '-';
                }
                return $image;
            })
            ->editColumn('created_at', function ($brand) {
                return date('d/m/Y h:i A', strtotime($brand->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action', 'brand_icon'])
            ->make(true);
    }

    public function create()
    {
        try {
            $data['page_title']         = 'Add Brand';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            $data['breadcrumb'][]   = array(
                'link'      => route('admin.brand.index'),
                'title'     => 'Brand'
            );
            $data['breadcrumb'][]       = array(
                'title'     => 'Add Brand'
            );

            return view('admin.brand.add', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'brand_name'  => 'required',
            ];

            if ($request->has('brand_icon')) {
                $rules['brand_icon'] = 'required';
            }

            if (!$request->has('brand_id')) {
                $rules['brand_icon'] = 'required|mimes:jpg,jpeg,png,bmp,tiff |max:4096';
                $messages['brand_icon.required'] = 'The brand icon field is required.';
                $messages['brand_icon.mimes']    = 'Please insert brand_icon only.';
                $messages['brand_icon.max']      = 'brand icon should be less than 4 MB.';
            }

            $messages = [
                'brand_name.required' => 'The name field is required.',
                'brand_icon.required'   => 'The brand_icon field is required.',
            ];

            $validator      = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->has('brand_id')) {
                    return redirect()->route('admin.brand.edit', $request->brand_id)
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->route('admin.brand.create')
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                if ($request->has('brand_id')) {
                    $brand                = Brand::where('id', $request->brand_id)->first();
                    $brand->updated_at    = date('Y-m-d H:i:s');
                    $action               = 'updated';
                } else {
                    $brand                = new Brand();
                    $brand->created_at    = date('Y-m-d H:i:s');
                    $action               = 'added';
                }

                if ($logo = $request->file('brand_icon')) {
                    if ($brand->brand_icon != '') {
                        $brandLogo = public_path('uploads/brands/' . $brand->brand_icon);
                        if (File::exists($brandLogo)) {
                            unlink($brandLogo);
                        }
                    }
                    $destinationPath = 'uploads/brands/';
                    $brandLogo = date('YmdHis') . "." . $logo->getClientOriginalExtension();
                    $logo->move($destinationPath, $brandLogo);
                    $brand->brand_icon = $brandLogo;
                }
                $brand->brand_name      = $request->brand_name;
                $brand->remark          = $request->remark ;
                $brand->save();

                Session::flash('alert-message', 'Brand ' . $action . ' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.brand.index');
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
            $data['page_title']         = 'Edit Brand';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            $data['breadcrumb'][]   = array(
                'link'      => route('admin.brand.index'),
                'title'     => 'Brand'
            );
            $data['breadcrumb'][]       = array(
                'title'     => 'Edit Brand'
            );

            $brand = Brand::find($id);

            if ($brand) {
                $data['brand'] = $brand;
                return view('admin.brand.edit', $data);
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
        if ($request->ajax()) {
            $brand = Brand::with('phone_series')->where('id', $request->id)->first();
            if ($brand) {
                if($brand->phone_series->count() > 0)
                {
                    $return['success']        = false;
                    $return['message']        = "You're not able to delete the brand because there are some phone series available.";
                    return response()->json($return);
                }
                $brand->delete();
                $return['success']        = true;
                $return['message']        = "Brand deleted successfully.";
            } else {
                $return['success']        = false;
                $return['message']        = "Brand not deleted.";
            }
        }
        return response()->json($return);
    }
}
