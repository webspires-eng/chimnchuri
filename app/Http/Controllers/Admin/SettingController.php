<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Setting;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {

        $settings = Setting::first();

        return view("admin.settings.general_settings", compact("settings"));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "restaurant_name" => "required",
            "restaurant_logo" => "nullable",
            "phone" => "required|numeric",
            "email" => "nullable|string|email",
            "address" => "nullable|string",
            "city" => "nullable|string",
            "postcode" => "nullable",
            "state" => "nullable|string",
            "country" => "nullable|string",
            "currency_code" => "nullable|string",
            "currency_symbol" => "nullable|string",
            "tax_percentage" => "nullable|numeric",
            "delivery_charge" => "nullable|numeric",
            "min_order_amount" => "nullable|numeric",
            "is_delivery_enabled" => "nullable|boolean",
            "is_pickup_enabled" => "nullable|boolean",
            "is_cod_enabled" => "nullable|boolean",


            // "fb_link" => "nullable|url",
            // "insta_link" => "nullable|url",
            // "twitter_link" => "nullable|url",
            // "youtube_link" => "nullable|url",
            // "whatsapp_link" => "nullable|string",
            // "tiktok_link" => "nullable|url",

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = $request->all();

        if ($request->hasFile('restaurant_logo')) {
            $file = $request->file('restaurant_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $data['restaurant_logo'] = 'uploads/' . $fileName;
        }


        $socialLinks = [
            'fb_link' => $request->fb_link,
            'insta_link' => $request->insta_link,
            'twitter_link' => $request->twitter_link,
            'youtube_link' => $request->youtube_link,
            'whatsapp_link' => $request->whatsapp_link,
            'tiktok_link' => $request->tiktok_link,
        ];

        $data['social_links'] = json_encode($socialLinks);

        unset(
            $data['fb_link'],
            $data['insta_link'],
            $data['twitter_link'],
            $data['youtube_link'],
            $data['whatsapp_link'],
            $data['tiktok_link']
        );
        $setting = Setting::first();
        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        return redirect()->back()->with("success", "Settings updated successfully");
    }


    public function workingHours()
    {
        $workingHours = WorkingHour::all();
        return view("admin.settings.working-hours", compact("workingHours"));
    }

    public function updateWorkingHours(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "working_hours" => "required|array",
            "working_hours.*.day" => "required",
            "working_hours.*.open_time" => "required",
            "working_hours.*.close_time" => "required",
            "working_hours.*.is_closed" => "required|boolean",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $workingHours = $request->working_hours;
        foreach ($workingHours as $workingHour) {
            WorkingHour::updateOrCreate(
                ["day" => $workingHour["day"]],
                [
                    "open_time" => $workingHour["open_time"],
                    "close_time" => $workingHour["close_time"],
                    "is_closed" => $workingHour["is_closed"],
                ]
            );
        }

        return redirect()->back()->with("success", "Working hours updated successfully");
    }



    public function settings()
    {
        $settings = Setting::first();
        return response()->json([
            "success" => true,
            "message" => "Settings fetched successfully",
            "data" => $settings
        ]);
    }


    public function paymentSettings()
    {
        $settings = PaymentGateway::where("is_active", true)->with("setting")->get();
        return response()->json([
            "success" => true,
            "message" => "Payment settings fetched successfully",
            "data" => $settings
        ]);
    }
}
