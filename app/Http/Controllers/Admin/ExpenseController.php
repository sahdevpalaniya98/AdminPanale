<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Order;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class ExpenseController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:expense-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:expense-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:expense-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:expense-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Expense List';
            if  (Auth::user()->can('expense-add')) {
                $data['btnadd'][]       = array(
                    'link'  => route('admin.expense.add'),
                    'title' => 'Add Expense'
                );
            }
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.expense.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request)
    {
        $expense = Expense::query();
        return DataTables::eloquent($expense)
            ->addColumn('action', function ($expense) {
                $action      = '';
                if (Auth::user()->can('expense-edit') && $expense->expense_name != 'Shipping') {
                    $action .= '<a href="'.route('admin.expense.edit', $expense->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('expense-delete') && $expense->expense_name != 'Shipping') {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.expense.destroy').'" data-id="'.$expense->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('note', function ($expense) {
                return ($expense->note) ? $expense->note : '-';
            })
            ->editColumn('created_at', function ($expense) {
                return date('d/m/Y h:i A', strtotime($expense->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action','address','note'])->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Add Expense';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('Expense-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.expense.index'),
                    'title' => 'Expense'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            return view('admin.expense.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $expenseId   = ($request->expense_id) ? $request->expense_id : '';
            $rules = [
                'expense_name'=> 'required|unique:expenses,expense_name,NULL,id,deleted_at,NULL',
            ];
        
            $messages = [
                'expense_name.required' => 'The expense name field is required.',
            ];
            $validator      = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                if ($expenseId != '') {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                
                if($expenseId != "")
                {
                    $expense   = Expense::where('id', $request->expense_id)->whereNull('deleted_at')->first();
                    $action     = "Update";
                }else{
                    $expense   = new Expense();
                    $action     = "Added";
                }
                $expense->expense_name          = $request->expense_name; 
                $expense->note       = (isset($request->note) && $request->note != "") ? $request->note : null; 
                
               if($expense->save())
               {
                Session::flash('alert-message', 'Expense '.$action.' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.expense.index');
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
            $data['page_title']         = 'Edit Expense';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('expense-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.expense.index'),
                    'title' => 'Expense'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Edit'
            );
            $expense                       = Expense::where('id', $id)->first();
            if ($expense) {
                $data['expense']           = $expense;
                return view('admin.expense.edit', $data);
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
                $expense = Expense::where('id', $request->id)->first();

                if ($expense->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Expense deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Expense not deleted.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function order_count($id)
    {  
        try {
            $data                       = [];
            $data['page_title']         = 'Order History';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'Order History'
            );
            $data['order']     = Order::with('pay_worker')->where('id',$id)->first();
            return view('admin.order.order_view',$data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }
}
