<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$do   = in_array($_GPC['do'], array('upload')) ? $_GPC['do'] : 'upload';
$type = in_array($_GPC['type'], array('image','audio')) ? $_GPC['type'] : 'image';

$result = array('error' => 1, 'message' => '');

if ($do == 'upload') {
	if($type == 'image'){
		$setting = $_W['setting']['upload'][$type];
		$result = array(
			'jsonrpc' => '2.0',
			'id' => 'id',
			'error' => array('code' => 1, 'message'=>''),
		);
		load()->func('file');
		
		if (!empty($_FILES['file']['name'])) {
			if ($_FILES['file']['error'] != 0) {
				$result['error']['message'] = '上传失败，请重试！';
				die(json_encode($result));
			}
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$ext = strtolower($ext);

			$file = file_upload($_FILES['file']);
			if (is_error($file)) {
				$result['error']['message'] = $file['message'];
				die(json_encode($result));
			}

			$pathname = $file['path'];
			$fullname = ATTACHMENT_ROOT . '/' . $pathname;

			$thumb = empty($setting['thumb']) ? 0 : 1; 			$width = intval($setting['width']); 			if ($thumb == 1 && $width > 0) {
				$thumbnail = file_image_thumb($fullname, '', $width);
				@unlink($fullname);
				if (is_error($thumbnail)) {
					$result['message'] = $thumbnail['message'];
					die(json_encode($result));
				} else {
					$filename = pathinfo($thumbnail, PATHINFO_BASENAME);
					$pathname = $thumbnail;
					$fullname = ATTACHMENT_ROOT .'/'.$pathname;
				}
			}

			$info = array(
				'name' => $_FILES['file']['name'],
				'ext' => $ext,
				'filename' => $pathname,
				'attachment' => $pathname,
				'url' => $_W['attachurl'] . $pathname,
				'is_image' => 1,
				'filesize' => filesize($fullname),
			);
			$size = getimagesize($fullname);
			$info['width'] = $size[0];
			$info['height'] = $size[1];

			if (!empty($_W['setting']['remote']['type'])) {
				$remotestatus = file_remote_upload($pathname);
				if (is_error($remotestatus)) {
					$result['message'] = '远程附件上传失败，请检查配置并重新上传';
					file_delete($pathname);
					die(json_encode($result));
				} else {
					file_delete($pathname);
				}
			}
			pdo_insert('core_attachment', array(
				'uniacid' => $uniacid,
				'uid' => $_W['uid'],
				'filename' => $_FILES['file']['name'],
				'attachment' => $pathname,
				'type' => $type == 'image' ? 1 : 2,
				'createtime' => TIMESTAMP,
			));
			die(json_encode($info));
		} else {
			$result['error']['message'] = '请选择要上传的图片！';
			die(json_encode($result));
		}
	}
}
