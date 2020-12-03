<?php

class Template_Helper extends \Prefab {
	public static function length($val) {
		return is_array($val) || is_object($val) ? count($val) : (is_string($val) ? strlen($val) : intval($val));
	}
}