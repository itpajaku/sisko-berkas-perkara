<?php

namespace App\Services;

use App\Libraries\AuthData;
use App\Models\AccessMenuSection;
use App\Models\MenuSection;

class MenuService
{
	protected static $menu = [];

	public static function getMenu()
	{
		if (!empty(self::$menu)) {
			return self::$menu;
		}

		$user = AuthData::getUserData();
		if (!$user || empty($user->groupid)) {
			return collect([]);
		}

		$groupId = $user->groupid;

		// Jika Administrator (groupid = 1), tampilkan semua menu section dan menu yang aktif
		if ($groupId == 1) {
			self::$menu = MenuSection::where('is_active', 1)
				->with(['menu' => function ($q) {
					$q->where('is_active', 1);
				}])
				->get()
				->map(function ($section) {
					$item = new \stdClass();
					$item->menu_section = $section;
					return $item;
				});

			return self::$menu;
		}

		// Untuk user / group lainnya, ambil berdasarkan akses yang diberikan
		self::$menu = AccessMenuSection::with(['menu_section.menu' => function ($q) use ($groupId) {
			$q->whereHas('access_menu', function ($qq) use ($groupId) {
				$qq->where('group_id', $groupId);
			})->where('is_active', 1);
		}])
			->where("group_id", $groupId)
			->get()
			->filter(function ($accessSection) {
				return $accessSection->menu_section && $accessSection->menu_section->is_active == 1;
			});

		return self::$menu;
	}
}
