<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayWorker;
use App\Models\Order;
use App\Models\PayWorkersWallet;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class PayWorkerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pay-worker-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:pay-worker-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:pay-worker-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:pay-worker-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Pay Worker List';
            if  (Auth::user()->can('pay-worker-add')) {
                $data['btnadd'][]       = array(
                    'link'  => route('admin.pay.worker.add'),
                    'title' => 'Add Pay Worker'
                );
            }
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.pay-worker.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        $worker = PayWorker::query();
        return DataTables::eloquent($worker)
            ->addColumn('action', function ($worker) {
                $action      = '';
                if (Auth::user()->can('pay-worker-edit')) {
                    $action .= '<a href="'.route('admin.pay.worker.edit', $worker->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('pay-worker-edit')) {
                    $action .= '<a href="'.route('admin.pay.worker.wallet', $worker->id).'" class="btn btn-outline-secondary btn-sm" title="Wallet"><i class="fa fa-plus"></i></a>&nbsp;';
                }
                if (Auth::user()->can('pay-worker-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.pay.worker.destroy').'" data-id="'.$worker->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('pay-worker-view')) {
                    $action .= '<a href="'.route('admin.pay.worker.view', $worker->id).'" class="btn btn-outline-secondary btn-sm" title="View"><i class="fas fa-list"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('created_at', function ($worker) {
                return date('d/m/Y h:i A', strtotime($worker->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action'])->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Add Pay Worker';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('pay-worker-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.pay.worker.index'),
                    'title' => 'Pay Worker'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            return view('admin.pay-worker.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $workerId   = ($request->worker_id) ? $request->worker_id : '';
            $rules = [
                'name'=> 'required',
                'phone_number'=> 'required|max:10',

            ];

            $messages = [
                'name.required' => 'The name field is required.',
                'phone_number.required' => 'The phone number field is required.',

            ];
            $validator      = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                if ($workerId != '') {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {

                if($workerId != "")
                {
                    $worker     = PayWorker::where('id', $request->worker_id)->whereNull('deleted_at')->first();
                    $action     = "Update";
                }else{
                    $worker     = new PayWorker();
                    $action     = "Added";
                }
                $worker->name   = $request->name;
                $worker->phone_number   = $request->phone_number;

               if($worker->save())
               {
                Session::flash('alert-message', 'Pay Worker '.$action.' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.pay.worker.index');
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
            $data['page_title']         = 'Edit Pay Worker';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('pay-worker-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.pay.worker.index'),
                    'title' => 'Pay Worker'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Edit'
            );
            $worker                     = PayWorker::where('id', $id)->first();
            if ($worker) {
                $data['worker']         = $worker;
                return view('admin.pay-worker.edit', $data);
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
                $worker = PayWorker::where('id', $request->id)->first();

                if ($worker->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Pay worker deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Pay Worker not deleted.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function wallet($id)
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Pay Worker Wallet';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('pay-worker-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.pay.worker.index'),
                    'title' => 'Pay Worker'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Worker Wallet'
            );
            $data['pay_worker_id']       = $id;
            $data['total_wallet_amount'] = number_format(PayWorkersWallet::where('pay_worker_id','=',$id)->where('status', 'CREDIT')->sum('amount') - PayWorkersWallet::where('pay_worker_id','=',$id)->where('status', 'DEBIT')->sum('amount'), 2);
            return view('admin.pay-worker.wallet', $data);

        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function wallet_store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required',
                'amount_type' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->all()
                ]);
            }else{
                $pay_worker_amount                  = new PayWorkersWallet;
                $pay_worker_amount->pay_worker_id   = $request->pay_worker_id;
                $pay_worker_amount->status          = $request->amount_type;
                $pay_worker_amount->amount          = $request->amount;
                if($pay_worker_amount->save())
                {
                    $response['status']              = true;
                    $response['message']             = 'Pay worker wallet amount added successfully';
                    $response['total_wallet_amount'] = number_format(PayWorkersWallet::where('pay_worker_id','=',$request->pay_worker_id)->where('status', 'CREDIT')->sum('amount') - PayWorkersWallet::where('pay_worker_id','=',$request->pay_worker_id)->where('status', 'DEBIT')->sum('amount'), 2);
                    return response()->json($response, 200);
                }
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function wallet_datatable(Request $request)
    {
        try{
            $wallet = PayWorkersWallet::query();
            $wallet = $wallet->where('pay_worker_id',$request->id);

            return DataTables::eloquent($wallet)
            ->editColumn('created_at', function ($wallet) {
                return date('d/m/Y h:i A', strtotime($wallet->created_at));
            })
            ->editColumn('notes', function ($wallet) {
                if ($wallet->notes != '') {
                    if ($wallet->order_id != '') {
                        return '<a target="_blank" href="'.route('admin.order.view', $wallet->order_id).'">'.$wallet->notes.'</a>';
                    } else if ($wallet->inventory_id != '') {
                        return '<a target="_blank" href="'.route('admin.inventory.view', $wallet->inventory_id).'">'.$wallet->notes.'</a>';
                    }
                }
                return '-';
            })
            ->editColumn('amount', function ($wallet) {
                if ($wallet->status == 'CREDIT') {
                    return '<span class="text-success">$'.number_format($wallet->amount, 2).'</span>';
                } else {
                    return '<span class="text-danger">$'.number_format($wallet->amount, 2).'</span>';
                }
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['notes', 'amount'])->addIndexColumn()
            ->make(true);
        }catch(\Exception $e)
        {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function view($id)
    {
        try {
            $data                       = [];
            $data['page_title']         = 'View Pay Worker';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('pay-worker-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.pay.worker.index'),
                    'title' => 'Pay Worker'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'View'
            );
            $data['pay_worker_id']         = $id;
            return view('admin.pay-worker.view', $data);

        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function pay_worker_datatable(Request $request)
    {
        $order = Order::where('pay_worker_id', $request->filter['pay_worker_id']);

        return DataTables::eloquent($order)

            ->editColumn('order_status', function($order) {
                if ($order->order_status == 'in-progress') {
                    return '<span class="badge rounded-pill badge-soft-warning font-size-13">In Progress</span>';
                }
                if ($order->order_status == 'complete') {
                    return '<span class="badge rounded-pill badge-soft-success font-size-13">Completed</span>';
                }
                if ($order->order_status == 'return') {
                    return '<span class="badge rounded-pill badge-soft-danger font-size-13">Returned</span>';
                }
                if ($order->order_status == 'cancel') {
                    return '<span class="badge rounded-pill badge-soft-danger font-size-13">Cancelled</span>';
                }
            })
            ->editColumn('pay_worker_id', function($order) {
                return $order->pay_worker ? $order->pay_worker->name : '-';
            })
            ->editColumn('created_at', function ($order) {
                return date('d/m/Y h:i A', strtotime($order->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action','order_status'])->addIndexColumn()
            ->make(true);
    }
}
