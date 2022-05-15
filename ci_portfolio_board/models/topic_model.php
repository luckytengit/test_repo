<?php
class Topic_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	public function gets() {
		return $this->db->query("select * from topic")->result();	// 객체로 리턴, result_array():배열로 리턴
	}

	public function get($topic_id) {
		//return $this->db->query("select * from topic whrere id=$topic_id")->result();
		return $this->db->get_where('topic', array('id'=>$topic_id))->result();	// 위와동일 (타db와 이식성 좋음)
	}

	function add($title, $description){
        $this->db->set('created', 'NOW()', false);	// false : 문자아닌형태로. db함수(now등)는 아래 처럼 포함하면 안됨
        $this->db->insert('topic',array(
            'title'=>$title,
            'description'=>$description
	    ));     
		echo $this->db->last_query();	// 위 구문 db sql문으로 보여줌

        return $this->db->insert_id();
    }

}
?>