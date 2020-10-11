<?php

namespace App\Http\Controllers\User;

use App\Models\Address;
use App\Models\Provinces;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class AdressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $address = Auth::user()->addresses;

        return view('user.address.index', compact('address'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = DB::table('provinces')->orderBy('name', 'asc')->get();
        
        return view('user.address.create', compact('provinces'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        $address = new Address();
        
        try {
            if (in_array(null, $request->all())) {
                return response()->json(['status' => 'error', 'msg' => 'Alamat Tidak Lengkap']);
            }

            $existing = Address::where('user_id', Auth::user()->id)->where('receiver', $request->receiver_name)->where('city_id', $request->city_id)->first();
            if ($existing <> '') {
                return response()->json(['status' => 'error', 'msg' => 'Alamat Sudah Ada Sebelumnya']);
            }

            $address->user_id = Auth::user()->id;
            $address->receiver = $request->receiver_name;
            $address->address_name = $request->shipping_name;
            $address->phone = $request->shipping_phone;
            $address->province_id = $request->shipping_provinces;
            $address->city_id = $request->shipping_city;
            $address->district_id = $request->shipping_districts;
            $address->zip = $request->shipping_zip;
            $address->address = $request->shipping_address;

            $address->save();    

            if ( $request->ajax() ) {
                return response()->json(['status' => 'success', 'msg' => 'Berhasil Menambahkan Alamat']);
            }

            return redirect()->route('user-address-index')->with('success', 'Berhasil Menambahkan Alamat');
            
        } catch (\Throwable $th) {
            if ( $request->ajax() ) {
                return response()->json(['status' => 'error', 'msg' => 'Gagal Menambahkan Alamat']);
            }
            
            return redirect()->back()->withInput()->with('unsuccess', 'Gagal Menambahkan Alamat');
        }
        


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alamat = Address::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $provinces = DB::table('provinces')->orderBy('name', 'asc')->get();
        $regencies = DB::table('regencies')->where('province_id', @$alamat->province_id)->orderBy('name', 'asc')->get();
        $districts = DB::table('districts')->where('regency_id', @$alamat->city_id)->orderBy('name', 'asc')->get();
        $zip = $alamat->zip;

        return view('user.address.edit', compact('alamat', 'provinces', 'regencies', 'districts', 'zip'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $alamat = Address::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();

        $alamat->address_name = $request->name;
        $alamat->receiver = $request->receiver;
        $alamat->phone = $request->phone;
        $alamat->province_id = $request->provinces;
        $alamat->city_id = $request->city;
        $alamat->district_id = $request->districts;
        $alamat->zip = $request->zip;
        $alamat->address = $request->address;

        $alamat->save();

        return redirect('/user/address')->with('success', 'Alamat Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id <> '') {
            $address = Address::find($id);

            Address::destroy($id);
            return redirect('/user/address')->with('success', 'Berhasil Menghapus Alamat');
        }
    }
}
