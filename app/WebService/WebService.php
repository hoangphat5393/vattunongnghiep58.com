<?php

namespace App\WebService;

/**
 * Dịch vụ tiện ích dùng qua facade WebService (format tiền, SEO meta, v.v.).
 */
class WebService
{
    public function getSEO(array $data): array
    {
        return $data;
    }

    public function formatMoney12($number, $fractional = null): string
    {
        if ($number === null || $number === '') {
            return '0';
        }

        return number_format((float) $number, 0, ',', '.');
    }

    public function format_price($price): string
    {
        return $this->formatMoney12($price);
    }

    public function objectToArray($object): array
    {
        return json_decode(json_encode($object), true) ?: [];
    }

    public function objectEmpty($object): bool
    {
        if ($object === null) {
            return true;
        }
        if (is_array($object)) {
            return empty($object);
        }

        return empty((array) $object);
    }

    public function getParentCategory($menus, $id_parent, $html = '')
    {
        return $html;
    }

    public function showMenuhtml($menus, $menu_current, $id_parent, $html = '', $i = 0)
    {
        return $html;
    }

    public function showMenuMobilehtml($menus, $menu_current, $id_parent, $html = '', $i = 0)
    {
        return $html;
    }

    public function showMutiCategory($menus, $array_current, $id_parent, $html = '', $i = 0)
    {
        return $html;
    }

    public function showOptionCategory($menus, $menu_current, $id_parent, $text = '')
    {
        return $text;
    }
}
