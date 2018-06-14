<?php if (!defined('BASEPATH'))     exit('No direct script access allowed');

/**
 *
 * @author MinhVan
 */
function kq_phantram($mc, $vc, $pc, $sc, $mm, $vm, $sm){
	if($mm != 0 && $sm != 0 && $vc != 0 ){
		return ($mc * $pc * $sm * $vm) / ($mm * $sc * $vc);
	}
	return false;
}

function g_L($kq_p, $titrong){
	if ($titrong != 0) {
		return $kq_p*$titrong*10;
	}
	return false;
}

?>
