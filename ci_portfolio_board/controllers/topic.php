<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Topic extends CI_Controller {
	
	function __construct() {
		parent::__construct();

		$this->load->database();
		$this->load->model("topic_model");
	}

	public function index()
	{
		$data = $this->topic_model->gets();

		$this->load->view("header");
		$this->load->view("main", array("topics"=>$data));
		$this->load->view("footer");
	}

	public function get($id)
	{
		$topic = $this->topic_model->get($id);

		$this->load->view("header");
		$this->load->helper(array("url","korean"));
		$this->load->view("get",array("topic"=>$topic));
		$this->load->view("footer");
	}

	function add() {
		// 로그인 필요
		
		// 로그인이 되어 있지 않다면 로그인 페이지로 리다이렉션
		if (!$this->session->userdata('is_login')) {
			$this->load->helper("url");
			redirect("/auth/login");
		} else {
			echo "<a href='/index.php/auth/logout'>로그아웃</a>";
		}


		$topic = $this->topic_model->gets();

		$this->load->view("header");

		$this->load->library('form_validation');

        $this->form_validation->set_rules('title', '제목t', 'required');		// required:반드시입력되야할 필드, 제목:에러시 표시내용
        $this->form_validation->set_rules('description', '본문1', 'required');
        
        if ($this->form_validation->run() == FALSE)
        {
             $this->load->view('add');
        }
        else
        {
            $topic_id = $this->topic_model->add($this->input->post('title'), $this->input->post('description'));
            $this->load->helper('url');
            redirect('/topic/get/'.$topic_id);	// 이동하고 싶은 페이지로.
        }

		$this->load->view("footer");
	}

	function upload_receive() {
		// 사용자가 업로드 한 파일을 /static/user/ 디렉토리에 저장한다.
		$config['upload_path'] = './static/user';
		// git,jpg,png 파일만 업로드를 허용한다.
		$config['allowed_types'] = 'gif|jpg|png';
		// 허용되는 파일의 최대 사이즈(KB)
		$config['max_size'] = '100';
		// 이미지인 경우 허용되는 최대 폭
		$config['max_width']  = '1024';
		// 이미지인 경우 허용되는 최대 높이
		$config['max_height']  = '768';
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload("user_upload")) {	// ->do_upload()은  do_upload("userfile")과 같음
			echo  $this->upload->display_errors();
		} else {
			echo "성공";
			$data = array('upload_data' => $this->upload->data());
			var_dump($data);
		}

	}

	function upload_receive_from_ck() {
		// 사용자가 업로드 한 파일을 /static/user/ 디렉토리에 저장한다.
		$config['upload_path'] = './static/user';
		// git,jpg,png 파일만 업로드를 허용한다.
		$config['allowed_types'] = 'gif|jpg|png';
		// 허용되는 파일의 최대 사이즈(KB)
		$config['max_size'] = '100';
		// 이미지인 경우 허용되는 최대 폭
		$config['max_width']  = '1024';
		// 이미지인 경우 허용되는 최대 높이
		$config['max_height']  = '768';

		$this->load->library('upload', $config);	//***

		if ( ! $this->upload->do_upload("upload")) {	// 무조건 "upload"로!
			echo  $this->upload->display_errors();
		} else {
			$CKEditorFuncNum = $this->input->get("CKEditorFuncNum"); // '콜백의 식별 ID 값' - 무조건
			 $data = $this->upload->data();
			 $filename = $data["file_name"];
			$url = "/static/user/".$filename;		// '파일의 URL'
			$resultMsg = '전송완료 메시지';
			 		
			if (strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")==true) {	// ie와 크롬 인코딩 크로스브라우징
				$url = iconv("UTF-8","EUC-KR",$url);
				$resultMsg = iconv("UTF-8","EUC-KR",$resultMsg);
			}

			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '".$url."', '".$resultMsg."')</script>";

		}

	}

	function upload_form() {
		$this->_head();
	
		$this->load->view("upload_form");

		$this->load->view("footer");
	}

	private function _head() {
		var_dump($this->session->userdata('session_test'));
		$this->session->set_userdata('session_test','egoing');
		
		$topic = $this->topic_model->gets();
		$this->load->view("header");
	}
}
