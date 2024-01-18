<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMode;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class PaymentModeController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:payment-mode-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:payment-mode-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:payment-mode-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:payment-mode-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Payment Mode List';
            if  (Auth::user()->can('payment-mode-add')) {
                $data['btnadd'][]       = array(
                    'link'  => route('admin.payment.mode.add'),
                    'title' => 'Add Payment Mode'
                );
            }
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.payment-mode.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        $payment_mode = PaymentMode::query();
        return DataTables::eloquent($payment_mode)
            ->addColumn('action', function ($payment_mode) {
                $action      = '';
                if (Auth::user()->can('payment-mode-edit')) {
                    $action .= '<a href="'.route('admin.payment.mode.edit', $payment_mode->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('payment-mode-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.payment.mode.destroy').'" data-id="'.$payment_mode->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('created_at', function ($payment_mode) {
                return date('d/m/Y h:i A', strtotime($payment_mode->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action','address'])->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Add Payment Mode';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('payment-mode-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.payment.mode.index'),
                    'title' => 'Payment Mode'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            return view('admin.payment-mode.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $payment_modeId   = ($request->payment_mode_id) ? $request->payment_mode_id : '';
            $rules = [
                'payment_name'=> 'required',
                'remark'=> 'required',

            ];
        
            $messages = [
                'payment_name.required' => 'The payment name field is required.',
                'remark.required' => 'The remark field is required.',

            ];
            $validator      = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                if ($payment_modeId != '') {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                
                if($payment_modeId != "")
                {
                    $payment_mode   = PaymentMode::where('id', $request->payment_mode_id)->whereNull('deleted_at')->first();
                    $action     = "Update";
                }else{
                    $payment_mode   = new PaymentMode();
                    $action     = "Added";
                }
                $payment_mode->payment_name           = $request->payment_name ; 
                $payment_mode->remark       = (isset($request->remark) && $request->remark != "") ? $request->remark : null; 
                
               if($payment_mode->save())
               {
                Session::flash('alert-message', 'Payment Mode '.$action.' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.payment.mode.index');
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
            $data['page_title']         = 'Edit Payment Mode';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('payment-mode-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.payment.mode.index'),
                    'title' => 'Payment Mode'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Edit'
            );
            $payment_mode                       = PaymentMode::where('id', $id)->first();
            if ($payment_mode) {
                $data['payment_mode']           = $payment_mode;
                return view('admin.payment-mode.edit', $data);
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
                $payment_mode = PaymentMode::where('id', $request->id)->first();

                if ($payment_mode->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Payment Mode deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Payment Mode not deleted.";
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
