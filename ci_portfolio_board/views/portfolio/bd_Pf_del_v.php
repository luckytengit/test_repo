		<!-- 게시판 삭제 페이지-->
		<script>
		$(function(){
			$('.add_btn .del').click(function(){
				if (chk_input()==0 ) {
					$("form[name=boardAdd_frm]").attr("action","/portfolio/main/shboard/Pf/process_del").submit();
				}
			});

			$('.add_btn .cancel').click(function(){
				history.back();
			});
		});
		
		function chk_input(){
			var inputBox = [
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
					[게시글 삭제]
				</li>
				<li class="add_title">
					제목 : <?=$vs[0]->subject?>
				</li>

				<li class="add_txt">
					비빌번호
				</li>
				<li class="add_input">
					<input type="password" name="pwd" value="" />
				</li>

				<li class="add_btn">
					<span class="spanBtn del">삭제</span>
					<span class="spanBtn cancel">취소</span>
				</li>
			</ul>
		</section>