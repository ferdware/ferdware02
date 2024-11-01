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

namespace Vvveb\Controller;

use Vvveb\System\Traits\Listing as ListingTrait;

class Listing extends Base {
	use ListingTrait;

	function index() {
		$results = $this->get();

		if (isset($results['count'])) {
			$count = $results['count'] ?: 0;
			$limit = $results['limit'] ?: 0;
			$page  = $results['page'] ?: 1;
			//$pages = ($count && $limit) ? ceil($count / $limit) : 0;

			$this->response->addHeader('X-V-count', $count);
			$this->response->addHeader('X-V-limit', $limit);
			//$this->response->addHeader('X-V-page', $page);
			//$this->response->addHeader('X-V-pages', $pages);
		}

		return $results[$this->type] ?? [];
	}
}
