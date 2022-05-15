		<link rel="stylesheet" type="text/css" href="/static/lib/jquery-ui-1.11.1.custom/jquery-ui.css" />
		<script src="/static/lib/jquery-ui-1.11.1.custom/jquery-ui.min.js"></script>
		<script>
		//window.showModalDialog("http://www.naver.com", "modal", "width=1000,height=100"); 
		$(function(){

			$("#sampleDialog").dialog({
                autoOpen:true, //자동으로 열리지않게
                width:400, //x,y  값을 지정
                //"center", "left", "right", "top", "bottom"
                modal:true, //모달대화상자
                resizable:false, //크기 조절 못하게
                
                buttons:{
					"확인":function(){
                        $(this).dialog("close");
                    },"취소":function(){
                        $(this).dialog("close");
                    }
                }
            });
			
			$("#on").click(function(){
                $("#sampleDialog").dialog("open"); //다이얼로그창 오픈                
            });

		});
		</script>

		<section><span id="on">반갑습니다..</span>
			<div id="sampleDialog" title="다이로그제목">
			김상호의 포트폴리오 사이트입니다.<p></p>
			<p>본 페이지는 Codeigniter 프레임워크로 만들어졌습니다.</p>
			<p>방문해주셔서 감사합니다.</p>
			</div>
		</section>