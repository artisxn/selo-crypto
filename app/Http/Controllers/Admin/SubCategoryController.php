<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
         $datas = Subcategory::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('category', function(Subcategory $data) {
                                return $data->category->name;
                            })
                            ->addColumn('status', function(Subcategory $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-subcat-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><<option data-val="0" value="'. route('admin-subcat-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
                            })
                            ->addColumn('attributes', function(Subcategory $data) {
                                $buttons = '<div class="action-list"><a data-href="' . route('admin-attr-createForSubcategory', $data->id) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>';
                                if ($data->attributes()->count() > 0) {
                                  $buttons .= '<a href="' . route('admin-attr-manage', $data->id) .'?type=subcategory' . '" class="edit"> <i class="fas fa-edit"></i>Manage</a>';
                                }
                                $buttons .= '</div>';

                                return $buttons;
                            })
                            ->addColumn('action', function(Subcategory $data) {
                                return '<div class="action-list"><a data-href="' . route('admin-subcat-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-subcat-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->rawColumns(['status','attributes','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.subcategory.index');
    }

    //*** GET Request
    public function create()
    {
      	$cats = Category::all();
        return view('admin.subcategory.create',compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'banner' => 'mimes:jpeg,jpg,png,svg',
            'photo.mimes' => 'Icon Type is Invalid.',
            'banner.mimes' => 'Banner Type is Invalid.',
            'slug' => 'unique:subcategories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make(Input::all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        
        //--- Logic Section
        $input = $request->all();
        if ($file = $request->file('photo'))
        {
           $name = time().$file->getClientOriginalName();
           $file->move('assets/images/categories',$name);
           $input['photo'] = $name;
        }

        if ($file = $request->file('banner'))
        {
           $name = time().$file->getClientOriginalName();
           $file->move('assets/images/categories',$name);
           $input['banner'] = $name;
        }
       

        $data = new Subcategory();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
    	$cats = Category::all();
        $data = Subcategory::findOrFail($id);
        return view('admin.subcategory.edit',compact('data','cats'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'banner' => 'mimes:jpeg,jpg,png,svg',
            'slug' => 'unique:subcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'photo.mimes' => 'Icon Type is Invalid.',
            'banner.mimes' => 'Banner Type is Invalid.',
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make(Input::all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Subcategory::findOrFail($id);
        $input = $request->all();

        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/categories',$name);
            if($data->photo != null)
            {
                if (file_exists(public_path().'/assets/images/categories/'.$data->photo)) {
                    unlink(public_path().'/assets/images/categories/'.$data->photo);
                }
            }
            $input['photo'] = $name;
        }

        if ($file = $request->file('banner'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/categories',$name);
            if($data->banner != null)
            {
                if (file_exists(public_path().'/assets/images/categories/'.$data->banner)) {
                    unlink(public_path().'/assets/images/categories/'.$data->banner);
                }
            }
            $input['banner'] = $name;
        }


        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

      //*** GET Request Status
      public function status($id1,$id2)
        {
            $data = Subcategory::findOrFail($id1);
            $data->status = $id2;
            $data->update();
        }

    //*** GET Request
    public function load($id)
    {
        $cat = Category::findOrFail($id);
        return view('load.subcategory',compact('cat'));
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Subcategory::findOrFail($id);


        if($data->attributes->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the Attributes first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }
        if($data->childs->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the Child Categories first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }
        if($data->products->count()>0)
        {
        //--- Redirect Section
        $msg = 'Remove the products first !';
        return response()->json($msg);
        //--- Redirect Section Ends
        }

        if ($data->photo <> '') {
            if (file_exists(public_path().'/assets/images/categories/'.$data->photo)) {
                unlink(public_path().'/assets/images/categories/'.$data->photo);
            }
        }
        

        if ($data->image <> '') {
            if (file_exists(public_path().'/assets/images/categories/'.$data->image)) {
                unlink(public_path().'/assets/images/categories/'.$data->image);
            }
        }

        if ($data->banner <> '') {
            if (file_exists(public_path().'/assets/images/categories/'.$data->banner)) {
                unlink(public_path().'/assets/images/categories/'.$data->banner);
            }
        }


        $data->delete();
        //--- Redirect Section
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
