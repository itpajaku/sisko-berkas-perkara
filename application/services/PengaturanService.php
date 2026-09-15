<?php

namespace App\Services;

use App\Libraries\Hashid;
use App\Libraries\RequestBody;
use App\Models\AccessMenu;
use App\Models\AccessMenuSection;
use App\Models\AllowedGroup;
use App\Models\Menu;
use App\Models\SysGroup;

class PengaturanService
{
    protected static $instances = [];

    public static function getInstance(): PengaturanService
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function addAllowedGroup()
    {
        $groupid = Hashid::singleDecode(RequestBody::post("group_id"));
        $groupExists = AllowedGroup::where("group_id", $groupid)->first();

        if ($groupExists) {
            throw new \Exception("Grup akun ini sudah terdaftar", 1);
        }

        $sysgroup = SysGroup::find($groupid);
        if (!$sysgroup) {
            throw new \Exception("Grup SIPP tidak ditemukan", 1);
        }

        AllowedGroup::create([
            "group_id" => $sysgroup->groupid,
            "group_name" => $sysgroup->name,
        ]);
    }

    public function attachMenuToGroup($group_id)
    {
        $group = AllowedGroup::where('group_id', $group_id)->first();
        if (!$group) {
            throw new \Exception("Grup akun tidak ditemukan", 1);
        }

        $selected = RequestBody::post('selected_menu');
        $selectedMenuIds = [];
        if (!empty($selected) && is_array($selected)) {
            foreach ($selected as $encodedId) {
                $decoded = Hashid::singleDecode($encodedId);
                if ($decoded) {
                    $selectedMenuIds[] = $decoded;
                }
            }
        }

        // Hapus akses menu lama untuk grup ini
        AccessMenu::where('group_id', $group_id)->delete();

        // Tambahkan akses menu yang dipilih
        foreach ($selectedMenuIds as $menuId) {
            AccessMenu::create([
                'group_id' => $group_id,
                'menu_id' => $menuId,
            ]);
        }

        // Sinkronkan access_menu_section agar section menu otomatis terdaftar
        if (!empty($selectedMenuIds)) {
            $sectionIds = Menu::whereIn('id', $selectedMenuIds)->pluck('section_id')->filter()->unique();
            foreach ($sectionIds as $secId) {
                AccessMenuSection::firstOrCreate([
                    'group_id' => $group_id,
                    'menu_section_id' => $secId,
                ]);
            }
        }
    }

    public function detach_section($group_id, $section_id)
    {
        $group = AllowedGroup::where("group_id", $group_id)->first();
        if ($group) {
            $group->access_menu_section()->where('menu_section_id', $section_id)->delete();
            // Hapus juga menu di dalam section tersebut dari access_menu
            $menuIds = Menu::where('section_id', $section_id)->pluck('id')->toArray();
            if (!empty($menuIds)) {
                AccessMenu::where('group_id', $group_id)->whereIn('menu_id', $menuIds)->delete();
            }
        }
    }
}
