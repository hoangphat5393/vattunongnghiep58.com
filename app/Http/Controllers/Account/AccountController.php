<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerPasswordRequest;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Services\CustomerAccountService;
use Auth;
use Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    public function __construct(
        protected CustomerAccountService $accountService,
    ) {
        parent::__construct();
    }

    public function index(): RedirectResponse
    {
        return redirect()->route('customer.profile');
    }

    public function profile(): View
    {
        $this->data['user'] = Auth::user();

        $this->data['seo'] = [
            'seo_title' => 'Customer | Profile',
            'seo_image' => '',
            'seo_description' => 'Customer Update Profile',
            'seo_keyword' => 'Update, Profile',
        ];

        return view($this->templatePath.'.account.profile', $this->data);
    }

    public function updateProfile(UpdateCustomerProfileRequest $request): RedirectResponse
    {
        $this->accountService->updateProfile(
            Auth::user(),
            $request->validated(),
            $request->file('avatar_upload'),
        );

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Thông tin tài khoản đã được cập nhật.');
    }

    public function changePassword(): View
    {
        $this->data['user'] = Auth::user();
        $this->data['seo'] = [
            'seo_title' => 'Customer | Change Password',
            'seo_image' => '',
            'seo_description' => 'Customer Change Password',
            'seo_keyword' => 'Customer, Password',
        ];

        return view($this->templatePath.'.account.auth.change_pass')->with(['data' => $this->data]);
    }

    public function postChangePassword(UpdateCustomerPasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->update([
            'password' => bcrypt($request->input('new_password')),
        ]);

        return redirect()
            ->route('customer.password.edit')
            ->with('success', 'Mật khẩu đã được thay đổi.');
    }

    public function myOrder(): View
    {
        $this->data['user'] = Auth::user();
        $this->data['orders'] = $this->accountService->paginatedOrdersForUser(Auth::id());
        $this->data['orderPayment'] = $this->accountService->orderPaymentLabels();
        $this->data['orderStatus'] = $this->accountService->orderStatusLabels();

        $this->data['seo'] = [
            'seo_title' => 'Customer |  My Order',
            'seo_image' => '',
            'seo_description' => 'Customer My Order',
            'seo_keyword' => 'Order',
        ];

        return view($this->templatePath.'.account.myorder', $this->data);
    }

    public function myOrderDetail(int|string $id_cart): View
    {
        $order = $this->accountService->findOrderForUserOrAbort(Auth::id(), $id_cart);

        $this->data['shop_payment_method'] = $this->accountService->activePaymentMethodLabels();
        $this->data['order'] = $order;
        $this->data['order_detail'] = $this->accountService->orderDetailsWithProducts($order);
        $this->data['orderPayment'] = $this->accountService->orderPaymentLabels();
        $this->data['orderStatus'] = $this->accountService->orderStatusLabels();

        $this->data['seo'] = [
            'seo_title' => 'Đơn hàng - '.$order->cart_code,
        ];

        return view($this->templatePath.'.account.orderdetail', ['data' => $this->data]);
    }
}
