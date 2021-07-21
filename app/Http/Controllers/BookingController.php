<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function check(Request $request)
    {
        try {
            $data = Booking::whereHas('registrant', function ($q) use($request) {
                $q->where('phone', '+62' . $request->phone);
            })->with('registrant', 'schedule')->first();
            if($data) {
                $data->schedule->date = Carbon::parse($data->schedule->date)->isoFormat('dddd, Do MMMM Y'); 
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
        } catch (\Exception $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function quotaCheck(Request $request)
    {
        try {
            $check = Schedule::findOrFail($request->schedule_id);
            if($check->stock > 0) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error']);
            }
        } catch (\Exception $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
        
    }

    public function confirmAtendee(Request $request)
    {
        try {
            $data = Booking::findOrFail($request->booking_id);
            if($data->isConfirmed == 0) {
                $data->update([
                    'isConfirmed' => 1
                ]);
                return response()->json(['status' => 'success', 'message' => 'Telah Dikonfirmasi']);
            }
        } catch (\Exception $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
        
    }
}
