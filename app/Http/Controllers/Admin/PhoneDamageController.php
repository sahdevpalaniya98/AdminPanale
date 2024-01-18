<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhoneDamage;
use DataTables;
use Validator;
use Session;
use Image;
use Auth;
use Hash;
use File;

class PhoneDamageController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:phone-damage-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:phone-damage-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:phone-damage-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:phone-damage-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $data                       = [];
            $data['page_title']         = 'Phone Damage List';
            if  (Auth::user()->can('phone-damage-add')) {
                $data['btnadd'][]       = array(
                    'link'  => route('admin.phone.damage.add'),
                    'title' => 'Add Phone Damage'
                );
            }
            $data['breadcrumb'][]       = array(
                'link'  => route('admin.home'),
                'title' => 'Dashboard'
            );
            $data['breadcrumb'][]       = array(
                'title' => 'List'
            );
            return view('admin.phone-damage.index', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function datatable(Request $request)
    {
        $phone_damage = PhoneDamage::query();
        return DataTables::eloquent($phone_damage)
            ->addColumn('action', function ($phone_damage) {
                $action      = '';
                if (Auth::user()->can('phone-damage-edit')) {
                    $action .= '<a href="'.route('admin.phone.damage.edit', $phone_damage->id).'" class="btn btn-outline-secondary btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
                }
                if (Auth::user()->can('phone-damage-delete')) {
                    $action .= '<a class="btn btn-outline-danger btn-sm btnDelete" data-url="'.route('admin.phone.damage.destroy').'" data-id="'.$phone_damage->id.'" title="Delete"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                }
                return $action;
            })
            ->editColumn('note', function ($phone_damage) {
                return ($phone_damage->note) ? $phone_damage->note : '-';
            })
            ->editColumn('created_at', function ($phone_damage) {
                return date('d/m/Y h:i A', strtotime($phone_damage->created_at));
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
            $data['page_title']         = 'Add Phone Damage';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('phone-damage-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.phone.damage.index'),
                    'title' => 'Phone Damage'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Add'
            );
            return view('admin.phone-damage.add', $data);
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class', 'error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $phone_damageId   = ($request->phone_damage_id) ? $request->phone_damage_id : '';
            $rules = [
                'damage_name'=> 'required',
                'note'=> 'required',

            ];
        
            $messages = [
                'damage_name.required' => 'The phone damage name field is required.',
                'note.required' => 'The phone damage note field is required.',

            ];
            $validator      = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                if ($phone_damageId != '') {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                
                if($phone_damageId != "")
                {
                    $phone_damage   = PhoneDamage::where('id', $request->phone_damage_id)->whereNull('deleted_at')->first();
                    $action     = "Update";
                }else{
                    $phone_damage   = new PhoneDamage();
                    $action     = "Added";
                }
                $phone_damage->damage_name           = $request->damage_name ; 
                $phone_damage->note       = (isset($request->note) && $request->note != "") ? $request->note : null; 
                
               if($phone_damage->save())
               {
                Session::flash('alert-message', 'Phone Damage '.$action.' successfully.');
                Session::flash('alert-class', 'success');
                return redirect()->route('admin.phone.damage.index');
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
            $data['page_title']         = 'Edit Phone Damage';
            $data['breadcrumb'][]       = array(
                'link'      => route('admin.home'),
                'title'     => 'Dashboard'
            );
            if (Auth::user()->can('phone-damage-list')) {
                $data['breadcrumb'][]   = array(
                    'link'  => route('admin.phone.damage.index'),
                    'title' => 'Phone Damage'
                );
            }
            $data['breadcrumb'][]       = array(
                'title' => 'Edit'
            );
            $phone_damage                       = PhoneDamage::where('id', $id)->first();
            if ($phone_damage) {
                $data['phone_damage']           = $phone_damage;
                return view('admin.phone-damage.edit', $data);
            } else {
                return abort(404);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function destroy(Request $request)
    {
        try {
            if ($request->ajax()) {
                $phone_damage = PhoneDamage::where('id', $request->id)->first();

                if ($phone_damage->delete()) {
                    $return['success'] = true;
                    $return['message'] = "Phone Damage deleted successfully.";
                } else {
                    $return['success'] = false;
                    $return['message'] = "Phone Damage not deleted.";
                }

                return response()->json($return);
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }
}
