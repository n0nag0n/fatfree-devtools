<?php
declare(strict_types=1);

/**
 * This is an example base controller to help establish a foundation. You can use this
 * to create other base controllers (especially for the before/afterroute events).
 * This is likely where you will put code to authenticate the user if they can
 * view the route they are intending. 
 */
abstract class Base_Controller {

	/**
	 * This will be called before a route is executed. Return false to deny the request
	 *
	 * @param \Base $fw
	 * @return bool
	 */
	public function beforeroute(\Base $fw) {

	}

	/**
	 * This will be called after a route is executed. Return false to deny the request
	 *
	 * @param \Base $fw
	 * @return bool
	 */
	public function afterroute(\Base $fw) {

	}

	/**
	 * Simply outputs the array as a JSON response
	 *
	 * @param array $array
	 * @return void
	 */
	protected function outputJsonResponse(array $array): void {
		http_response_code(200);
		echo json_encode($array);
	}

	/**
	 * Simply outputs the string
	 *
	 * @param string $html
	 * @return void
	 */
	protected function outputHtmlResponse(string $html): void {
		http_response_code(200);
		echo $html;
	}
}