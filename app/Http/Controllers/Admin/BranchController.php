<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DataTables;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:branch-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:branch-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:branch-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:branch-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Branch';

            if (Auth::user()->can('branch-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.branch.add'),
                    'title' => 'Add Branch'
                );
            }

            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Branch'
            );

            return view('admin.branch.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request)
    {
        $branch = Branch::query();

        return DataTables::eloquent($branch)
            ->addColumn('action', function ($branch) {
                $action = '';
                if (Auth::user()->can('branch-edit')) {
                    $action .= '<a href="' . route('admin.branch.edit', $branch->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('branch-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="' . route('admin.branch.destroy') . '" data-id="' . $branch->id . '" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('created_at', function ($branch) {
                return date('d/m/Y h:i A', strtotime($branch->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        try {
            $data['page_title'] = 'Add Branch';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][] = array(
                'link' => route('admin.branch.index'),
                'title' => 'Branch'
            );
            $data['breadcrumb'][] = array(
                'title' => 'Add Branch'
            );

            return view('admin.branch.create', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'branch_name' => 'required',
            ];

            $messages = [
                'branch_name.required' => 'The name field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->has('branch_id')) {
                    return redirect()->route('admin.branch.edit', $request->branch_id)
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->route('admin.branch.create')
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                if ($request->has('branch_id')) {
                    $branch = Branch::where('id', $request->branch_id)->first();
                    $branch->updated_at = date('Y-m-d H:i:s');
                    $action = 'updated';
                } else {
                    $branch = new Branch();
                    $branch->created_at = date('Y-m-d H:i:s');
                    $action = 'added';
                }

                $branch->name = $request->branch_name;
                $branch->location = $request->branch_location;
                $branch->save();

                Session::flash('alert-message', 'branch ' . $action . ' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.branch.index');
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
            $data['page_title'] = 'Edit Branch';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][] = array(
                'link' => route('admin.branch.index'),
                'title' => 'Branch'
            );
            $data['breadcrumb'][] = array(
                'title' => 'Edit Branch'
            );

            $branch = Branch::find($id);

            if ($branch) {
                $data['branch'] = $branch;
                return view('admin.branch.create', $data);
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
            $branch = Branch::where('id', $request->id)->first();
            if ($branch) {
                $branch->delete();
                $return['success'] = true;
                $return['message'] = "Branch deleted successfully.";
            } else {
                $return['success'] = false;
                $return['message'] = "Branch not deleted.";
            }
        }
        return response()->json($return);
    }
}
