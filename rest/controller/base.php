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

use \Vvveb\System\Core\FrontController;
use Vvveb\System\Core\View;
use Vvveb\System\PageCache;
use Vvveb\System\User\User;
use Vvveb\System\User\Admin;
use function Vvveb\config;
use function Vvveb\__;

#[\AllowDynamicProperties]
class Base {
	protected $global = [];

	function auth() {
		$authMode = config(APP .'.auth.mode');

		if ($authMode == 'http') {
			if (isset($_SERVER['PHP_AUTH_USER'])) {
				$user = $_SERVER['PHP_AUTH_USER'];
				$password = $_SERVER['PHP_AUTH_PW'] ?? '';
				$loginData = compact('user', 'password');
			
				if ($userInfo = Admin::login($loginData)) {
				} else {
					$this->response->addHeader('WWW-Authenticate', 'Basic realm="REST Api"');
					$this->response->addHeader('HTTP/1.0 401 Unauthorized');
					FrontController::notFound(false, __('Auth failed!'), 403);
				}
			} else {
				$this->response->addHeader('WWW-Authenticate', 'Basic realm="REST Api"');
				$this->response->addHeader('HTTP/1.0 401 Unauthorized');
				FrontController::notFound(false, __('Auth failed!'), 403);
			}
		}
	}
	
	function init() {
		$this->response->setType('json');
		if (!REST) {
			die(FrontController::notFound(false, __('REST is disabled!'), 404));
		}
		
		
		$method                      = $this->request->getMethod();
		$this->global['site_id']     = 1;
		$this->global['language_id'] = 1;
		
		
		$this->auth();

		if (in_array($method, ['post', 'put', 'delete', 'patch'])) {
			return $method;
		}
		
	}

	protected function redirect($url = '/', $parameters = []) {
		$redirect = \Vvveb\url($url, $parameters);

		if ($redirect) {
			$url = $redirect;
		}

		if (isset($this->session)) {
			$this->session->close();
		}

		FrontController::closeConnections();
		PageCache::getInstance()->cleanUp();

		die(header("Location: $url"));
	}

	/**
	 * Call this method if the action requires login, if the user is not logged in, a login form will be shown.
	 *
	 */
	protected function requireLogin() {
		$view = view :: getInstance();
		$view :: template('/login.html');

		die(view :: getInstance()->render());
	}

	/**
	 * Call this function if the requeste information was not found, for example if the specifed news, image, profile etc is not found then call this function.
	 * It shows a "Not found" page and it also send 404 http status code, this is usefull for search engines etc.
	 *
	 * @param unknown_type $code
	 * @param mixed $service
	 * @param mixed $statusCode
	 * @param null|mixed $message
	 */
	protected function notFound($service = false, $message = null, $statusCode = 404) {
		return FrontController::notFound($service, $message, $statusCode);
	}
}
