<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:customer-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Customer List';
            if (Auth::user()->can('customer-add')) {
                $data['btnadd'][]       = array(
                    'link'  => route('admin.customer.add'),
                    'title' => 'Add Customer'
                );
            }
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.customer.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request)
    {
        $customer = Customer::query();
        return DataTables::eloquent($customer)
            ->addColumn('action', function ($customer) {
                $action      = '';
                if (Auth::user()->can('customer-edit')) {
                    $action .= '<a href="'.route('admin.customer.edit', $customer->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('customer-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.customer.destroy').'" data-id="'.$customer->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('address', function ($customer) {
                return ($customer->address) ? $customer->address : '-';
            })
            ->editColumn('created_at', function ($customer) {
                return date('d/m/Y h:i A', strtotime($customer->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(customers.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action','address'])->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Add Customer';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('customer-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.customer.index'),
                    'title' => 'Customer'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            return view('admin.customer.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $customerId   = ($request->customer_id) ? $request->customer_id : '';
            $rules = [
                'name'=> 'required',
            ];
        
            $messages = [
                'name.required' => 'The name field is required.',
            ];
            $validator      = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                if ($customerId != '') {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                $old_img    = [];
                $imagenames = [];
                if($customerId != "")
                {
                    $customer   = Customer::where('id', $request->customer_id)->whereNull('deleted_at')->first();
                    $action     = "Update";
                    $old_img    = (isset($customer->document_images) && $customer->document_images != NULL ) ? explode(",",$customer->document_images) : NULL;
                }else{
                    $customer   = new Customer();
                    $action     = "Added";
                }
                $customer->name          = (isset($request->name) && $request->name != "" && $request->name != 'undefined') ? $request->name : null; 
                $customer->address       = (isset($request->address) && $request->address != "" && $request->address != 'undefined') ? $request->address : null; 
                $customer->mobile_number = (isset($request->mobile_number) && $request->mobile_number != "" && $request->mobile_number != "undefined") ? $request->mobile_number : null; 
                if(isset($request->document) && $request->document != "" && is_array($request->document))
                {
                    $customer->document = implode(',',$request->document);
                }else{
                    $customer->document = (isset($request->document) && $request->document != "") ? $request->document : null;
                }
                $customer->remark        = (isset($request->remark) && $request->remark != "" && $request->remark != "undefined") ? $request->remark : null;
                if($request->file != "")
                {   
                    foreach($request['file'] as $images){
                        $file_name = time().uniqid().".".$images->extension();
                        $path      = 'uploads/documentImages';
                        $images->move($path, $file_name);
                        array_push($imagenames,$file_name);
                    }
                }
                $all_images = [];
                if(!empty($old_img)){
                    foreach ($old_img as $key => $value) {
                        array_push($all_images,$value);
                    }
                }

                if(!empty($imagenames)){
                    foreach ($imagenames as $key => $value) {
                        array_push($all_images,$value);
                    }
                }

                if(isset($request->removed_images) && $request->removed_images != "")
                {   
                    $removeImg =explode(',',$request->removed_images);
                    $new_images =array_diff($all_images,$removeImg);
                    foreach ($removeImg as $key => $value) {
                        $removedpath = public_path('uploads/documentImages/' . $value);
                        if (File::exists($removedpath)) {
                            unlink($removedpath);
                        }
                    }
                }else{
                    $new_images = $all_images;
                }
                $customer->document_images = (isset($new_images) && $new_images != null) ? implode(',',$new_images) : null;
               if($customer->save())
               {
                if ($request->ajax()) {
                    // return response()->json(['success'=>true, 'message'=>'Customer added successfully.', 'data' => $customer]);
                    Session::flash('alert-message', 'Customer '.$action.' successfully.');
                    Session::flash('alert-class', 'success');
                }
                Session::flash('alert-message', 'Customer '.$action.' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.customer.index');
               }
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success'=>false, 'message'=>$e->getMessage()]);
            } else {
                Session::flash('alert-message', $e->getMessage());
                Session::flash('alert-class', 'error');
                return redirect()->back();
            }
        }
    }

    public function edit($id)
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Edit Customer';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('customer-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.customer.index'),
                    'title' => 'Customer'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Edit'
            );
            $customer                       = Customer::where('id', $id)->first();
            if ($customer) {
                $data['customer']           = $customer;
                return view('admin.customer.edit', $data);
            } else {
                return abort(404);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            try {
                $customer = Customer::where('id', $request->id)->first();
                if (!is_null($customer)) {
                    $removeImg = (isset($customer->document_images) && $customer->document_images != "") ? explode(",",$customer->document_images) : "";
                    if(!empty($removeImg))
                    {
                        foreach ($removeImg as $key => $value) {
                            $removedpath = public_path('uploads/documentImages/' . $value);
                            if (File::exists($removedpath)) {
                                unlink($removedpath);
                            }
                        }
                    }
                    if ($customer) {
                        if($customer->inventory->count() > 0) 
                        {
                            $return['success']        = false;
                            $return['message']        = "You're not able to delete the customer because there are some inventory record's available.";
                            return response()->json($return);
                        }
                        $customer->delete();
                        $response['success']    = true;
                        $response['message']    = "Customer deleted successfully.";
                    } else {
                        $response['success']    = false;
                        $response['message']    = "Customer deleted unsuccessfully.";
                    }
                }
            } catch (\Exception $e) {
                $response['success']            = false;
                $response['message']            = $e->getMessage();
            }
            return response()->json($response);
        } else {
            return abort(404);
        }
    }
}
