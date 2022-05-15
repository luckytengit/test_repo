<?php
class Board_m extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	
	function process_add($boardTable, $filename="") {
		// post
		$subject = $this->input->post('subject');
		$subSubject = $this->input->post('subSubject');
		$content = $this->input->post('content');
		$pwd = $this->input->post('pwd');
		$uploadedFile = $this->input->post('uploadedFile');

		if (!$subject || !$content || !$pwd) {
			return "blank";
		}

		// 파일명 추출
		$filenames = ($filename!="") ? $filename."/-/" : "";
		if (is_array($uploadedFile)) {
			foreach ($uploadedFile as $key=>$val) {
				$filenames .= $val."/-/";
			}
		}
		$filenames = substr($filenames,0,-3);

		$this->db->set('reg_date', 'NOW()', false);

		$this->db->insert($boardTable, array(
			'subject'=>$subject,
			'sub_subject'=>$subSubject,
			'content'=>$content,
			'pwd'=>$pwd,
			'wirter'=>'관리자',
			'filename'=>$filenames
		));

		$ret = $this->db->insert_id();
		
		$this->db->where('no', $ret);
		$this->db->update($boardTable, array(
			"sort"=>$ret
		));

		return $ret;
	}
	
	function lists($boardTable) {
		$this->db->order_by('sort desc');
		$qry = $this->db->get_where($boardTable, array('use_yn'=>"Y"));

		return $qry->result();
	}

	function detail($boardTable) {
		$no = $this->input->get('no');
		
		return $this->db->get_where($boardTable, array('use_yn'=>"Y","no"=>$no))->result();
	}

	function process_modify($boardTable, $filename='') {
		// post
		$no = $this->input->post('no');
		$subject = $this->input->post('subject');
		$subSubject = $this->input->post('subSubject');
		$content = $this->input->post('content');
		$pwd = $this->input->post('pwd');
		$sort = $this->input->post('sort');
		$uploadedFile = $this->input->post('uploadedFile');

		if (!$subject || !$content || !$pwd || !$sort) {
			return "blank";
		}

		// 파일명 추출
		$filenames = ($filename!="") ? $filename."/-/" : "";
		if (is_array($uploadedFile)) {
			foreach ($uploadedFile as $key=>$val) {
				$filenames .= $val."/-/";
			}
		}
		$filenames = substr($filenames,0,-3);

		$rows = $this->db->get_where($boardTable, array('no'=>$no, "pwd"=>$pwd))->result();
		if (!$rows[0]->no) {
			return "noMatchPwd";
		}

		$this->db->set('mod_date', 'NOW()', false);
		$this->db->where('no', $no);
		$this->db->update($boardTable, array(
			'subject'=>$subject,
			'sub_subject'=>$subSubject,
			'content'=>$content,
			'pwd'=>$pwd,
			'wirter'=>'관리자',
			'filename'=>$filenames
		));

		// 목록 sort
		$tmpSort = $rows[0]->sort;

		$row = $this->db->get_where($boardTable, array('sort'=>$sort))->result();
		$targetNO = $row[0]->no;

		if ($tmpSort != $sort) {
			// 목록 순서 바꿈
			$this->db->where('no', $no);
			$this->db->update($boardTable, array(
				'sort'=>$sort
			));

			// 타켓 sort의 no값
			$this->db->where('no', $targetNO);
			$this->db->update($boardTable, array(
				'sort'=>$tmpSort
			));
		}
		
		return $no;
	}

	function process_del($boardTable) {
		//get
		$no = $this->input->post('no');
		$pwd = $this->input->post('pwd');

		if (!$no || !$pwd) {
			return "blank";
		}

		$rows = $this->db->get_where($boardTable, array('no'=>$no, "pwd"=>$pwd))->result();
		if (!$rows) {
			return "noMatchPwd";
		}

		$this->db->where(array(
			'no'=>$no,
			'pwd'=>$pwd
		));
		$this->db->update($boardTable, array(
			'use_yn'=>'N'
		));

		return $no;
	}

}
