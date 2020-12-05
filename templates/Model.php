<?php

final class #model_name# extends Mapper_Shim {

	public function __construct(\DB\SQL $DB) {
		parent::__construct($DB, '#table_name#');

		// Things you want to happen on insert or on update automatically
		// Good for automatically adding timestamps or generating tokens
		$this->beforeInsert(function($self, $pkeys) {
			//$self->token = hash('sha256', microtime(true).'something witty here');
			//$self->added_dt = Date::getCurrentTimestamp();
			//if(empty($self->added_by_user_id)) {
			//	$self->added_by_user_id = Base::instance()->SESSION['user_id'];
			//}
			//$self->updated_dt = null;
			//$self->updated_by_user_id = 0;
		});
		$this->beforeUpdate(function($self, $pkeys) {
			// $self->updated_dt = Date::getCurrentTimestamp();
			// if(empty($self->updated_by_user_id)) {
			// 	$self->updated_by_user_id = Base::instance()->SESSION['user_id'];
			// }
		});
	}
}