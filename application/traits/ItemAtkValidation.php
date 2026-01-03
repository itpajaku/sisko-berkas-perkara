<?php

namespace App\Traits;

trait ItemAtkValidation
{
	private function validate_form()
	{
		$this->form_validation->set_rules([
			[
				'field' => 'name',
				'label' => 'Nama Item',
				'rules' => 'required|min_length[3]|max_length[255]',
				'errors' => [
					'required'   => '%s wajib diisi.',
					'min_length' => '%s minimal 3 karakter.',
					'max_length' => '%s maksimal 255 karakter.',
				]
			],
			[
				'field' => 'type',
				'label' => 'Tipe Item',
				'rules' => 'required|in_list[consume,assets,etc]',
				'errors' => [
					'required' => '%s wajib dipilih.',
					'in_list'  => '%s tidak valid.',
				]
			],
			[
				'field' => 'icon',
				'label' => 'Icon',
				'rules' => 'max_length[32]',
				'errors' => [
					'max_length' => '%s maksimal 32 karakter.',
				]
			],
			[
				'field' => 'desc',
				'label' => 'Deskripsi',
				'rules' => 'required|max_length[512]',
				'errors' => [
					'max_length' => '%s maksimal 512 karakter.',
					'required' => 'Tolong isi keterangan barang'
				]
			],
		]);

		if ($this->form_validation->run() == FALSE) {
			$ul = "<ul>";
			foreach ($this->form_validation->error_array() as $er) {
				$ul .= "<li>$er</li>";
			}
			$ul .= "</ul>";
			throw new \Exception($ul);
		}
	}
}
