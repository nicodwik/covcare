<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function check(Request $request)
    {
        $data = Booking::whereHas('registrant', function ($q) use($request) {
            $q->where('phone', '+62' . $request->phone);
        })->with('registrant')->first();
        if($data) {
            $data['status'] == 'MENUNGGU KONFIRMASI' ? $data['color'] = 'alert-warning' : ($data['status'] == 'TERDAFTAR' ? $data['color'] = 'alert-success' : $data['color'] = 'alert-danger');
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
