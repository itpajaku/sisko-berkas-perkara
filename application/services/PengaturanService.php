<?php

namespace App\Services;

use App\Libraries\Hashid;
use App\Libraries\RequestBody;
use App\Models\AccessMenu;
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
        $groupExists = AllowedGroup::find($groupid);

        if ($groupExists) {
            throw new \Exception("Akun ini sudah ada", 1);
        }

        $sysgroup = SysGroup::find($groupid);

        AllowedGroup::create([
            "group_id" => $sysgroup->groupid,
            "group_name" => $sysgroup->name,
        ]);
    }

    public function attachMenuToGroup($group_id)
    {
        $group = AllowedGroup::where('group_id', $group_id)->first();
        if (!$group) {
            throw new \Exception("Selected Group Not Found", 1);
        }

        $selected_menu_id = collect(RequestBody::post('selected_menu'));
        $group->menu()->sync($selected_menu_id->transform(function ($item) {
            return Hashid::singleDecode($item);
        })->all());
    }

    public function detach_section($group_id, $section_id)
    {
        $group = AllowedGroup::where("group_id", $group_id)->first();
        $group->access_menu_section()->where('menu_section_id', $section_id)->delete();
    }
}
