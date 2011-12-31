<?php

	class auth
	{
		var $session = NULL;
		var $cheked = FALSE;
		var $deny = TRUE;

		function __construct($session) {
			$this->session = $session;
		}

		function check() {
			if ($this->cheked === FALSE) {
				if (isset($_SESSION['user'])) {
					$this->deny = FALSE;
					return true;
				} else {
					$this->deny = TRUE;
					return false;
				}
				$this->cheked = TRUE;

			}
		}

		function isAllow() {
			$this->check();
			return $this->deny;
		}

		function isDeny() {
			if ($this->session != NULL) $this->check();
			if ($this->deny == TRUE) {
				header('Location: /');
				die();
			}
		}

	}