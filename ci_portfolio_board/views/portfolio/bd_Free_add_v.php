		<!-- 게시판 글 추가란 -->
		<script src="/static/lib/ckeditor/ckeditor.js"></script>
		<script src="/static/lib/AjaxFileUploader/ajaxfileupload.js"></script>
		<script src="/static/lib/KevinSheedy-jquery.alphanum/jquery.alphanum.js"></script>
		<script>
		$(function(){
			$('.add_btn .regist').click(function(){
				// 길이 체크
				if ($('input[name=subject]').shValid(2,0,false,"제목") &&
					$('input[name=pwd]').shValid(4,0,false,"비밀번호") ) 
				{
					$("form[name=boardAdd_frm]").attr("action","/portfolio/main/shboard/Free/process_add").submit();
				}

			});

			//$('input[name=subject]').keyup(function() {
			//		$("#subject").shNumber();
			//});

			$('.add_btn .cancel').click(function(){
				$(location).attr("href", "/portfolio/main/shboard/Free/list");
			});
			
			// 파일처리
			$('#uploadBtn').click(function(){
				var imgBoxCnt = $(".imgBox").length;

				if (imgBoxCnt > 4) {
					alert("이미지는 4개까지 가능합니다.");
					return;
				}

				return ajaxFileUpload();
			});
			$('#imgView').delegate(".imgDel","click",function(){
				var filename = $(this).attr("filename");
				var indexImg = $(".imgDel").index(this);
			
				if (filename == "") {
					return false;
				}

				$.ajax({ 
					type: "post", 
					url: "/aj/viewImgDel.php",
					data: {filename:filename, mode:"viewImgDel"}, 
					dataType: "text",
					success: function(data){ 
						// 이미지 영역 삭제 처리
						$(".imgBox").eq(indexImg).remove();
						$(".imgDel").eq(indexImg).remove();
						$(".upFile").eq(indexImg).remove();
						alert(filename + " : 삭제처리됐습니다.");
					}
				})
			});
		});
		
		function ajaxFileUpload() {
			$("#loading")
			.ajaxStart(function(){
				$(this).show();
			})
			.ajaxComplete(function(){
				$(this).hide();
			});

			$.ajaxFileUpload
			(
				{
					url:'/aj/my_doAjaxfileupload.php',
					secureuri:false,
					fileElementId:'fileToUpload',
					dataType: 'json',
					data:{boardName:'Free'},
					success: function (data, status)
					{
						if(typeof(data.error) != 'undefined')
						{
							if(data.error != '')
							{
								alert(data.error);
							}else
							{
								data.msg = decodeURIComponent(data.msg);
								alert(data.msg+" : 업로드 완료됐습니다");
								var imgFile = "/static/user/board_files/"+data.msg;

								// 이미지 박스 뷰 생성
								var imgStr = "<img src='"+imgFile+"' class='imgBox' alt='업로드보기'/><span class='imgDel' filename='"+data.msg+"'>삭제</span>";

								// 업로드 파일명 input박스 생성
								imgStr += "<input type='hidden' name='uploadedFile[]' class='upFile' value='"+data.msg+"' />";

								$("#imgView").append(imgStr);

								

							}
						}
					},
					error: function (data, status, e)
					{
						alert(e);
					}
				}
			)
			
			return false;

		}
		
		/*
		 * 한글 Byte 체크
		 * is_byte=true : 한글 2byte.
		 */
		$.fn.shStrCnt = function(is_byte) {
			var str = $(this).val();
			var cnt = 0;
			
			if (is_byte==false) {
				cnt = str.length;
			} else {
				for (var i=0; i<str.length; i++) {
					cnt += (str.charCodeAt(i) > 128) ? 2: 1;
				}
			}
			return cnt;
		}
		
		/*
		 * 폼 길이 체크
		 * maxLen = 0 : 최대 길이 제한 없음
		 * use : if ($('input[name=subject]').shValid(2,0,false,"제목") {submit()}
		 */
		$.fn.shValid = function(minLen, maxLen, is_byte, msg) {
			var cnt = $(this).shStrCnt(is_byte);	// 글자수 리턴
			
			if (maxLen==0) {
				if (minLen > cnt) {
					alert(msg+" 길이는 "+minLen+"자 이상이여야합니다.");
					$(this).focus();
					return false;
				}
			} else {
				if (minLen > cnt || cnt > maxLen) {
					alert(msg+" 길이는 "+minLen+"~"+maxLen+"자 사이여야합니다.");
					$(this).focus();
					return false;
				}
			}
			return true;
		}
		
		/*
		 * text박스에 숫자만 입력되게
		 * use : keyup funciton 에 사용
		 */
		$.fn.shNumber = function() {
			var str = $(this).val();
			$(this).val(str.replace(/[^0-9]|/gi, "")); 
		}
		
		function dump(obj) {
			var info = '';
			for (var imsi in obj) {
				info += imsi + ' = ' + obj[imsi] + '\n';
			}
			alert(info);
		} 

		</script>
		<section>
			<form method="post" name="boardAdd_frm" enctype="multipart/form-data">
			<ul>
				<li class="add_title">
					[게시판 글 등록]
				</li>

				<li class="add_txt">
					제목
				</li>
				<li class="add_input">
					<input type="text" name="subject" id="subject" value="" />
				</li>

				<!-- li class="add_txt">
					sub 제목
				</li>
				<li class="add_input">
					<textarea name="subSubject" ></textarea>
				</li -->

				<li class="add_txt">
					본문
				</li>
				<li class="add_input">
					<textarea name="content"></textarea>
				</li>

				<li class="add_txt">
					첨부파일
				</li>
				<li class="add_input">
					<input type="file" name="fileToUpload" id="fileToUpload" />
					<span id="uploadBtn" class="spanBtn">업로드</span>
					<div id="imgView"> </div>
				</li>

				<li class="add_txt">
					비밀번호
				</li>
				<li class="add_input">
					<input type="password" name="pwd" value="" />
				</li>

				<li class="add_btn">
					<span class="spanBtn regist">등록</span>
					<span class="spanBtn cancel">취소</span>
				</li>
			</ul>
			</form>
			<script>
			// ckediter
			CKEDITOR.replace( 'content', {		// textarea name
				filebrowserUploadUrl: '/index.php/topic/upload_receive_from_ck',
				height:'350px'
			});
			</script>

			
		</section>