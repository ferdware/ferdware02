<?php

/**
 * Vvveb
 *
 * Copyright (C) 2022  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Vvveb\Controller\Localization;

use function Vvveb\__;
use Vvveb\Controller\Crud;
use function Vvveb\download;
use function Vvveb\filter;
use function Vvveb\installedLanguages;
use Vvveb\System\CacheManager;

class Language extends Crud {
	protected $type = 'language';

	protected $module = 'localization';

	protected $installUrl = 'https://raw.githubusercontent.com/Vvveb/{code}/master/LC_MESSAGES/';

	protected $files = ['vvveb.po', 'landing-theme.po'];

	protected $listUrl = 'https://www.vvveb.com/page/contribute#language';

	function save() {
		CacheManager::clearObjectCache(APP, 'languages');
		$this->redirect = false;

		parent::save();
		//if language set as default set other default language as false
		if (isset($this->data['default'])) {
			//$this->model->edit();
		}

		$this->index();
	}

	function install() {
		$code      = filter('/[-\w]+/', $this->request->post['code']);
		$url       = str_replace('{code}', $code, $this->installUrl);
		$available = false;

		require DIR_SYSTEM . 'functions' . DS . 'php-mo.php';

		foreach ($this->files as $file) {
			$translations = download($url . $file);

			if ($translations) {
				$available = true;
				$folder    = DIR_ROOT . 'locale' . DS . $code . DS . 'LC_MESSAGES';
				$poFile    = $folder . DS . $file;
				@mkdir($folder, 0755 & ~umask(), true);

				if (file_put_contents($poFile, $translations)) {
					if (phpmo_convert($poFile)) {
						$this->view->success['language'] = __('Language pack installed!');
					} else {
						$this->view->errors['language'] = __('Language compilation failed!');

						break;
					}
				} else {
					$this->view->errors[] = __('Error writing language files!');

					break;
				}
			} else {
				break;
			}
		}

		if (! $available) {
			$this->view->errors[] = __('Language pack not available!');
			$this->view->info[]   = sprintf(__('Check available translations at %s'), '<a href="' . $this->listUrl . '" target="_blank">' . $this->listUrl . '</a>');
		}

		return $this->index();
	}

	function index() {
		parent::index();
		$languageList = include DIR_SYSTEM . 'data' . DS . 'languages-list.php';
		$installed    = installedLanguages();

		foreach ($installed as $l) {
			$languageList[$l]['installed'] = true;
		}

		$this->view->language_list  = $languageList;

		$this->view->status  = [1 => 'Active', 0 => 'Inactive'];
		$this->view->default = [0 => 'No', 1 => 'Yes'];
	}
}
