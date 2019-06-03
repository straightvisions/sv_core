<?php
	function sv_core_php_version_check(){
		if(version_compare( phpversion(), '7.0.0', '>=' )){
			return true;
		}else{
			return false;
		}
	}