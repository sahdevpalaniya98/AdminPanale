<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\PayWorkersWallet;
use Auth;
use Validator;
use DataTables;
use Session;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:inventory-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:inventory-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:inventory-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:inventory-delete', ['only' => ['destroy']]);
        $this->middleware('permission:inventory-view', ['only' => ['view']]);
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Inventory List';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );
            if (Auth::user()->can('inventory-add')) {
                $data['btnadd'][] = array(
                    'link' => route('admin.inventory.add'),
                    'title' => 'Add Inventory'
                );
            }
            $data['breadcrumb'][] = array(
                'title' => 'List'
            );
            return view('admin.inventory.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }


    public function datatable(Request $request)
    {
        $inventory = Inventory::query()->with('employee')->with('customer')->with('phone_model');
        if (!in_array(1, auth()->user()->roles() ->pluck('role_id')->toArray())) {
            $inventory->where('branch_id',auth()->user()->branch_id);
        }
        if (isset($request->filter['brand_id']) && $request->filter['brand_id'] != null) {
            $inventory->where('brand_id', $request->filter['brand_id']);
        }
        if (isset($request->filter['category_id']) && $request->filter['category_id'] != null) {
            $inventory->where('category_id', $request->filter['category_id']);
        }
        if (isset($request->filter['series_id']) && $request->filter['series_id'] != null) {
            $inventory->where('series_id', $request->filter['series_id']);
        }
        if (isset($request->filter['model_id']) && $request->filter['model_id'] != null) {
            $inventory->where('model_id', $request->filter['model_id']);
        }
        if (isset($request->filter['employee_id']) && $request->filter['employee_id'] != null) {
            $inventory->where('employee_id', $request->filter['employee_id']);
        }
        if (isset($request->filter['sold']) && $request->filter['sold'] != null) {
            $inventory->where('is_sold', $request->filter['sold']);
        }
        return DataTables::eloquent($inventory)
            ->addColumn('action', function ($inventory) {
                $action = '';
                if (Auth::user()->can('inventory-edit')) {
                    $action .= '<a href="' . route('admin.inventory.edit', $inventory->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('inventory-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="' . route('admin.inventory.destroy') . '" data-id="' . $inventory->id . '" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                }
                if (Auth::user()->can('inventory-view')) {
                    $action .= '<a href="' . route('admin.inventory.view', $inventory->id) . '" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-eye"></i></a>';
                }
                if (Auth::user()->can('inventory-edit')) {
                    if ($inventory->is_sold == 0) {
                        $action .= '<a href="' . route('admin.inventory.order.create', $inventory->id) . '" class="btn btn-outline-secondary btn-sm" title="Sold"><i class="bx bxs-cart-alt"></i></a>&nbsp;';
                    }
                }
                return $action;
            })
            ->filterColumn('is_sold', function ($query, $keyword) {
                if (strpos('Sold', $keyword) !== false) {
                    $query->where('is_sold', 1);
                } else if (strpos('Not Sold', $keyword) !== false) {
                    $query->where('is_sold', 0);
                }
            })
            ->filterColumn('brand_id', function ($query, $keyword) {
                $query->whereHas('phone_brand', function ($sub_q) use ($keyword) {
                    $sub_q->whereRaw("brand_name like ?", ["%$keyword%"]);
                });
            })
            ->filterColumn('category_id', function ($query, $keyword) {
                $query->whereHas('category', function ($sub_q) use ($keyword) {
                    $sub_q->whereRaw("category_name like ?", ["%$keyword%"]);
                });
            })
            ->filterColumn('series_id', function ($query, $keyword) {
                $query->whereHas('phone_serice', function ($sub_q) use ($keyword) {
                    $sub_q->whereRaw("series_name like ?", ["%$keyword%"]);
                });
            })
            ->filterColumn('model_id', function ($query, $keyword) {
                $query->whereHas('phone_model', function ($sub_q) use ($keyword) {
                    $sub_q->whereRaw("model_name like ?", ["%$keyword%"]);
                });
            })
            ->filterColumn('customer_id', function ($query, $keyword) {
                $query->whereHas('customer', function ($sub_q) use ($keyword) {
                    $sub_q->whereRaw("name like ?", ["%$keyword%"]);
                });
            })
            ->filterColumn('phone_grade_id', function ($query, $keyword) {
                $query->whereHas('phone_grade', function ($sub_q) use ($keyword) {
                    $sub_q->whereRaw("grade_name like ?", ["%$keyword%"]);
                });
            })
            ->editColumn('is_sold', function ($order) {
                return ($order->is_sold == true) ? '<span class="badge rounded-pill badge-soft-success font-size-13">Sold</span>' : '<span class="badge rounded-pill badge-soft-danger font-size-13">Not Sold</span>';
            })
            ->filterColumn('branch_id', function ($query, $keyword) {
                $query->whereHas('branch', function ($sub_q) use ($keyword) {
                    $sub_q->whereRaw("name like ?", ["%$keyword%"]);
                });
            })
            ->editColumn('created_at', function ($inventory) {
                return date('d/m/Y h:i A', strtotime($inventory->created_at));
            })
            ->editColumn('customer_id', function ($inventory) {
                return (isset($inventory->customer) && $inventory->customer->name != "") ? $inventory->customer->name : '-';
            })
            ->editColumn('phone_grade_id', function ($inventory) {
                return (isset($inventory->phone_grade) && $inventory->phone_grade->grade_name != "") ? $inventory->phone_grade->grade_name : '-';
            })
            ->editColumn('employee_id', function ($inventory) {
                return (isset($inventory->employee_name) && $inventory->employee_name->name != "") ? $inventory->employee_name->name : '-';
            })
            ->editColumn('bettry_health', function ($inventory) {
                return $inventory->bettry_health != "" ? $inventory->bettry_health . '%' : '-';
            })
            ->editColumn('model_id', function ($inventory) {
                // return (isset($inventory->phone_model) && $inventory->phone_model->model_name != "") ? $inventory->phone_model->model_name : '-';
                return '<a href="' . route('admin.inventory.view', $inventory->id) . '" style="color:#495057" title="Edit">' . (isset($inventory->phone_model) && $inventory->phone_model != "" && $inventory->phone_model->model_name != "") ? $inventory->phone_model->model_name : '-' . '</a>';
            })
            ->editColumn('brand_id', function ($inventory) {
                return (isset($inventory->phone_brand) && $inventory->phone_brand->brand_name != "") ? $inventory->phone_brand->brand_name : '-';
            })
            ->editColumn('category_id', function ($inventory) {
                return (isset($inventory->category) && $inventory->category->category_name != "") ? $inventory->category->category_name : '-';
            })
            ->editColumn('series_id', function ($inventory) {
                return (isset($inventory->phone_serice) && $inventory->phone_serice->series_name != "") ? $inventory->phone_serice->series_name : '-';
            })
            ->editColumn('branch_id', function ($inventory) {
                return (isset($inventory->branch) && $inventory->branch->name != "") ? $inventory->branch->name : '-';;
            })
            ->editColumn('purchase_price', function ($inventory) {
                return '$' . number_format($inventory->purchase_price, 2);
            })
            ->editColumn('imei_number', function ($inventory) {
                $qr  =  (isset($inventory->imei_number) && $inventory->imei_number != "") ? \DNS1D::getBarcodeHTML($inventory->imei_number, "C128" ,1,25) : '-';

                $qr .=  (isset($inventory->imei_number) && $inventory->imei_number != "") ? '<p style="font-size: 10px"> IMEI: '.$inventory->imei_number.'</p> ': '';
                return $qr;
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action', 'status', 'is_sold', 'customer_id', 'model_id', 'employee_id','imei_number'])
            ->make(true);
    }

    public function create()
    {
        try {
            $data = [];
            $data['page_title'] = 'Add Inventory';
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
                'title' => 'Add'
            );
            return view('admin.inventory.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'customer' => 'required',
                'brand_id' => 'required',
                'series_id' => 'required',
                'purchase_price' => 'required',
                'phone_notes' => 'required',
                'payment_mode' => 'required',
                'employee_id' => 'required',
            ];

            if ($request->selected_brand_text === 'apple' || $request->selected_brand_text === 'samsung') {
                // $rules = [
                //     'phone_sickw_details' => 'required',
                // ];
                if (! $request->has('inventory_id')) {
                    $rules['imei_number'] = 'required';
                    $messages = [
                        'imei_number.required' => 'The imei number field is required.',
                    ];
                }
            }

            $messages = [];



            $validator = Validator::make($request->all(), $rules, $messages);

            // dd($request->all(), $validator->errors()->toArray());

            if (! $validator->fails()) {

                if ($request->has('inventory_id')) {
                    $inventory = Inventory::find($request->inventory_id);
                    $inventory->updated_at = date('Y-m-d H:i:s');
                    $action = "Update";
                } else {
                    $inventory = new Inventory();
                    $action = "Added";
                }
                $inventory->customer_id = $request->customer;
                $inventory->brand_id = $request->brand_id;
                $inventory->series_id = $request->series_id;
                $inventory->model_id = $request->model_id;
                $inventory->phone_grade_id = $request->phone_grade;
                $inventory->branch_id = (isset(auth()->user()->branch_id) && auth()->user()->branch_id != "") ? auth()->user()->branch_id : null;
                $inventory->employee_id = $request->employee_id;
                $inventory->phone_name = $request->phone_name;
                $inventory->pay_worker_id = $request->pay_worker_id;
                $inventory->phone_sickw_details = (isset($request->phone_sickw_details) && $request->phone_sickw_details != "") ? $request->phone_sickw_details : null;
                $inventory->imei_number = (isset($request->imei_number) && $request->imei_number != "") ? $request->imei_number : null;
                $inventory->serial_number = (isset($request->serial_number) && $request->serial_number != "") ? $request->serial_number : null;
                $inventory->bettry_health = (isset($request->bettry_health) && $request->bettry_health != "") ? $request->bettry_health : null;
                $inventory->purchase_price = str_replace('$ ', '', $request->purchase_price);
                $inventory->category_id = (isset($request->category_id) && $request->category_id != "") ? $request->category_id : null;
                $inventory->save();
                $inventory_payment_mode_sync_data = [];
                $amount_arr = $request->payment_mode_amount;
                if ($request->payment_mode) {
                    foreach ($request->payment_mode as $key => $mode) {
                        $inventory_payment_mode_sync_data[$mode] = [
                            'amount' => str_replace('$ ', '', $amount_arr[$key]),
                        ];
                    }
                }
                $shipping_amount = 0;
                $inventory_expense_sync_data = [];
                if ($request->damages) {
                    foreach ($request->damages as $key => $expense) {
                        if (isset($expense['damage_id']) && $expense['damage_id'] != null && $expense['damage_amount'] != "") {
                            if ($expense['expense_name'] == 'Shipping') {
                                $shipping_amount = (int) str_replace('$ ', '', $expense['damage_amount']);
                            }
                            $inventory_expense_sync_data[$expense['damage_id']] = [
                                'amount' => str_replace('$ ', '', $expense['damage_amount']),
                            ];
                        }
                    }
                }
                if (! $request->has('inventory_id') && $shipping_amount > 0) {
                    $pay_worker_amount = new PayWorkersWallet;
                    $pay_worker_amount->pay_worker_id = $request->pay_worker_id;
                    $pay_worker_amount->inventory_id = $inventory->id;
                    $pay_worker_amount->status = 'CREDIT';
                    $pay_worker_amount->notes = 'Shipping of #' . $inventory->id . ' Inventory';
                    $pay_worker_amount->amount = $shipping_amount;
                    $pay_worker_amount->save();
                } else {
                    $pay_worker_amount = PayWorkersWallet::where('inventory_id', $inventory->id)->first();
                    if ($pay_worker_amount) {
                        $pay_worker_amount->amount = $shipping_amount;
                        $pay_worker_amount->save();
                    }
                }
                $inventory->payment_modes()->sync($inventory_payment_mode_sync_data);
                if ($inventory_expense_sync_data != '') {
                    $inventory->expenses()->sync($inventory_expense_sync_data);
                }
                $inventory->phone_damages()->sync($request->phone_notes);
                $inventory->inventory_variant()->sync($request->variant_id);

                Session::flash('alert-message', 'Inventory ' . $action . ' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.inventory.index');
            } else {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
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
            foreach ($request->all_inventory as $key => $data) {
                $inventory = new Inventory();
                $action = "Added";
                $inventory->customer_id = $data['customer'];
                $inventory->brand_id = $data['brand_id'];
                $inventory->series_id = $data['series_id'];
                $inventory->model_id = $data['model_id'];
                $inventory->branch_id = (isset(auth()->user()->branch_id) && auth()->user()->branch_id != "") ? auth()->user()->branch_id : null;
                $inventory->phone_grade_id = (isset($data['phone_grade']) && $data['phone_grade'] != "") ? $data['phone_grade'] : null;
                $inventory->employee_id = (isset($data['employee_id']) && $data['employee_id'] != "") ? $data['employee_id'] : null;
                $inventory->phone_name = (isset($data['phone_name']) && $data['phone_name'] != "") ? $data['phone_name'] : null;
                $inventory->pay_worker_id = (isset($data['pay_worker_id']) && $data['pay_worker_id'] != "") ? $data['pay_worker_id'] : null;
                $inventory->phone_sickw_details = (isset($data['phone_sickw_details']) && $data['phone_sickw_details'] != "") ? $data['phone_sickw_details'] : null;
                $inventory->imei_number = (isset($data['imei_number']) && $data['imei_number'] != "") ? $data['imei_number'] : null;
                $inventory->serial_number = (isset($data['serial_number']) && $data['serial_number'] != "") ? $data['serial_number'] : null;
                $inventory->bettry_health = (isset($data['bettry_health']) && $data['bettry_health'] != "") ? $data['bettry_health'] : null;
                $inventory->purchase_price = str_replace('$ ', '', $data['purchase_price']);
                $inventory->category_id = (isset($data['category_id']) && $data['category_id'] != "") ? $data['category_id'] : null;
                $inventory->save();
                $inventory_payment_mode_sync_data = [];
                $amount_arr = $data['payment_mode'];
                $payment_amounts = [];
                if (isset($data['arrya_of_amount']) && $data['arrya_of_amount'] != "") {
                    $payment_amounts = explode(',', $data['arrya_of_amount']);
                }
                if ($data['payment_mode']) {
                    foreach ($data['payment_mode'] as $key => $mode) {
                        $inventory_payment_mode_sync_data[$mode] = [
                            'amount' => str_replace('$ ', '', $payment_amounts[$key]),
                        ];
                    }
                }
                $shipping_amount = 0;
                $inventory_expense_sync_data = [];
                if ($data['expense']) {
                    foreach ($data['expense'] as $key => $expense_data) {
                        if ($expense_data['damage_id'] != null && $expense_data['damage_amount'] != "") {
                            if ($expense_data['expense_name'] == 'Shipping') {
                                $shipping_amount = (int) str_replace('$ ', '', $expense_data['damage_amount']);
                            }
                            $inventory_expense_sync_data[$expense_data['damage_id']] = [
                                'amount' => str_replace('$ ', '', $expense_data['damage_amount']),
                            ];
                        }
                    }
                }
                $inventory->payment_modes()->sync($inventory_payment_mode_sync_data);
                if ($inventory_expense_sync_data != '') {
                    $inventory->expenses()->sync($inventory_expense_sync_data);
                }
                $inventory->phone_damages()->sync($data['phone_notes']);
                if (isset($data['variant_id']) && $data['variant_id'] != "") {

                    $inventory->inventory_variant()->sync($data['variant_id']);
                }

            }
            Session::flash('alert-message', 'Inventory ' . $action . ' successfully.');
            Session::flash('alert-class', 'success');
            return redirect()->route('admin.inventory.index');
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
            $data['page_title'] = 'Edit Inventory';
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
                'title' => 'Edit'
            );
            $inventory = Inventory::find($id);
            if ($inventory) {
                $data['inventory'] = $inventory;
                return view('admin.inventory.edit', $data);
            }
            return abort(404);
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
            // dd($inventory->inventory_items);
            if ($inventory) {
                $data['inventory'] = $inventory;
                return view('admin.inventory.view', $data);
            }
            return abort(404);
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
                $inventory = Inventory::where('id', $request->id)->first();
                if (! is_null($inventory)) {
                    $inventory->payment_modes()->detach();
                    $inventory->phone_damages()->detach();
                    if ($inventory->delete()) {
                        $response['success'] = true;
                        $response['message'] = "Inventory deleted successfully.";
                    } else {
                        $response['success'] = false;
                        $response['message'] = "Inventory deleted unsuccessfully.";
                    }
                }
            } catch (\Exception $e) {
                $response['success'] = false;
                $response['message'] = $e->getMessage();
            }
            return response()->json($response);
        }
    }

    public function order_create(Request $request)
    {
        try {
            // dd();
            $data = [];
            $data['page_title'] = 'Sold Inventory';
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
                'title' => 'Sold Inventory'
            );
            $data['inventory_data'] = Inventory::where('id', $request->id)->first();
            return view('admin.inventory.order', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function order_store(Request $request)
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

                $order = new Order();
                $action = 'added';
                $order->buyer_id = $request->buyer_id;
                $order->pay_worker_id = $request->pay_worker_id;
                $order->sell_amount = str_replace('$ ', '', $request->sell_amount);
                $order->save();
                $this->create_order_status_history($order->id, $order->order_status, 'in-progress');
                if ($request->inventorys) {
                    foreach ($request->inventorys as $key => $value) {
                        $inventory = Inventory::where('id', $value['inventory_id'])->first();
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
                        if (isset($value['damage_id']) && $value['damage_id'] != "" && $value['damage_amount'] != "") {

                            if ($value['expense_name'] == 'Shipping') {
                                $shipping_amount = (int) str_replace('$ ', '', $value['damage_amount']);
                            }
                            $damages_sync_data[$value['damage_id']] = [
                                'amount' => str_replace('$ ', '', $value['damage_amount'])
                            ];
                        }
                    }
                    if (isset($damages_sync_data) && $damages_sync_data != "") {
                        $order->phone_damages()->sync($damages_sync_data);
                    }
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

                Session::flash('alert-message', 'Inventory Sold ' . $action . ' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.inventory.index');
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
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
