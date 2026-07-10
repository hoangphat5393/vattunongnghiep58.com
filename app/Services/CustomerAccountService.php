<?php

namespace App\Services;

use App\Models\Addtocard;
use App\Models\Addtocard_Detail;
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
        if (! Schema::hasTable('shop_orders')) {
            return 0;
        }

        return Addtocard::query()
            ->whereNull('user_id')
            ->where('cart_email', $email)
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
        return Addtocard::query()
            ->where('user_id', $userId)
            ->orderByDesc('cart_id')
            ->paginate($perPage);
    }

    public function findOrderForUserOrAbort(int $userId, int|string $cartId): Addtocard
    {
        $order = Addtocard::query()
            ->where('cart_id', $cartId)
            ->where('user_id', $userId)
            ->first();

        if (! $order) {
            abort(403);
        }

        return $order;
    }

    /**
     * @return Collection<int, Addtocard_Detail>
     */
    public function orderDetailsWithProducts(Addtocard $order): Collection
    {
        return Addtocard_Detail::with('product')
            ->where('cart_id', $order->cart_id)
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
}
