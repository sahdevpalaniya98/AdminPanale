<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use DataTables;
use Validator;
use Session;
use File;
use Auth;

class CategoryController extends Controller
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
            $data['page_title'] = 'Category';

            if(Auth::user()->can('category-add')) {
            $data['btnadd'][]      = array(
                'link'  => route('admin.category.add'),
                'title' => 'Add Category'
            );
            }

            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Category'
            );

            return view('admin.category.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        $category = Category::query();

        return DataTables::eloquent($category)
            ->addColumn('action', function ($category) {
                $action      = '';
                if (Auth::user()->can('category-edit')) {
                    $action .= '<a href="'.route('admin.category.edit', $category->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('category-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.category.destroy').'" data-id="'.$category->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->addColumn('type', function ($category) {
                return isset($category->device_type) ? $category->device_type->name : '';
            })
            ->editColumn('category_icon', function ($atribute) {
                if (isset($atribute->category_icon) && File::exists(public_path('uploads/category/' . $atribute->category_icon))) {
                    $image = '<img src="' . asset('uploads/category/' . $atribute->category_icon) . '" id="user_image" class="rounded-circle header-profile-user" alt="Avatar">';
                } else {
                    $image = '-';
                }
                return $image;
            })
            ->editColumn('created_at', function ($category) {
                return date('d/m/Y h:i A', strtotime($category->created_at));
            })
            ->editColumn('brand_id', function ($category) {
                return (isset($category->brand) && $category->brand->brand_name != "") ? $category->brand->brand_name : "-";
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action', 'category_icon','brand_id'])
            ->make(true);
    }

    public function create()
    {
        try {
            $data['page_title']         = 'Add Category';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            $data['breadcrumb'][]   = array(
                'link'      => route('admin.category.index'),
                'title'     => 'Category'
            );
            $data['breadcrumb'][]       = array(
                'title'     => 'Add Category'
            );

            return view('admin.category.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $data['page_title']         = 'Edit Category';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            $data['breadcrumb'][]   = array(
                'link'      => route('admin.category.index'),
                'title'     => 'category'
            );
            $data['breadcrumb'][]       = array(
                'title'     => 'Edit Category'
            );

            $category = Category::find($id);

            if ($category) {
                $data['category'] = $category;
                return view('admin.category.edit', $data);
            } else {
                return abort(404);
            }
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
                'category_name'  => 'required',
                'brand_id'  => 'required',
            ];

            if ($request->has('category_icon')) {
                $rules['category_icon'] = 'required';
            }

            if (!$request->has('category_id')) {
                $rules['category_icon'] = 'required|mimes:jpg,jpeg,png,bmp,tiff |max:4096';
                $messages['category_icon.required'] = 'The brand icon field is required.';
                $messages['category_icon.mimes']    = 'Please insert category_icon only.';
                $messages['category_icon.max']      = 'brand icon should be less than 4 MB.';
            }

            $messages = [
                'brand_id.required' => 'The brand field is required.',
                'category_name.required' => 'The name field is required.',
                'category_icon.required'   => 'The category_icon field is required.',
            ];

            $validator      = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->has('category_id')) {
                    return redirect()->route('admin.category.edit', $request->category_id)
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->route('admin.category.add')
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                if ($request->has('category_id')) {
                    $category                = Category::where('id', $request->category_id)->first();
                    $category->updated_at    = date('Y-m-d H:i:s');
                    $action               = 'updated';
                } else {
                    $category                = new Category();
                    $category->created_at    = date('Y-m-d H:i:s');
                    $action               = 'added';
                }

                if ($logo = $request->file('category_icon')) {
                    if ($category->category_icon != '') {
                        $categoryLogo = public_path('uploads/category/' . $category->category_icon);
                        if (File::exists($categoryLogo)) {
                            unlink($categoryLogo);
                        }
                    }
                    $destinationPath = 'uploads/category/';
                    $categoryLogo = date('YmdHis') . "." . $logo->getClientOriginalExtension();
                    $logo->move($destinationPath, $categoryLogo);
                    $category->category_icon    = $categoryLogo;
                }   
                $category->category_name        = $request->category_name;
                $category->brand_id             = $request->brand_id;
                $category->remark               = $request->remark ;
                $category->save();

                Session::flash('alert-message', 'Category ' . $action . ' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.category.index');
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
            $category = Category::where('id', $request->id)->first();
            if ($category) {
                $categoryLogo = public_path('uploads/category/' . $category->category_icon);
                if (File::exists($categoryLogo)) {
                    unlink($categoryLogo);
                }
                // if($category->phone_series->count() > 0)
                // {
                //     $return['success']        = false;
                //     $return['message']        = "You're not able to delete the category because there are some phone series available.";
                //     return response()->json($return);
                // }
                $category->delete();
                $return['success']        = true;
                $return['message']        = "Category deleted successfully.";
            } else {
                $return['success']        = false;
                $return['message']        = "Category not deleted.";
            }
        }
        return response()->json($return);
    }
}
