		<!-- 게시판 글 수정란 -->
		<script src="/static/lib/ckeditor/ckeditor.js"></script>
		<script src="/static/lib/AjaxFileUploader/ajaxfileupload.js"></script>
		<script>
		$(function(){
			$('.add_btn .modify').click(function(){
				if (chk_input()==0 ) {
					$("form[name=boardAdd_frm]").attr("action","/portfolio/main/shboard/Free/process_modify").submit();
				}
			});

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
			
				//if (filename == "") {
				//	return false;
				//}

				$.ajax({ 
					type: "post", 
					url: "/aj/viewImgDel.php",
					data: {filename:filename, boardName:'Free', bno:'<?=$vs[0]->no?>', mode:"viewImgDel"}, 
					dataType: "text",
					success: function(data){
						if (data) {
							// 이미지 영역 삭제 처리
							$(".imgBox").eq(indexImg).remove();
							$(".imgDel").eq(indexImg).remove();
							$(".upFile").eq(indexImg).remove();
							alert(filename + " : 삭제처리됐습니다.");
						}
						
					}
				})
			});
		});
		
		function chk_input(){
			var inputBox = [
				{"type":"input",			"name":"subject",			"text":"제목"},
				{"type":"input",			"name":"pwd",				"text":"비빌번호"}
			];

			var i=0;
			$.each(inputBox, function(index, itm){
				if(itm.type=="input:radio"){
					if(!$(itm.type + "[name="+itm.name+"]:checked").val()){
						alert(itm.text + "을 입력/선택 해주세요!");
						$(itm.type + "[name=" + itm.name + "]").focus();
						i++;
						return false;
					}
				}else if($(itm.type + "[name=" + itm.name + "]").val()==''){
						alert(itm.text + "을 입력/선택 해주세요!");
						$(itm.type + "[name=" + itm.name + "]").focus();
						i++;
						return false;
				}
			});
			return i;
		}

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
		</script>
		<section>
			<form method="post" name="boardAdd_frm">
			<input type="hidden" name="no" value="<?=$vs[0]->no?>" />
			<ul>
				<li class="add_title">
					[프로젝트 수정]
				</li>

				<li class="add_txt">
					제목
				</li>
				<li class="add_input">
					<input type="text" name="subject" value="<?=$vs[0]->subject?>" />
				</li>

				<!-- li class="add_txt">
					sub 제목
				</li>
				<li class="add_input">
					<textarea name="subSubject" ><?=$vs[0]->sub_subject?></textarea>
				</li -->

				<li class="add_txt">
					본문
				</li>
				<li class="add_input">
					<textarea name="content" value="" ><?=$vs[0]->content?></textarea>
				</li>

				<li class="add_txt">
					첨부파일
				</li>
				<li class="add_input">
					<input type="file" name="fileToUpload" id="fileToUpload" />
					<span id="uploadBtn" class="spanBtn">업로드</span>
					<div id="imgView">
						<?php 
						if (is_array($vs[0]->files)) {
						foreach ($vs[0]->files as $val) { 
							if ($val) {?>
							<img src='/static/user/board_files/<?=$val?>' class='imgBox' alt='업로드보기'/><span class='imgDel' filename='<?=$val?>'>삭제</span>
							<input type='hidden' name='uploadedFile[]' class='upFile' value='<?=$val?>' />
						<?php
							}
						}
						}
						?>
					</div>
				</li>

				<li class="add_txt">
					순서 정렬
				</li>
				<li class="add_input">
					<input type="text" name="sort" value="<?=$vs[0]->sort?>" />
				</li>

				<li class="add_txt">
					비밀번호
				</li>
				<li class="add_input">
					<input type="password" name="pwd" value="" />
				</li>

				<li class="add_btn">
					<span class="spanBtn modify">수정</span>
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