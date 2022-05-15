<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	private $boardName = "";
	
	function __construct() {
		parent::__construct();
	}
	
	public function index() {
		// 메인 화면
		$cssFile = "content";
		$this->load->view("portfolio/header_v", array("cssFile"=>$cssFile));
		$this->load->view("portfolio/content_v");
		$this->load->view("portfolio/footer_v");
	}
	
	/*
	 * 게사판 메인
	 * boardName: 생성 보드명, contentName: 삽입, 수정, 목록 등 구분자
	 */
	public function shboard($boardName, $contentNamet) {
		$this->boardName = $boardName;

		$boardTable = "Board_".$boardName;	// DB 테이블명
		$contentName = "bd_".$boardName."_".$contentNamet;	// 뷰 파일명
		$data = "";
		
		// manage DB
		$data = $this->manage_board_db($boardTable, $contentNamet, $data);

		$this->load->view("portfolio/header_v", array("cssFile"=>$contentName));
		$this->load->view("portfolio/".$contentName."_v", array("vs"=>$data));
		$this->load->view("portfolio/footer_v");
	}

	/*
	 * 보드 DB 처리 분기
	 */
	private function manage_board_db($boardTable, $contentNamet, $data) {
		$this->load->helper(array("url","sh_func"));
	
		switch ($contentNamet) {
			case "process_add":
				
				// 파일 처리
				$filename = $this->upload_receive_add();

				// db 처리
				$this->load->database();
				$this->load->model("board_m");
				$ret = $this->board_m->process_add($boardTable,$filename);
				
				if ($ret=="blank") {
					echo jsAlert("빈 값이 있습니다. 다시 입력해주십시오.");
					echo jsToBefore();
				} else if (!$ret) {
					echo jsAlert("DB insert 에러! 다시 입력해주십시오.");
					echo jsToBefore();
				} else if ($ret) {
					redirect("/portfolio/main/shboard/".$this->boardName."/list");
				}

				break;
			
			case "list":
				// db 처리
				$this->load->database();
				$this->load->model("board_m");
				$data = $this->board_m->lists($boardTable);

				break;
			case "detail":
				// db 처리
				$this->load->database();
				$this->load->model("board_m");
				$data = $this->board_m->detail($boardTable);

				if (!$data) {
					echo jsAlert("해당 내용이 없습니다.");
					echo jsToBefore();
				}

				break;
			case "modify":
			case "del";
				// db 처리
				$this->load->database();
				$this->load->model("board_m");
				$data = $this->board_m->detail($boardTable);

				// 파일 필드 추출
				$data[0]->files = explode("/-/",$data[0]->filename);

				break;
			
			case "process_modify":

				// 파일 처리
				$filename = $this->upload_receive_add();

				// db 처리
				$this->load->database();
				$this->load->model("board_m");
				$ret = $this->board_m->process_modify($boardTable, $filename);
				
				if ($ret=="blank") {
					echo jsAlert("빈 값이 있습니다. 다시 입력해주십시오.");
					echo jsToBefore();
				} else if ($ret=="noMatchPwd") {
					echo jsAlert("비번 불일치! 다시 입력해주십시오.");
					echo jsToBefore();
				} else if ($ret) {
					redirect("/portfolio/main/shboard/".$this->boardName."/list");
				}

				break;
			
			case "process_del":
				// db 처리
				$this->load->database();
				$this->load->model("board_m");
				$ret = $this->board_m->process_del($boardTable);

				if ($ret=="blank") {
					echo jsAlert("빈 값이 있습니다. 다시 입력해주십시오.");
					echo jsToBefore();
				} else if ($ret=="noMatchPwd") {
					echo jsAlert("비번 불일치! 다시 입력해주십시오.");
					echo jsToBefore();
				} else if ($ret) {
					redirect("/portfolio/main/shboard/".$this->boardName."/list");
				}

				break;
			default :
				break;
		}

		return $data;
	}

	/*
	 * ckediter 업로드 파일 처리
	 */
	function upload_receive_from_ck() {
		// 사용자가 업로드 한 파일을 /static/user/ 디렉토리에 저장한다.
		$config['upload_path'] = './static/user/';
		//git,jpg,png 파일만 업로드를 허용한다.
		$config['allowed_types'] = 'gif|jpg|png';
		// 허용되는 파일의 최대 사이즈(KB)
		$config['max_size'] = '1000';

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

	function upload_receive_add() {
		// 사용자가 업로드 한 파일을 /static/user/ 디렉토리에 저장한다.
		$config['upload_path'] = './static/user/board_files/';
		// 모든 파일 업로드를 허용한다.
		$config['allowed_types'] = '*';
		// 허용되는 파일의 최대 사이즈(KB)
		$config['max_size'] = '10000';

		$this->load->library('upload', $config);	//***

		if ( ! $this->upload->do_upload("fileToUpload")) {
			//echo  $this->upload->display_errors();
			$filename = "";
		} else {
			$data = $this->upload->data();
			$filename = $data["file_name"];
		}

		return $filename;
	}

	function doAjexfileuploader() {
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{

				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else 
		{
				$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
				$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
				//for security reason, we force to remove all uploaded file
				//@unlink($_FILES['fileToUpload']);
				
				// file server load
				//$this->upload_receive_add();

				$uploadfile = "/static/user/board_files/".$_FILES['fileToUpload']['name'];

				if (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
					$error = 'No file was uploaded..';
				}

		}		
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";
		echo "}";

	}

	function about() {
		// about 화면
		$cssFile = "about";
		$this->load->view("portfolio/header_v", array("cssFile"=>$cssFile));
		$this->load->view("portfolio/about_v");
		$this->load->view("portfolio/footer_v");
	}
}