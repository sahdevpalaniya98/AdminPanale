<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\PhoneDamage;
use App\Models\PaymentMode;
use App\Models\User;
use App\Models\Buyer;
use App\Models\PayWorker;
use App\Models\Inventory;
use App\Models\PhoneGrade;
use App\Models\Brand;
use App\Models\PhoneSeries;
use App\Models\PhoneModel;
use App\Models\Expense;
use App\Models\Category;

class AjaxController extends Controller
{

    public function customer_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = Customer::query();
        if ($search != '') {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->name);
        }
        return response()->json($data);
    }

    public function payment_mode_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PaymentMode::query();
        if ($search != '') {
            $query->where('payment_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->payment_name);
        }
        return response()->json($data);
    }

    public function employee_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = User::query()->whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'employee');
            }
        );
        if ($search != '') {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->name);
        }
        return response()->json($data);
    }

    public function phone_damage_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PhoneDamage::query();
        if ($search != '') {
            $query->where('damage_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->damage_name);
        }
        return response()->json($data);
    }

    public function get_sickw(Request $request)
    {
        try {
            $sickwData = sickwImeiData($request->imei_number, $request->brand_value);
            return response()->json(['success' => true, 'message' => 'Details found.', 'data' => $sickwData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function buyer_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = Buyer::query();
        if ($search != '') {
            $query->where('company_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->company_name);
        }
        return response()->json($data);
    }

    public function pay_worker_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PayWorker::query();
        if ($search != '') {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->name);
        }
        return response()->json($data);
    }

    public function phone_grade_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PhoneGrade::query();
        if ($search != '') {
            $query->where('grade_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->grade_name);
        }
        return response()->json($data);
    }

    public function inventory_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $is_sold = ($request->has('is_sold') && $request->is_sold != '') ? $request->is_sold : '';
        $selecte_inventory_ids = ($request->has('join_inventory_ids') && $request->join_inventory_ids != '') ? $request->join_inventory_ids : '';
        $query = Inventory::query();
        if (! in_array(1, auth()->user()->roles()->pluck('role_id')->toArray())) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        if ($search != '') {
            $query->whereHas('phone_model', function ($q) use ($search) {
                $q->where('model_name', 'LIKE', '%' . $search . '%');
            })->orWhere('imei_number', 'LIKE', '%' . $search . '%')->orWhere('serial_number', 'LIKE', '%' . $search . '%');
        }
        if ($is_sold == '') {
            $query->where('is_sold', false);
        }
        if ($selecte_inventory_ids != "") {
            $ids = explode(',', $selecte_inventory_ids);
            $query->whereNotIn('id', $ids);
        }
        $records = $query->with(['phone_brand', 'phone_serice', 'expenses', 'payment_modes', 'phone_damages', 'inventory_variant'])->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $serial_number = (isset($row->serial_number) && $row->serial_number != "") ? "(SR No-" . $row->serial_number . ")" : null;
            $imei = (isset($row->imei_number) && $row->imei_number != "") ? "(IMEI-" . $row->imei_number . ")" : $serial_number;
            $data[] = array('id' => $row->id, 'text' => $row->phone_model->model_name ." " . $imei, 'all_data' => $row);
        }
        return response()->json($data);
    }

    public function all_damages_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PhoneDamage::query();
        if ($search != '') {
            $query->where('damage_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->damage_name);
        }
        return response()->json($data);
    }

    public function damage_list(Request $request)
    {
        $inventory = Inventory::where('id', $request->inventory_id)->first();
        $html = '';
        $input_mast = "'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'";
        $html .= "<div class='card mt-5' id='device_damage_" . $request->inventory_id . "'>
					<div class='card-header text-center'><b>" . $inventory->phone_model->model_name . "</b></div>
					<input type='hidden' value='" . $inventory->purchase_price . "' name='purchase_price' class='inventory-items-amt' />
					<div class='card-body'>
						<div class='row'>";
        foreach ($inventory->phone_damages as $damage) {
            $html .= "<div class='col-6'>
						<div class='mb-3'>
							<input type='hidden' name='inventory[" . $damage->pivot->inventory_id . "][phone_damage_id][]' value='" . $damage->id . "'>
							<label class='form-label' for='expense_amount'> <b>" . $damage->damage_name . "</b> Expense Amount</label>
							<input style='text-align: left;' type='text' class='form-control text-start input-mask inventory-items-expense-amt' name='inventory[" . $damage->pivot->inventory_id . "][expense_amount][]'
							value='" . $damage->pivot->expense_amount . "'
							placeholder='Enter expense amount'>
						</div>
					</div>";
        }
        $html .= "</div>
					</div>
				</div>";

        return response($html);
    }

    public function brand_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = Brand::query();
        if ($search != '') {
            $query->where('brand_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->brand_name);
        }
        return response()->json($data);
    }

    public function series_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PhoneSeries::query();
        if ($search != '') {
            $query->where('series_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->where('category_id', $request->category_id)->where('category_id', '!=', null)->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->series_name);
        }
        return response()->json($data);
    }

    public function category_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = Category::query();
        if ($search != '') {
            $query->where('category_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->where('brand_id', $request->brand_id)->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->category_name);
        }
        return response()->json($data);
    }

    public function model_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PhoneModel::query();
        if ($search != '') {
            $query->where('model_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->where('brand_id', $request->brand_id)->where('series_id', $request->series_id)->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->model_name);
        }
        return response()->json($data);
    }

    public function expense_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = Expense::query();
        if ($search != '') {
            $query->where('expense_name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->expense_name);
        }
        return response()->json($data);
    }

    public function variant_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = Variant::query();
        if ($search != '') {
            $query->where('title', 'LIKE', '%' . $search . '%')->orWhere('type', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->type . '-' . $row->title);
        }
        return response()->json($data);
    }

    public function model_variant_list(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = PhoneModel::with('phone_variant');
        if ($search != '') {
            $query->whereHas('phone_variant', function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')->orWhere('type', 'LIKE', '%' . $search . '%');
            });
        }
        $model_id = $request->model_id;
        $records = $query->whereHas('phone_variant', function ($q) use ($model_id) {
            $q->where('phone_model_id', $model_id);
        })->get();
        $data = [];
        foreach ($records as $key => $row) {
            foreach ($row->phone_variant as $key => $variant) {
                $data[] = array('id' => $variant->id, 'text' => $variant->type . '-' . $variant->title);
            }
        }
        return response()->json($data);
    }

    public function branch(Request $request)
    {
        $search = ($request->has('search') && $request->search != '') ? $request->search : '';
        $query = Branch::query();
        if ($search != '') {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }
        $records = $query->limit(75)->get();
        $data = [];
        foreach ($records as $key => $row) {
            $data[] = array('id' => $row->id, 'text' => $row->name);
        }
        return response()->json($data);
    }
}
