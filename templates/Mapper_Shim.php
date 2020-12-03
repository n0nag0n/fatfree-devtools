<?php 

/**
 * This allows all mappers to be serializable with json
 */
class Mapper_Shim extends Db\SQL\Mapper implements JsonSerializable {

	public function jsonSerialize() {
		return $this->cast();
	}
}