<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrant;
use App\Models\Booking;
use App\Models\Vaccine;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credential = $request->only(['email', 'password']);
        $validated = \Validator::make($credential, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validated->fails()) {
            return redirect()->back()->with('error', $validated->errors()->first());
        } else {
            if(\Auth::attempt($credential)) {
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            } else {
                return redirect()->back()->with('error', 'Email / Password salah');
            }
        }
    }

    public function register(Request $request) 
    {
        $validated = \Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:registrants,phone',
            'nik' => 'required',
            'address' => 'required',
            'time' => 'required',
        ]);
        if($validated->fails()) {
            return response()->json([
                'status' => 'validation-error',
                'message' => 'Harap lengkapi data!'
            ]);
        } else {
            try {
                $registrant = $request->except('time');
                $checkVaccine = Vaccine::first();
                if($checkVaccine->stock > 0) {
                    $checkVaccine->update([
                        'stock' => $checkVaccine->stock - 1
                    ]);
                    $registrant['qr_code'] = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . route('check') . '?phone='. $request->phone;
                    $dataRegistrant = Registrant::create($registrant);
                    Booking::create([
                        'registrant_id' => $dataRegistrant->id,
                        'vaccine_id' => $checkVaccine->id,
                        'time' => $request->time,
                        'status' => 'MENUNGGU KONFIRMASI'
                    ]);
                    return response()->json([
                        'status' => 'success'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stok Vaksin Habis'
                    ]);
                }
            } catch (\Exception $th) {
                return response()->json([
                    'message' => $th->getMessage()
                ]);
            }
            
        }
    }
}
