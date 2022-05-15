		<!-- 게시판 목록 뷰 -->
		<section>
			<?php foreach ($vs as $v) {?>
			<div class="boardList">
				<img src="/static/user/pf<?=$v->no?>.JPG" alt="포트폴리오사진"/>
				<div>
					<p>작업명 : 
						<a href="/portfolio/main/shboard/Pf/detail?no=<?=$v->no?>">
							<?=$v->subject?>
						</a>
					</P>
					<p>
						<?=nl2br($v->sub_subject)?>
					</p>
				</div>
			</div>
			<?php }?>

			<div class="boardList1">
				<div>
					<a href="/portfolio/main/shboard/Pf/add">
						<span class="spanBtn">프로젝트 추가</span>
					</a>
				</div>
			</div>
		</section>