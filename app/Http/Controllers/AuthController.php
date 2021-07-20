<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrant;
use App\Models\Booking;
use App\Models\Vaccine;
use App\Models\Schedule;
use \Carbon\Carbon;

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
            'schedule' => 'required',
        ]);
        if($validated->fails()) {
            return response()->json([
                'status' => 'validation-error',
                'message' => $validated->errors()->first()
            ]);
        } else {
            try {
                $registrant = $request->except('schedule');
                $checkVaccine = Schedule::findOrFail($request->schedule);
                if($checkVaccine->stock > 0) {
                    $checkVaccine->update([
                        'stock' => $checkVaccine->stock - 1
                    ]);
                    $registrant['qr_code'] = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . route('check') . '?phone='. ltrim($request->phone, '+62');
                    $dataRegistrant = Registrant::create($registrant);
                    Booking::create([
                        'registrant_id' => $dataRegistrant->id,
                        'schedule_id' => $checkVaccine->id,
                        'time' => Carbon::parse($checkVaccine->date)->isoFormat('dddd, Do MMMM Y') . ' (' . $checkVaccine->time . ')',
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
