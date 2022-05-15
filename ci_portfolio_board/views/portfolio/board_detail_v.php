		<!-- 게시판 상세보기란 -->
		<script>
		$(function(){
			$('.add_btn .del').click(function(){
				$(location).attr("href", "/portfolio/main/shboard/Pf/del?no=<?=$vs[0]->no?>");
			});

			$('.add_btn .modify').click(function(){
				$(location).attr("href", "/portfolio/main/shboard/Pf/modify?no=<?=$vs[0]->no?>");
			});

			$('.add_btn .toList').click(function(){
				$(location).attr("href", "/portfolio/main/shboard/Pf/list");
			});
		});
		</script>
		<section>
			<ul>
				<li class="add_title">
					[프로젝트 상세보기]
				</li>

				<li class="add_txt">
					번호
				</li>
				<li class="add_input">
					<?=$vs[0]->no?>
				</li>

				<li class="add_txt">
					제목
				</li>
				<li class="add_input">
					<?=$vs[0]->subject?>
				</li>

				<li class="add_txt">
					sub 제목
				</li>
				<li class="add_input">
					<?=nl2br($vs[0]->sub_subject)?>
				</li>

				<li class="add_txt">
					본문
				</li>
				<li class="add_input">
					<?=$vs[0]->content?>
				</li>

				<li class="add_btn">
					<span class="spanBtn toList">리스트</span>
					<span class="spanBtn modify">수정</span>
					<span class="spanBtn del">삭제</span>
				</li>
			</ul>
		</section>