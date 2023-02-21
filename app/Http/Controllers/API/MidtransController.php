<?php

namespace App\Http\Controllers\API;

//class midtrans
use Midtrans\Config;
use Midtrans\Notification;
//class biasa
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        //set konfigurasi middtrans
        Config::$serverKey = config('services.midtrans.serveyKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //Buat instance midtrans notification
        $notification = new Notification();

        //Assign ke variabel untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        //cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($order_id);
        
        //Handle notifikasi status midtrans
        if($status == 'capture'){
            if($status == 'credit_card'){
                if($fraud == 'challenge' ){
                    $transaction->status = 'PENDING';
                }else{
                    $transaction->status = 'SUCCESS';
                }
            }
        }
        else if($status == 'settlement'){
            $transaction->status = 'SUCCESS';
        }

        else if($status == 'pending'){
            $transaction->status = 'PENDING';
        }

        else if($status == 'deny'){
             $transaction->status = 'CANCELLED';
        }

        else if($status == 'expire'){
             $transaction->status = 'CANCELLED';
        }

        else if($status == 'cancel'){
             $transaction->status = 'CANCELLED';
        }

        //simpan transaksi
        $transaction->save();



        //simpan transaksi


    }

    public function success(){
        return view('midtrans.success');
    }

    public function unfinish(){
       return view('midtrans.unfinish');
    }

    public function error(){
        return view('midtrans.error');  
    }


}
