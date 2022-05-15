<?php
// 인젝션
$_POST = mysqli_r_escape_string($link, $_POST);

// post
$toSub		= $_REQUEST['main'];
$page_sub	= $_REQUEST['sub'];
$page		= $_REQUEST['page'];
$pagep		= $_REQUEST['pagep'];

$no			= $_POST['no'];
$title		= $_POST['title'];
$content	= $_POST['content'];
$writer		= $_POST['writer'];
$date		= isset($_POST['date'])? $_POST['date']:"";
$del_img	= isset($_POST['del_img'])? $_POST['del_img']:"";
$del_img2	= isset($_POST['del_img2'])? $_POST['del_img2']:"";
$del_img3	= isset($_POST['del_img3'])? $_POST['del_img3']:"";
$del_img4	= isset($_POST['del_img4'])? $_POST['del_img4']:"";
	//보도자료, 수상내역
$category	= isset($_POST['category'])? $_POST['category']:"";
$type		= isset($_POST['type'])? $_POST['type']:"";
	//광고게시판
$mov_code	= isset($_POST['mov_code'])? $_POST['mov_code']:"";
	//이벤트게시판
$cssjs	= isset($_POST['cssjs'])? $_POST['cssjs']:"";
$s_date	= isset($_POST['s_date'])? $_POST['s_date']:"";
$e_date	= isset($_POST['e_date'])? $_POST['e_date']:"";
	// 고객 인터뷰
$before_kg	= isset($_POST['before_kg'])? $_POST['before_kg']:"";
$after_kg	= isset($_POST['after_kg'])? $_POST['after_kg']:"";

// 검사
if (!$title || !$content) {
	no_alert("오류! 빈 값이 있습니다.");
	exit;
}

// 디렉토리 결정
switch ($page_sub) {
	case "dt_news_release":
		$imgpath = "dt_news_release";
		break;
	case "dt_wexperience":
		$imgpath = "dt_wexperience";
		break;
	case "dt_event_list":
		$imgpath = "dt_event_list";
		break;
	case "dt_notice":
		$imgpath = "dt_notice";
		break;
	case "dt_succ_story":
		$imgpath = "dt_succ_story";
		break;
	case "dt_custom_interview":
		$imgpath = "dt_custom_interview";
		break;
	case "dt_gallery_bd":
		$imgpath = "dt_gallery_bd";
		break;

	default:
		$imgpath = $page_sub;
		break;
}

// 파일 삭제
if ($no) {
	$qry = "select * from ".$page_sub."
			where no=".$no;
	$ps_re = mysqli_query($link, $qry);
	$ps_rw = mysqli_fetch_assoc($ps_re);
}

for ($j=1;$j<=4; $j++) {
	if ($j == 1) $i = "";
	else $i = $j;

	if (${'del_img'.$i}=="Y") {

		if ($ps_rw['img'.$i]) {
			// 파일삭제
			$delfile = "./dn/".$imgpath."/".$ps_rw['img'.$i];
			@unlink($delfile);
			
			// db 이미지 삭제
			$qry2 = "update ".$page_sub." set
						img".$i." = ''
					where no=".$no;
			mysqli_query($link, $qry2);
		}

	}
}


// 파일 처리
$img_sql = "";
for ($j=1; $j<=4; $j++) {
	if ($j == 1) $i = "";
	else $i = $j;

	if($_FILES['img'.$i]['size'] >0) {
		if($_FILES['img'.$i]['error'] !== UPLOAD_ERR_OK) {
			no_alert("업로드 중 에러가 발생했습니다-".$_FILES['img'.$i]['error']);
			exit;
		}

		$Extension_One = substr(strrchr($_FILES['img'.$i]['name'], "."),1);
		if($Extension_One == "html" or $Extension_One == "php" or $Extension_One == "php3" or $Extension_One == "js"){
			no_alert("업로드 할 수 없는 확장자 입니다.");
			exit;
		}
		$input_filename= date("YmdHis")."_".$_FILES['img'.$i]['name'];

		$uploadfile = "./dn/".$imgpath."/".$input_filename;
		if (!move_uploaded_file($_FILES['img'.$i]['tmp_name'], $uploadfile)) {
				no_alert("파일 업로드 공격의 가능성이 있습니다!");
				exit;
		}

		if ($no && $ps_rw['img'.$i]) {
			// 이미지 교체시 이전 파일삭제
			$delfile = "../dn/".$imgpath."/".$ps_rw['img'.$i];
			@unlink($delfile);
		}

		$img_sql .= "img".$i." ='".$input_filename."',";
	}
}

$wh_sql = "";

// 보도자료일 경우
if ($category) {
	$wh_sql .= "category ='".$category."',";
}
if ($type) {
	$wh_sql .= "type ='".$type."',";
} 

// 광고게시판일 경우
if ($mov_code) {
	$wh_sql .= "mov_code='".$mov_code."',";
}
// 이벤트게시판일 경우
if ($cssjs) {
	$wh_sql .= "cssjs='".$cssjs."',";
}
if ($s_date) {
	$wh_sql .= "s_date='".$s_date."',";
}
if ($e_date) {
	$wh_sql .= "e_date='".$e_date."',";
}

if (!$date) {
	$date = date("Y-m-d H:i:s");
}

// 고객 인터뷰일 경우
if ($before_kg && $after_kg) {
	$cus_inv_sql .= "before_kg='".$before_kg."',";
	$cus_inv_sql .= "after_kg='".$after_kg."',";
}

if ($no) {	// 수정 로직
	$tmpType = "수정";
	$qry = "update ".$page_sub." set
				title		='".$title."',
				content		='".$content."',
				".$img_sql."
				".$wh_sql."
				date		='".$date."',
				writer		='".$writer."'
			where no=".$no;
	
} else {	// 등록 로직
	$tmpType = "등록";
	$qry = "insert into ".$page_sub." set
				title		='".$title."',
				content		='".$content."',
				".$img_sql."
				".$wh_sql."
				writer		='".$writer."',
				date		= '".$date."'";
}

$succ = mysqli_query($link, $qry);

if (!$no) {
	$no = mysqli_insert_id($link);
}

if (!$succ) {
	no_alert("오류! 등록 중 에러가 발생했습니다.");
	exit;
}

url_alert($tmpType."이 완료됐습니다.","./index.php?main=".$toSub."&sub=".$page_sub."&page=dt_board_view&no=".$no."&pagep=".$pagep);
?>