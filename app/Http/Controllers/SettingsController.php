<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'company_name'       => Setting::get('company_name', 'solution'),
            'contact_email'      => Setting::get('contact_email', 'solution@gmail.com'),
            'phone_number'       => Setting::get('phone_number', '+228 90 12 34 56'),
            'address'            => Setting::get('address', '123 Rue Principale, Lomé, Togo'),
            'parking_capacity'   => Setting::get('parking_capacity', 500),
            'operating_hours'    => Setting::get('operating_hours', '24h'),
            'currency'           => Setting::get('currency', 'XOF'),
            'language'           => Setting::get('language', 'fr'),
            'email_notifications'=> Setting::get('email_notifications', 1),
            'sms_notifications'  => Setting::get('sms_notifications', 0),
            'capacity_alerts'    => Setting::get('capacity_alerts', 1),
        ]);
    }

    public function update(Request $request)
    {
        $fields = $request->only([
            'company_name','contact_email','phone_number','address',
            'parking_capacity','operating_hours','currency','language',
            'email_notifications','sms_notifications','capacity_alerts'
        ]);

        foreach ($fields as $key => $value) {
            Setting::set($key, $request->has($key) ? $value : 0);
        }

        return redirect()->back()->with('success', 'Paramètres mis à jour.');
    }
}