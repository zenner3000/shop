<?php

class ModelExtensionModule extends Model {

	//获取到指定模块的信息，这些信息存在一个 setting的字段里面，看上去像是json信息
	public function getModule($module_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");
		
		if ($query->row) {
			return unserialize($query->row['setting']);
		} else {
			return array();	
		}
	}		
}