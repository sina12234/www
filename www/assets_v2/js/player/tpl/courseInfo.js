<div class="curtain-item tac cWhite" style="display:none">
	<div class="curtain-item-content">
	    <div>
	    	{ if tryEnd }
	        <p class="fs18 mb20">试听时间到了，报名后可观看完整视频</p>
	        { /if }
	        <p class="curtain-courseInfo-title fs16 mb20">课程信息</p>
	        { if (userStatus.className && userStatus.className != planInfo.class_name && !btnMemberInfo.showBtnEnroll) }
	        <p class="o-title fs16">您已报名“{ userStatus.className }”，如需报名{ planInfo.class_name }请联系机构调班</p>
	        { /if }
	        <p class="curtain-title fs16 mb10">{ courseName }</p>
	        <p class="fs12 mb10">班级：{ className }</p>
	        <p class="fs12 mb10">主讲老师：{ teacherName }</p>
	        <p class="fs12 mb20">价格：
	        	{ if !hasPrice }
					免费
	        	{ else }
	        		<span class="cYellow">￥{ price }</span>
	        	{ /if}
	        	{ if hasMemberInfo }
					<div class="curtain-vip">
						会员课程
						<div class="vip-info">
							<i class="icon icon-crown"></i>
							{ each memberInfo as v }
								<p setId="{ v.setId }">
									{ v.title }<a href="javascript:void(0)" data-status="{ v.status }" class="cYellow open-vip">{ v.text }</a>
								</p>
							{ /each }
						</div>
					</div>
	        	{ /if }
	        </div>
	        { if !isSign || !isLogin }
	        <P class="pt20 pb20">
	        { if !isSign }
	        	<a href="javascript:void(0)" class="btn-sign button-md bgc-yellow cWhite cWhite-hover pointer" style="font-size:16px;padding:10px 20px;">立即报名</a>
	        { /if }
	        { if !isLogin }
				<a href="/site.main/register" class="cYellow pointer" style="font-size:16px;padding:10px 20px;">注册</a>
	        { /if }
	        </P>
	        { /if }
	    </div>
	    { if btnMemberInfo.showDivInvalidMember }
	    <div class="curtain-top">
	    	<i class="icon icon-bang-red"></i>
	    	{ if userStatus }
	    		会员已失效，继续学习请<a href="/member.list" class="cYellow" id="re-vip">重新开通</a> 会员 <a href="javascript:void(0)" class="curtain-top-close"><i class="icon icon-close16x16"><i></a>
	    	{ else }
	    		会员已停用，继续学习请<span class="cYellow btn-sign">重新报名课程</span><a href="javascript:void(0)" class="curtain-top-close"><i class="icon icon-close16x16"><i></a>
	    	{ /if }
	    </div>
	    { /if }
	    { if courseMemberInfo }
	        <a class="btn-md border-yellow cYellow hidden-md hidden-lg" href="/member.list" target="_blank">{ if userStatus.isExpire }续费会员{ else }开通会员{ /if }</a>
	    { /if }
	</div>
</div>