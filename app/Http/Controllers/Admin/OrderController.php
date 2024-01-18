<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Buyer;
use App\Models\Inventory;
use App\Models\OrderStatusHistory;
use App\Models\PayWorkersWallet;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;
use DB;
use PDF;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:order-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
        $this->middleware('permission:order-view', ['only' => ['view']]);
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Phone Selling';
            if (Auth::user()->can('order-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.order.add'),
                    'title' => 'Add Phone Selling'
                );
            }
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][] = array(
                'title' => 'List'
            );
            return view('admin.order.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function datatable(Request $request)
    {
        $order = Order::query()->with('buyer');
        if (! in_array(1, auth()->user()->roles()->pluck('role_id')->toArray())) {
            $order->whereHas('inventory_items', function ($sub_q) {
                $sub_q->where('branch_id', auth()->user()->branch_id);
            });
        }
        return DataTables::eloquent($order)
            ->addColumn('action', function ($order) {
                $action = '';
                if (Auth::user()->can('order-edit') && $order->order_status === 'in-progress') {
                    $action .= '<a href="' . route('admin.order.edit', $order->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                // if (Auth::user()->can('order-delete') && $order->order_status === 'in-progress') {
                //     $action .= '<button class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.order.destroy').'" data-id="'.$order->id.'" title="Delete"><i class="fas fa-trash-alt"></i></button>&nbsp;';
                // }
                if (Auth::user()->can('order-edit') && $order->order_status === 'in-progress') {
                    $action .= '<button class="btn btn-outline-success btn-sm btnCompleteOrder" data-url="' . route('admin.order.mark_as_complete') . '" data-selling-price="' . $order->sell_amount . '" data-id="' . $order->id . '" data-bs-toggle="modal" data-bs-target="#order_complete_modal" title="Complete Order"><i class="fas fa-check"></i></button>&nbsp;';
                }
                if (Auth::user()->can('order-edit') && $order->order_status === 'complete') {
                    $action .= '<button class="btn btn-outline-danger btn-sm return_order" title="Return Order" data-id="' . $order->id . '" data-url="' . route('admin.order.return_order') . '"><i class="fas fas fa-undo-alt"></i></button>&nbsp;';
                }
                if (Auth::user()->can('order-edit') && $order->order_status === 'in-progress') {
                    $action .= '<button data-id="' . $order->id . '" data-url="' . route('admin.order.cancel_order') . '" class="btn btn-outline-danger btn-sm cancel_order" title="Cancel Order"><i class="fas fa-window-close"></i></button>&nbsp;';
                }
                if (Auth::user()->can('order-view')) {
                    $action .= '<a href="' . route('admin.order.view', $order->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                return $action;
            })
            ->filterColumn('order_status', function ($query, $keyword) {
                if (str_contains('In Progress', $keyword)) {
                    $query->where('order_status', 'in-progress');
                } else if (str_contains('Completed', $keyword)) {
                    $query->where('order_status', 'complete');
                } else if (str_contains('Return', $keyword)) {
                    $query->where('order_status', 'return');
                } else if (str_contains('Cancel', $keyword)) {
                    $query->where('order_status', 'cancel');
                }
            })
            ->editColumn('created_at', function ($order) {
                return date('d/m/Y h:i A', strtotime($order->created_at));
            })
            ->editColumn('buyer_id', function ($order) {
                return (isset($order->buyer) && $order->buyer != null && $order->buyer->company_name != null) ? $order->buyer->company_name : "-";
            })
            ->editColumn('order_status', function ($order) {
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
            ->editColumn('sell_amount', function ($order) {
                return '$' . number_format($order->sell_amount, 2);
            })
            ->editColumn('pay_worker_id', function ($order) {
                return $order->pay_worker ? $order->pay_worker->name : '-';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action', 'status', 'order_status', 'buyer_id'])
            ->make(true);
    }

    public function create()
    {
        try {
            $data = [];
            $data['page_title'] = 'Add Phone Selling';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('order-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.order.index'),
                    'title' => 'Phone Selling'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'Add'
            );
            return view('admin.order.add', $data);
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
                'buyer_id' => 'required',
            ];

            $messages = [
                'buyer_id.required' => 'The company name field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->has('order_id')) {
                    return redirect()->route('admin.order.edit', $request->order_id)
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                if ($request->has('order_id')) {
                    $order = Order::where('id', $request->order_id)->first();
                    $action = 'updated';
                } else {
                    $order = new Order();
                    $action = 'added';
                }

                $order->buyer_id = $request->buyer_id;
                $order->pay_worker_id = $request->pay_worker_id;
                $order->sell_amount = str_replace('$ ', '', $request->sell_amount);
                $order->save();
                if (! $request->has('order_id')) {
                    $this->create_order_status_history($order->id, $order->order_status, 'in-progress');
                }
                if ($request->inventorys) {
                    foreach ($request->inventorys as $key => $value) {
                        $inventory = Inventory::where('id', $value)->first();
                        $inventory->is_sold = 1;
                        $inventory->save();
                        $inventory_sync_data[$value['inventory_id']] = [
                            'amount' => str_replace('$ ', '', $value['per_amount'])
                        ];
                    }
                    $order->inventory_items()->sync($inventory_sync_data);
                }
                $shipping_amount = 0;
                if ($request->damages) {
                    foreach ($request->damages as $key => $value) {
                        if ($value['expense_name'] == 'Shipping') {
                            $shipping_amount = (int) str_replace('$ ', '', $value['damage_amount']);
                        }
                        $damages_sync_data[$value['damage_id']] = [
                            'amount' => str_replace('$ ', '', $value['damage_amount'])
                        ];
                    }
                    $order->phone_damages()->sync($damages_sync_data);
                }

                if (! $request->has('order_id') && $shipping_amount > 0) {
                    $pay_worker_amount = new PayWorkersWallet;
                    $pay_worker_amount->pay_worker_id = $request->pay_worker_id;
                    $pay_worker_amount->order_id = $order->id;
                    $pay_worker_amount->status = 'CREDIT';
                    $pay_worker_amount->notes = 'Shipping of #' . $order->id . ' Order';
                    $pay_worker_amount->amount = $shipping_amount;
                    $pay_worker_amount->save();
                } else {
                    $pay_worker_amount = PayWorkersWallet::where('order_id', $order->id)->first();
                    if ($pay_worker_amount) {
                        $pay_worker_amount->amount = $shipping_amount;
                        $pay_worker_amount->save();
                    }
                }

                Session::flash('alert-message', 'Order ' . $action . ' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.order.index');
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function view($id)
    {
        try {
            $data = [];
            $data['page_title'] = 'View Order';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('order-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.order.index'),
                    'title' => 'Order'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'View'
            );
            $order = Order::find($id);
            if ($order) {
                $data['order'] = $order;
                return view('admin.order.view', $data);
            }
            return abort(404);
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
            $data['page_title'] = 'Edit Order';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('order-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.order.index'),
                    'title' => 'Order'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'Edit'
            );
            $order = Order::with('inventory_items', 'phone_damages')->where('id', $id)->first();
            if ($order) {
                $data['order'] = $order;
                return view('admin.order.edit', $data);
            }
            return abort(404);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function invenroty_view($id)
    {
        try {
            $data = [];
            $data['page_title'] = 'View Inventory';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('inventory-list')) {
                $data['breadcrumb'][] = array(
                    'link' => route('admin.inventory.index'),
                    'title' => 'Inventory'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'View'
            );
            $inventory = Inventory::find($id);
            if ($inventory) {
                $data['inventory'] = $inventory;
                return view('admin.inventory.inventory_view', $data);
            }
            return abort(404);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function invoice_download($oid)
    {
        $order = Order::find($oid);
        $data['order'] = $order;

        $pdf = PDF::loadView('admin.order.pdf', $data);

        // return $pdf->stream('invoice.pdf');
        return $pdf->download('invoice_' . $oid . '.pdf');
    }

    public function mark_as_complete(Request $request)
    {
        try {
            $order = Order::find($request->order_id);
            $create_order_status_history = $this->create_order_status_history($request->order_id, $order->order_status, 'complete');

            if ($create_order_status_history) {
                $paymnt_mode_arr = [];
                $payment_amount_arr = $request->payment_mode_amount;

                if ($request->payment_mode) {
                    foreach ($request->payment_mode as $key => $payment_mode) {
                        $paymnt_mode_arr[$payment_mode] = [
                            'amount' => str_replace('$ ', '', $payment_amount_arr[$key])
                        ];
                    }
                }

                $order->order_status = 'complete';
                $order->save();
                $order->payment_mode()->sync($paymnt_mode_arr);

                $response['success'] = true;
                $response['message'] = 'Order complete successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Order status history not created.';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function return_order(Request $request)
    {
        try {
            $order = Order::find($request->id);
            $create_order_status_history = $this->create_order_status_history($order->id, $order->order_status, 'return');
            // dd($create_order_status_history);
            if ($create_order_status_history) {
                $order->order_status = 'return';
                $order->return_reason = $request->return_reason;
                $order->save();
                if ($order->inventory_items->isNotEmpty()) {
                    foreach ($order->inventory_items as $key => $inventory) {
                        $inventory_damage_data = [];
                        if ($inventory->phone_damages->isNotEmpty()) {
                            foreach ($inventory->phone_damages as $subkey => $damages) {
                                $inventory_damage_data[$damages->id] = [
                                    'expense_amount' => 0,
                                    'order_id' => null
                                ];
                            }
                        }
                        $inventory->phone_damages()->sync($inventory_damage_data);
                        $inventory->is_sold = 0;
                        $inventory->save();
                    }
                }
                $response['success'] = true;
                $response['message'] = 'Order return successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Order status history not created.';
            }
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function cancel_order(Request $request)
    {
        try {
            $order = Order::find($request->id);
            $create_order_status_history = $this->create_order_status_history($order->id, $order->order_status, 'cancel');

            if ($create_order_status_history) {
                $order->order_status = 'cancel';
                $order->save();
                if ($order->inventory_items->isNotEmpty()) {
                    foreach ($order->inventory_items as $key => $inventory) {
                        $inventory_damage_data = [];

                        if ($inventory->phone_damages->isNotEmpty()) {
                            foreach ($inventory->phone_damages as $subkey => $damages) {
                                $inventory_damage_data[$damages->id] = [
                                    'expense_amount' => 0,
                                    'order_id' => null
                                ];
                            }
                        }

                        $inventory->phone_damages()->sync($inventory_damage_data);
                        $inventory->is_sold = 0;
                        $inventory->save();
                    }
                }

                $response['success'] = true;
                $response['message'] = 'Order cancel successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Order status history not created.';
            }
            return response()->json($response);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }



    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            try {
                $order = Order::where('id', $request->id)->first();
                if (! is_null($order)) {
                    $order->inventory_items()->detach();
                    if ($order->delete()) {
                        $response['success'] = true;
                        $response['message'] = "Order deleted successfully.";
                    } else {
                        $response['success'] = false;
                        $response['message'] = "Order deleted unsuccessfully.";
                    }
                }
            } catch (\Exception $e) {
                $response['success'] = false;
                $response['message'] = $e->getMessage();
            }
            return response()->json($response);
        } else {
            return abort(404);
        }
    }

    public function create_order_status_history($order_id, $old_status, $new_status)
    {
        $order_status = new OrderStatusHistory();
        $order_status->order_id = $order_id;
        $order_status->previous_status = $old_status;
        $order_status->current_status = $new_status;

        if ($order_status->save()) {
            return true;
        } else {
            return false;
        }
    }
}
