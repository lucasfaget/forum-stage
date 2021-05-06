<?php

	function eight_char_at_least($mdp) {
		return strlen($mdp) >= 8;
	}

	function contains_upper($mdp) {
		return preg_match('/[A-Z]/', $mdp);
	}

	function contains_lower($mdp) {
		return preg_match('/[a-z]/', $mdp);
	}

	function contains_numeral($mdp) {
		return preg_match('/[0-9]/', $mdp);
	}

	function contains_symbol($mdp) {
		return preg_match('/[^a-zA-Z0-9 ]/', $mdp);
	}

	function contains_space($mdp) {
		return preg_match('/ /', $mdp);
	}

	function passwordOk($mdp) {
		return eight_char_at_least($mdp) and contains_upper($mdp) and contains_lower($mdp) and contains_numeral($mdp) and contains_symbol($mdp) and !contains_space($mdp);
	}

?>