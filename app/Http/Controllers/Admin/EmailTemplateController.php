<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backend\EmailTemplate;
use App\Http\Requests\Admin\EmailTemplate\StoreEmailTemplate;
use App\Http\Requests\Admin\EmailTemplate\UpdateEmailTemplate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = EmailTemplate::filter($request)->orderByDesc('sort')->paginate(20)->appends($request->all());

        $total_item = $data->count();

        return view('backend.email-template.index', compact('data', 'total_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $arrayGroup = $this->arrayGroup();
        return view('backend.email-template.single', compact('arrayGroup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmailTemplate $request)
    {
        $data = request()->except(['category_id', 'created_at', 'submit']);

        $data['description'] = $data['description'] ? htmlspecialchars($data['description']) : '';
        $data['content'] = $data['content'] ? htmlspecialchars($data['content']) : '';

        // USER ID
        $data['user_id'] = Auth::guard('admin')->user()->id;

        $shortcode = EmailTemplate::create($data);
        $insert_id = $shortcode->id;

        // Update sort
        $shortcode->update(['sort' => $insert_id]);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {
            $msg = "Email template has been created successfully";
            $url = route('admin.email-template.edit', array($insert_id));
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.email-template.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTemplate $emailTemplate, $id)
    {
        $emailTemplate = EmailTemplate::find($id);
        return view('backend.email-template.show', compact('emailTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $emailTemplate, $id)
    {
        $arrayGroup = $this->arrayGroup();
        $emailTemplate = EmailTemplate::findorfail($id);

        if ($emailTemplate) {
            return view('backend.email-template.single', compact('emailTemplate', 'arrayGroup'));
        } else {
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmailTemplate $request, EmailTemplate $emailTemplate)
    {
        $data = request()->except(['created_at', 'submit', 'user_id']);

        $emailTemplate = EmailTemplate::findOrFail($request->id);
        $emailTemplate->update($data);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {
            $msg = "Email template has been updated successfully";
            $url = route('admin.email-template.edit', array($request->id));
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.email-template.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $emailTemplate, $id)
    {
        $emailTemplate->find($id)->delete();
        return redirect()->route('admin.email-template.index')->with('success', 'Email template deleted successfully.');
    }

    public function arrayGroup()
    {
        return  [
            'contact' => 'Inform contact to user',
            'contact_admin' => 'Inform contact to admin',
            'contact_setup' => 'Inform setup to admin',
            'transaction_register' => 'Inform transaction register to user',
            'transaction_register_admin' => 'Inform transaction register to admin',
            'purchase' => 'Inform purchase to user',
            'purchase_admin' => 'Inform purchase to admin',
            // 'order_payment_success' => 'Thông báo thanh toán thành công',
            // 'order_to_admin' => 'Thông báo có đơn hàng tới Admin',
            // 'order_to_user' => 'Thông báo có đơn hàng tới User',
            // 'forgot_password' => trans('email.email_action.forgot_password'),
            // 'welcome_customer_sys' =>  trans('email.email_action.welcome_customer_to_admin'),
            // 'welcome_customer' =>  trans('email.email_action.welcome_customer'),
            // 'contact_to_admin' =>  trans('email.email_action.contact_to_admin'),
            // 'baogia' =>  "Thông báo có báo giá tới user",
            // 'baogia_to_admin' =>  "Thông báo có báo giá tới admin",
            // 'request_payment_success' =>  'Thông báo thanh toán đơn hàng thành công',
            // 'other' =>  trans('email.email_action.other'),
        ];
    }
}
