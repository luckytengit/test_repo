		<!-- 게시판 목록 뷰 -->
		<section>
			<table>
				<caption>게시판</caption>
				<thead>
				<tr>
					<td width="50">번호</td>
					<td width="597">제목</td>
					<td width="70">글쓴이</td>
					<td width="70">날짜</td>
				</tr>
				</thead>
				<tbody>
			<?php 
			if (is_array($vs)) {
				foreach ($vs as $v) {?>
			
				<tr>
					<td><?=$v->no?></td>
					<td class="title">
						<a href="/portfolio/main/shboard/Free/detail?no=<?=$v->no?>">
						<?=$v->subject?>
						</a>
					</td>
					<td><?=$v->wirter?></td>
					<td><?=substr($v->reg_date,0,10)?></td>
				</tr>
				
			<?php 
				}
			}?>
				</tbody>
			</table>

			<div class="btnFoot">
				<a href="/portfolio/main/shboard/Free/add">
					<span class="spanBtn">글추가</span>
				</a>
			</div>
		</section>