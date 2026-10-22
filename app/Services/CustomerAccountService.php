<?php

namespace App\Services;

use App\Models\Frontend\Order;
use App\Models\Frontend\OrderItem;
use App\Models\Frontend\ShopOrderPaymentStatus;
use App\Models\Frontend\ShopOrderStatus;
use App\Models\Frontend\User;
use App\Models\ShopPaymentMethod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

class CustomerAccountService
{
    public function linkGuestOrdersToUser(int $userId, string $email): int
    {
        if (! Schema::hasTable('orders')) {
            return 0;
        }

        return Order::query()
            ->whereNull('user_id')
            ->where('email', $email)
            ->update(['user_id' => $userId]);
    }

    /**
     * @param  array{fullname: string, phone?: ?string, address?: ?string}  $validated
     */
    public function updateProfile(User $user, array $validated, ?UploadedFile $avatarFile = null): User
    {
        $avatarPath = $user->avatar;

        if ($avatarFile !== null) {
            $storedPath = $avatarFile->store('avatars', 'public');
            $avatarPath = 'storage/'.$storedPath;
        }

        $user->update([
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'] ?? $user->phone,
            'address' => $validated['address'] ?? $user->address,
            'avatar' => $avatarPath,
        ]);

        return $user->fresh();
    }

    public function paginatedOrdersForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Order::query()
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findOrderForUserOrAbort(int $userId, int|string $orderId): Order
    {
        $order = Order::query()
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (! $order) {
            abort(403);
        }

        return $order;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function orderDetailsWithProducts(Order $order): Collection
    {
        return OrderItem::with('product')
            ->where('order_id', $order->id)
            ->get();
    }

    /**
     * @return array<int|string, string>
     */
    public function orderStatusLabels(): array
    {
        return ShopOrderStatus::getIdAll();
    }

    /**
     * @return array<int|string, string>
     */
    public function orderPaymentLabels(): array
    {
        return ShopOrderPaymentStatus::getIdAll();
    }

    /**
     * @return array<string, string>
     */
    public function activePaymentMethodLabels(): array
    {
        return ShopPaymentMethod::query()
            ->where('status', 1)
            ->get()
            ->pluck('name', 'code')
            ->toArray();
    }

    /**
     * @return array<int|string, string>
     */
    public function paymentMethodLabels(): array
    {
        return ShopPaymentMethod::pluck('name', 'id')->toArray();
    }
}
