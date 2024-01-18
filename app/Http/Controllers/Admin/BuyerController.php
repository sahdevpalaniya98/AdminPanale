<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class BuyerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:buyer-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:buyer-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:buyer-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:buyer-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Buyer List';
            if (Auth::user()->can('buyer-add')) {
                $data['btnadd'][]       = array(
                    'link'  => route('admin.buyer.add'),
                    'title' => 'Add Buyer'
                );
            }
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.buyer.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }


    public function datatable(Request $request) {
        $buyer = Buyer::query();

        return DataTables::eloquent($buyer)
            ->addColumn('action', function ($buyer) {
                $action      = '';
                if (Auth::user()->can('buyer-edit')) {
                    $action .= '<a href="'.route('admin.buyer.edit', $buyer->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('buyer-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.buyer.destroy').'" data-id="'.$buyer->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('buyer-history')) {
                    $action .= '<a target="_blank" href="'.route('admin.buyer.history', $buyer->id).'" class="btn btn-outline-secondary btn-sm" title="History"><i class="fas fa-list"></i></a>';
                }
                return $action;
            })
            ->editColumn('created_at', function($buyer) {
                return date('d/m/Y h:i A', strtotime($buyer->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function history($id)
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Buyer History';
            $data['data_table_link']         = route('admin.buyer.history.data', $id);
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'History'
            );

            $buyer = Buyer::where('id', $id)->first();

            $order_history = OrderStatusHistory::whereHas('order', function($q) use ($id) {
                $q->where('buyer_id', $id);
            })->get();

            $completedAmount = 0;
            $refundAmount = 0;
            $inComingAmount = 0;
            $totalAmount = 0;

            foreach($order_history as $key=>$value){
                
                if ($value->current_status == 'complete'){
                    $totalAmount += $value->order->sell_amount;
                    $completedAmount = $value->order->sell_amount;
                }

                if ($value->current_status == 'return'){
                    $totalAmount -= $value->order->sell_amount;
                    $refundAmount = $value->order->sell_amount;
                }

                if ($value->current_status == 'in-progress'){
                    $inComingAmount += $value->order->sell_amount;
                }

            }

            $data['completedAmount'] = $completedAmount;
            $data['refundAmount'] = $refundAmount;
            $data['inComingAmount'] = $inComingAmount;
            $data['totalAmount'] = $totalAmount;
            $data['buyer'] = $buyer;

            return view('admin.buyer.history', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function history_datatable(Request $request, $id) {
        // $order_history = Order::query()->where('buyer_id', $id);

        $order_history = OrderStatusHistory::whereHas('order', function($q) use ($id) {
            $q->where('buyer_id', $id);
        })->with('order');

        return DataTables::eloquent($order_history)
            ->editColumn('created_at', function($order_history) {
                return date('d/m/Y h:i A', strtotime($order_history->created_at));
            })
            ->editColumn('order_items', function($order_history) {
                $html_string = '<ul>';
                if ($order_history->order->inventory_items->isNotEmpty()) {
                    foreach($order_history->order->inventory_items as $key => $item) {
                        $html_string .= '<li>'.$item->phone_brand->brand_name.'</li>';
                    }
                }
                $html_string .= '</ul>';
                return $html_string;
            })
            ->editColumn('order.sell_amount', function($order_history) {
                $sell_amount = ($order_history->order) ? $order_history->order->sell_amount : 0;

                if ($order_history->current_status == 'in-progress') {
                    return '<span class="badge rounded-pill badge-soft-warning font-size-13">$'.number_format($sell_amount, 2).'</span>';
                }
                if ($order_history->current_status == 'complete') {
                    return '<span class="badge rounded-pill badge-soft-success font-size-13">$'.number_format($sell_amount, 2).'</span>';
                }
                if ($order_history->current_status == 'return') {
                    return '<span class="badge rounded-pill badge-soft-danger font-size-13">$'.number_format($sell_amount, 2).'</span>';
                }
                if ($order_history->current_status == 'cancel') {
                    return '<span class="badge rounded-pill badge-soft-danger font-size-13">$'.number_format($sell_amount, 2).'</span>';
                }
            })
            ->editColumn('order.id', function($order_history) {
                $order_number = ($order_history->order) ? $order_history->order->id : '-';

                return '#'.$order_number;
            })

            ->filterColumn('current_status', function($query, $keyword) {
                if (str_contains('In Progress', $keyword)) {
                    $query->where('current_status', 'in-progress');
                } else if (str_contains('Completed', $keyword)) {
                    $query->where('current_status', 'complete');
                } else if (str_contains('Returned', $keyword)) {
                    $query->where('current_status', 'return');
                } else if (str_contains('Cancel', $keyword)) {
                    $query->where('current_status', 'cancel');
                }
            })

            ->editColumn('current_status', function($order_history) {
                if ($order_history->current_status == 'in-progress') {
                    return '<span class="badge rounded-pill badge-soft-warning font-size-13">In Progress</span>';
                }
                if ($order_history->current_status == 'complete') {
                    return '<span class="badge rounded-pill badge-soft-success font-size-13">Completed</span>';
                }
                if ($order_history->current_status == 'return') {
                    return '<span class="badge rounded-pill badge-soft-danger font-size-13">Returned</span>';
                }
                if ($order_history->current_status == 'cancel') {
                    return '<span class="badge rounded-pill badge-soft-danger font-size-13">Cancelled</span>';
                }
            })

            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['order_items','order.sell_amount','current_status'])
            ->make(true);
    }

    public function create()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Add Buyer';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('buyer-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.buyer.index'),
                    'title' => 'Buyer'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            return view('admin.buyer.add', $data);
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
                'company_name' => 'required',
                'company_number' => 'required',
                'contact_person_name' => 'required',
                'contact_person_mobile_number' => 'required',
            ];

            $messages = [
                'company_name.required' => 'The company name field is required.',
                'company_number.required' => 'The company number field is required.',
                'contact_person_name.required' => 'The contact person name field is required.',
                'contact_person_mobile_number.required' => 'The contact person mobile number field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->has('buyer_id')) {
                    return redirect()->route('admin.buyer.edit', $request->buyer_id)
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
            } else {
                if ($request->has('buyer_id')) {
                    $buyer = Buyer::where('id', $request->buyer_id)->first();
                    $action = 'updated';
                } else {
                    $buyer = new Buyer();
                    $action = 'added';
                }

                $buyer->company_name = $request->company_name;
                $buyer->company_number = $request->company_number;
                $buyer->contact_person_name = $request->contact_person_name;
                $buyer->contact_person_mobile_number = $request->contact_person_mobile_number;
                $buyer->address = $request->address;

                $buyer->save();
                Session::flash('alert-message', 'Buyer '.$action.' successfully.');
                Session::flash('alert-class','success');
                return redirect()->route('admin.buyer.index');
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function edit($id) {
        try {
            $data['page_title'] = 'Buyer';
            $data['breadcrumb'][] = array(
                'link' => route('admin.home'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'link' => route('admin.buyer.index'),
                'title' => 'Buyer List'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Buyer'
            );

            $buyer = Buyer::find($id);

            if ($buyer) {
                $data['buyer'] = $buyer;
                return view('admin.buyer.add', $data);
            } else {
                return abort(404);
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function destroy(Request $request) {
        try {
            if ($request->ajax()) {
                $buyer = Buyer::with('order')->where('id', $request->id)->first();

                if ($buyer) {
                    if($buyer->order->count() > 0) 
                    {
                        $return['success']        = false;
                        $return['message']        = "You're not able to delete the buyer because there are some phone selling record's available.";
                        return response()->json($return);
                    }
                    $buyer->delete();
                    $return['success'] = true;
                    $return['message'] = "Buyer deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Buyer not deleted.";
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
