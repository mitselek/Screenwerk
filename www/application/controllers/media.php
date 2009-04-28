<?php

class Media extends Controller {

	function __construct() {
		parent::Controller();
		
		$this->load->model('Media_model', 'media');
		
		//$this->output->enable_profiler(TRUE);
	}



	function index() {
		$view['upload_folder'] = '/'. $this->session->customer_id .'/';

		$view['data'] = $this->media->get_list();
		$view['page_menu_code'] = 'media';
		$view['page_menu_code'] = 'media';
		$view['show_edit_link'] = isset($this->session->forms[$this->router->class .'']);
		$view['page_content'] = $this->load->view('media/media_list', $view, True);
		
		foreach($view['data'] as $media_id => $media_content) {
			$view['box']['media_'. $media_id]['hidden'] = TRUE;
			$view['box']['media_'. $media_id]['content'] = $this->load->view('media/media_box', $media_content, True);
		}
		
		$view['box']['upload']['content'] = $this->load->view('media/media_upload', $view, True);
		
		$this->load->view('main_page_view', $view);
	}


	
	function thumbnail($media_id, $thumb_no = null) {

		$this->load->helper('download');
		$this->load->helper('file');

		$dir = DIR_FTP_THUMBS .'/';
		
		if($thumb_no) {
			$file = $media_id .'_'. $thumb_no .'.png';
		} else {
			$file = $media_id .'.png';
		}
		
		if(read_file($dir.$file)) {
			header('Content-Type: image/png');
			print(file_get_contents($dir.$file));
		} else {
			show_404('media/thumbnail');
		}
	}

}
?>
