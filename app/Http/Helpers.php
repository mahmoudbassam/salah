<?php

use App\Helpers\APIResponse;
use App\Models\DoctorsDates;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

function api($success, $code, $message, $items = null, $errors = null)
{
    return new APIResponse($success, $code, $message);
}

function api_exception(Exception $e)
{
    return api(false, $e->getCode(), $e->getMessage())
        ->add('error', [
            'line' => $e->getLine(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'trace' => $e->getTrace(),
        ])->get();
}

function random_number($digits)
{
    return str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
}

function localURL($url)
{
    return url(app()->getLocale() . '/' . $url);
}

function locale()
{
    return app()->getLocale();
}

function direction()
{
    return locale() == 'ar' ? '.rtl' : '';
}

function isRTL()
{
    return locale() == 'ar';
}

function upload_file($file, $folder)
{
    $ex = $file->getClientOriginalExtension();

    return 'uploads/' . $file->storeAs($folder, time() . Str::random(30) . '.' . $ex);
}

function language($en, $ar)
{
    return app()->getLocale() == 'ar' ? $ar : $en;
}

function settings($key)
{
    return \App\Models\Setting::query()->first()->{$key};
}

function payment_info()
{
    return [
        'live_token' => 'Authorization:Bearer OGFjZGE0Yzk3ZDA5MjJiOTAxN2QyZGFlN2RjNzVmOWZ8d3RLRU1Rd1o4Zg==',
        'test_token' => 'Authorization:Bearer OGFjN2E0Yzk3YjYzMTA5NDAxN2I2YzY0YjhhMDEyYzR8OWRDQjZoQUN3NA==',
        'live_domain' => 'https://oppwa.com',
        'test_domain' => 'https://test.oppwa.com',
        'live_entity_id_visa' => '8acda4c97d0922b9017d2daf07695fab',
        'test_entity_id_visa' => '8ac7a4c97b631094017b6c6561fb12c9',
        'live_entity_id_mada' => '8acda4c97d0922b9017d2daf96245fb6',
        'test_entity_id_mada' => '8ac7a4c97b631094017b6c65ed9d12cd',
        'live_entity_id_apple' => '8ac7a4c97deb13bd017df6ed6e4e1606',
        'test_entity_id_apple' => '8ac7a4c97deb13bd017df6ed6e4e1606',
    ];
}

function payment($order)
{
    $mode = $order->payment_method->key == 'APPLEPAY' ? 'test' : 'live';
    $entity_id = $order->payment_method->key == 'MADA' ? payment_info()[$mode . '_entity_id_mada'] : ($order->payment_method->key == 'APPLEPAY' ? payment_info()[$mode . '_entity_id_apple'] : payment_info()[$mode . '_entity_id_visa']);
    $url = payment_info()[$mode . '_domain'] . "/v1/checkouts";
    $data =
        "entityId=" . $entity_id .
        "&amount=" . number_format($order->price, 2) .
        "&merchantTransactionId=" . uniqid() .
        "&currency=SAR" .
        "&customer.email=marham" . hash('md2', optional($order->user)->mobile ?: $order->user_id) . "@care.com" .
        "&billing.street1=jeddah" .
        "&billing.city=jeddah" .
        "&billing.state=jeddah" .
        "&billing.country=SA" .
        "&billing.postcode=21589" .
        "&customer.givenName=Marham" .
        "&customer.surname=Marham" .
        "&paymentType=DB";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(payment_info()[$mode . '_token']));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData, true);
}

function payment_response($id, $order)
{
    $mode = $order->payment_method->key == 'APPLEPAY' ? 'test' : 'live';
    $entity_id = $order->payment_method->key == 'MADA' ? payment_info()[$mode . '_entity_id_mada'] : ($order->payment_method->key == 'APPLEPAY' ? payment_info()[$mode . '_entity_id_apple'] : payment_info()[$mode . '_entity_id_visa']);
    $url = payment_info()[$mode . '_domain'] . "/v1/checkouts/" . urlencode($id) . "/payment?entityId=" . $entity_id;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(payment_info()[$mode . '_token']));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData, true);
}

function refund($order)
{
    if ($order->payment_id && $order->payment_method && $order->payment_method->key) {
        $mode = $order->payment_method->key == 'APPLEPAY' ? 'test' : 'live';
        $entity_id = $order->payment_method->key == 'MADA' ? payment_info()[$mode . '_entity_id_mada'] : ($order->payment_method->key == 'APPLEPAY' ? payment_info()[$mode . '_entity_id_apple'] : payment_info()[$mode . '_entity_id_visa']);
        $url = payment_info()[$mode . '_domain'] . "/v1/payments/" . urlencode($order->payment_id);
        $data = "entityId=" . $entity_id .
            "&amount=" . number_format($order->price, 2) .
            "&currency=SAR" .
            "&paymentType=RF";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(payment_info()[$mode . '_token']));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return json_decode($responseData, true);
    }
    return null;
}

function sms($number, $body)
{
    $url = "https://el.cloud.unifonic.com/rest/Messages/Send";
    $data = [
        'AppSid' => '3txVrGQyQW9zh4DIiuhSAeRRYnClMJ',
        'Recipient' => intval($number),
        'SenderID' => 'MARHAM',
        'Body' => $body,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData, true);
}


function copy_date_by_day($dates, $day_to)
{
    $date_groups = [];
    foreach ($dates as $date) {
        if ($date->doctors_dates_group) {
            $date_groups[] = $date->doctors_dates_group;
        }
    }
    $date_groups = collect($date_groups)->unique();
    foreach ($date_groups as $date_group) {
        $new_date_group = $date_group->replicate();
        $new_date_group->save();
        foreach ($date_group->doctors_dates as $date) {
            $is_date_has_order = DoctorsDates::query()->whereHas('orders')->where('center_id', $date->center_id)->where('doctor_id', $date->doctor_id)
                ->OpenConflict(Carbon::parse($day_to)->toDateString() . ' ' . Carbon::parse($date->date_from)->format('H:i:s'), Carbon::parse($day_to)->toDateString() . ' ' . Carbon::parse($date->date_to)->format('H:i:s'))->count();
            if ($is_date_has_order) return false;
            $is_date_exists = DoctorsDates::query()->where('center_id', $date->center_id)->where('doctor_id', $date->doctor_id)
                ->OpenConflict(Carbon::parse($day_to)->toDateString() . ' ' . Carbon::parse($date->date_from)->format('H:i:s'), Carbon::parse($day_to)->toDateString() . ' ' . Carbon::parse($date->date_to)->format('H:i:s'));
            if ($is_date_exists->count()) $is_date_exists->delete();
            DoctorsDates::query()->create([
                'date_group_id' => $new_date_group->getKey(),
                'doctor_id' => $date->doctor_id,
                'center_id' => $date->center_id,
                'date_from' => Carbon::parse($day_to)->toDateString() . ' ' . Carbon::parse($date->date_from)->format('H:i:s'),
                'date_to' => Carbon::parse($day_to)->toDateString() . ' ' . Carbon::parse($date->date_to)->format('H:i:s'),
                'type'
            ]);
        }
    }
    return true;
}

function generate_week_from_day(Carbon $day)
{
    $dates = \Carbon\CarbonPeriod::since($day)->days()->until($day->addDays(6));
    $dates_arr = [];
    foreach ($dates as $date) {
        $dates_arr[] = $date->toDateString();
    }
    return $dates_arr;
}

function payment_offer($order)
{

    $mode = $order->payment_method->key == 'APPLEPAY' ? 'test' : 'live';
    $entity_id = $order->payment_method->key == 'MADA' ? payment_info()[$mode . '_entity_id_mada'] : ($order->payment_method->key == 'APPLEPAY' ? payment_info()[$mode . '_entity_id_apple'] : payment_info()[$mode . '_entity_id_visa']);
    $url = payment_info()[$mode . '_domain'] . "/v1/checkouts";
    $data =
        "entityId=" . $entity_id .
        "&amount=" . number_format($order->from_card, 2) .
        "&merchantTransactionId=" . uniqid() .
        "&currency=SAR" .
        "&customer.email=marham" . hash('md2', optional($order->user)->mobile ?: $order->user_id) . "@care.com" .
        "&billing.street1=jeddah" .
        "&billing.city=jeddah" .
        "&billing.state=jeddah" .
        "&billing.country=SA" .
        "&billing.postcode=21589" .
        "&customer.givenName=Marham" .
        "&customer.surname=Marham" .
        "&paymentType=DB";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(payment_info()[$mode . '_token']));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData, true);
}


function payment_cart($cart)
{

    $mode = $cart->payment_method->key == 'APPLEPAY' ? 'test' : 'live';
    $entity_id = $cart->payment_method->key == 'MADA' ? payment_info()[$mode . '_entity_id_mada'] : ($cart->payment_method->key == 'APPLEPAY' ? payment_info()[$mode . '_entity_id_apple'] : payment_info()[$mode . '_entity_id_visa']);
    $url = payment_info()[$mode . '_domain'] . "/v1/checkouts";
    $data =
        "entityId=" . $entity_id .
        "&amount=" . number_format($cart->validCartOrders()->sum('from_balance'), 2) .
        "&merchantTransactionId=" . uniqid() .
        "&currency=SAR" .
        "&customer.email=marham" . hash('md2', optional($cart->user)->mobile ?: $cart->user_id) . "@care.com" .
        "&billing.street1=jeddah" .
        "&billing.city=jeddah" .
        "&billing.state=jeddah" .
        "&billing.country=SA" .
        "&billing.postcode=21589" .
        "&customer.givenName=Marham" .
        "&customer.surname=Marham" .
        "&paymentType=DB";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(payment_info()[$mode . '_token']));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData, true);
}

function cart_response($id, $order)
{
    $mode = $order->payment_method->key == 'APPLEPAY' ? 'test' : 'live';
    $entity_id = $order->payment_method->key == 'MADA' ? payment_info()[$mode . '_entity_id_mada'] : ($order->payment_method->key == 'APPLEPAY' ? payment_info()[$mode . '_entity_id_apple'] : payment_info()[$mode . '_entity_id_visa']);
    $url = payment_info()[$mode . '_domain'] . "/v1/checkouts/" . urlencode($id) . "/payment?entityId=" . $entity_id;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(payment_info()[$mode . '_token']));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return json_decode($responseData, true);
}
