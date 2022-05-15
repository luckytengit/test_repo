		<!-- 게시판 글 수정란 -->
		<script src="/static/lib/ckeditor/ckeditor.js"></script>
		<script>
		$(function(){
			$('.add_btn .modify').click(function(){
				if (chk_input()==0 ) {
					$("form[name=boardAdd_frm]").attr("action","/portfolio/main/shboard/Pf/process_modify").submit();
				}
			});

			$('.add_btn .cancel').click(function(){
				$(location).attr("href", "/portfolio/main/shboard/Pf/list");
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

				<li class="add_txt">
					sub 제목
				</li>
				<li class="add_input">
					<textarea name="subSubject" ><?=$vs[0]->sub_subject?></textarea>
				</li>

				<li class="add_txt">
					본문
				</li>
				<li class="add_input">
					<textarea name="content" value="" ><?=$vs[0]->content?></textarea>
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
				filebrowserUploadUrl: '/index.php/topic/upload_receive_from_ck'
			});
			</script>
		</section>