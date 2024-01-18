<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Imei;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class ImeiController extends Controller
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
        // $service = "61"; // APi Service iD
        // $format = "json"; // $format = "html"; display result in JSON or HTML format
        // $imei = '353009110641259'; // IMEI or SERIAL Number
        // $api = "6TZ-W1P-RZ5-9CR-62L-95S-MAO-8EJ"; // APi Key

        // $curl = curl_init ("https://sickw.com/api.php?format=$format&key=$api&imei=$imei&service=$service");
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        // $result = curl_exec($curl);
        // curl_close($curl);
        // dd(json_decode($result));
        try {
            $data                       = [];
            $data['page_title']         = 'Imei List';
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.imei_details.index', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }


    public function datatable(Request $request) {
        $buyer = Imei::latest();

        return DataTables::eloquent($buyer)
            ->addColumn('action', function ($buyer) {
                $action      = '';
                if (Auth::user()->can('buyer-edit')) {
                    $action .= '<a href="'.route('admin.imei_details.edit', $buyer->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('buyer-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.imei_details.destroy').'" data-id="'.$buyer->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('created_at', function($buyer) {
                return date('d/m/Y h:i A', strtotime($buyer->created_at));
            })
            ->rawColumns(['action', 'status'])
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
                    'link'  => route('admin.imei_details.index'),
                    'title' => 'Buyer'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            return view('admin.imei_details.add', $data);
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
                'imei_number' => 'required',
            ];

            $messages = [
                'imei_number.required' => 'The imei number field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if (!$validator->fails()) {
                $sickwData = sickwImeiData($request->imei_number);
                $model_details = new Imei();

                $model_details->details = $sickwData->result;
                $model_details->imei = $sickwData->imei;

                $model_details->save();
                return response()->json(['success' => true, 'message' => 'Model Data added successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
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
                'link' => route('admin.imei_details.index'),
                'title' => 'Buyer List'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Buyer'
            );

            $buyer = Buyer::find($id);

            if ($buyer) {
                $data['buyer'] = $buyer;
                return view('admin.imei_details.add', $data);
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
                $buyer = Buyer::where('id', $request->id)->first();

                if ($buyer->delete()) {
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
