<?php

namespace App\Services;

use App\Libraries\Hashid;
use App\Libraries\RequestBody;
use App\Models\AllowedGroup;
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
        if (empty(RequestBody::post("selected_menu"))) {
            throw new \Exception("Silahkan pilih minimal satu menu", 1);
        }
        $group->access_menu_section()->create([
            "menu_section_id" => Hashid::singleDecode(RequestBody::post("section_id"))
        ]);

        foreach (RequestBody::post("selected_menu") as $n => $selected_menu_id) {
            $group->menu()->attach(Hashid::singleDecode($selected_menu_id));
        }
    }
}
