<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\PayWorker;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title']         = 'Dashboard';
            $data['total_customer']     =  Customer::count();
            $data['orders']             =  Order::where('order_status', 'like', 'complete')->get();
            $data['in_progress']        =  Order::where('order_status', 'like', 'in-progress')->count();
            $data['cancel_order']       =  Order::where('order_status', 'like', 'cancel')->orWhere('order_status','like','return')->count();
            $data['total_order']        =  Order::count();
            $data['total_buyers']       =  Buyer::count();
            $data['total_payworker']    =  PayWorker::count();
            return view('home', $data);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return abort(404);
        }
    }
}