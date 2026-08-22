<?php

namespace App\Http\Controllers;

use App\Models\Frontend\User;
use App\Models\PaymentRequest;
use App\Models\Province;
use App\Traits\LocalizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PaymentController extends Controller
{
    use LocalizeController;

    public $data = [];

    public function checkout(Request $request)
    {

        $this->localized();

        $province = Province::find(auth()->user()->province);

        $amount = $request->amount ? str_replace(',', '', $request->amount) * 100 : 0;

        $purchase = [

            'vnp_Command' => 'pay',

            'vnp_CreateDate' => date('YmdHis'),

            'vnp_CurrCode' => 'VND',

            'vnp_Bill_Email' => 'bichnhibe@gmail.com',

            'vnp_TxnRef' => time(),

            'vnp_IpAddr' => '127.0.0.1',

            'vnp_OrderInfo' => $request->content ?? 'Nap Tien',

            'vnp_Locale' => 'vn',

            'vnp_OrderType' => 'other',

            'vnp_Amount' => $amount,

            'vnp_ReturnUrl' => route('payment.retun'),

        ];

        if ($request->bank_code != '') {

            $purchase['vnp_BankCode'] = $request->bank_code;
        }

        try {

            // dd($purchase);

            $response = \VNPay::purchase($purchase)->send();

            if ($response->isRedirect()) {

                PaymentRequest::create([

                    'user_id' => auth()->user()->id,

                    'amount' => $amount,

                    'content' => $request->content ?? '',

                    'status' => 1, // ma yeu cau nap tien

                    'payment_status' => 'Yêu cầu nạp tiền',

                ])->save();

                $response->redirect(); // this will automatically forward the customer

            } else {

                // not successful

                return $response->getMessage();

            }

        } catch (Exception $e) {

            return $e->getMessage();

        }

        // return view('theme.home', ['data' => $this->data]);

    }

    public function payment_return()
    {

        $this->localized();

        $response = \VNPay::completePurchase()->send();

        // dd($response);

        $this->data['payment'] = $response;

        $payment_status = $response->vnp_TransactionStatus == '00' ? 'GD Thành công' : 'GD không thành công';

        $amount = $response->vnp_Amount ? $response->vnp_Amount / 100 : 0;

        PaymentRequest::updateOrCreate(

            [

                'user_id' => auth()->user()->id,

                'payment_code' => $response->vnp_TxnRef,

            ],

            [

                'amount' => $amount,

                'content' => $response->vnp_OrderInfo ?? '',

                'status' => $response->vnp_TransactionStatus, // 00 giao dich thanh cong

                'payment_status' => $payment_status,

                'payment_method' => $response->vnp_CardType,

                'bank_code' => $response->vnp_BankCode,

            ]

        );

        if ($response->isSuccessful()) {

            $wallet = auth()->user()->wallet;

            $wallet = $wallet + $amount;

            User::find(auth()->user()->id)->update(['wallet' => $wallet]);
            // TODO: xử lý kết quả và hiển thị.

            return view('theme.payment.success', ['data' => $this->data]);

            // dd($response->getData()); // toàn bộ data do VNPay gửi sang.

            // return redirect(route('payment.success'))->with('payment', $response);

        } else {

            return view('theme.payment.reject', ['data' => $this->data]);

        }

    }

    public function paymentSuccess()
    {

        $this->localized();

        $this->data['payment'] = PaymentRequest::where('status', '<>', 1)->where('user_id', auth()->user()->id)->orderbyDesc('id')->first();

        return view('theme.payment.success', ['data' => $this->data]);

    }

    // nap tien

    public function paymentPoint()
    {

        $this->localized();

        return view('theme.payment.payment-point', ['data' => $this->data]);

    }

    public function paymentType(Request $request)
    {

        $type = $request->type;

        $this->data['payment_type'] = $type;

        if ($type == 'qrcode') {

            $view = view('theme.payment.includes.qrcode')->render();

        } elseif ($type == 'atm') {

            $view = view('theme.payment.includes.banks')->render();

        } elseif ($type == 'visa') {

            $view = view('theme.payment.includes.visa')->render();

        }

        $this->data['view'] = $view;

        return response()->json($this->data);

    }
}
