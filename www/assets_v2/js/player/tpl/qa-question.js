<div class="qa-title fs14 pb20">
	<span class="fs16">【{if qType == 2 || qType == 11 }多选{else if qType == 12}判断{else if qType == 13}填空{else}单选{/if}】</span>
	<span>{if qType != 13}请选择你的答案{else}请在下方输入你的答案{/if}</span>
</div>
{if qType == 2 || qType == 11 }
<ul class="qa-answers fs16 clearfix pb20 qa-select">
	<li class="qa-answers-item"><input id="qa-select1" type="checkbox" value="" name="qa-select" style="display:none"><label for="qa-select1"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>A</label></li>
	<li class="qa-answers-item"><input id="qa-select2" type="checkbox" value="" name="qa-select" style="display:none"><label for="qa-select2"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>B</label></li>
	<li class="qa-answers-item"><input id="qa-select3" type="checkbox" value="" name="qa-select" style="display:none"><label for="qa-select3"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>C</label></li>
	<li class="qa-answers-item"><input id="qa-select4" type="checkbox" value="" name="qa-select" style="display:none"><label for="qa-select4"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>D</label></li>
</ul>
{else if qType == 12}
<ul class="qa-answers fs16 clearfix pb20 qa-judge">
	<li class="qa-answers-item">
		<input id="qa-radio-right" type="radio" value="" name="qa-judeg" style="display:none">
		<label for="qa-radio-right">
			<i class="icon icon-qa-right-gray icon-normal"></i><i class="icon icon-qa-right icon-active"></i><br>
			<i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>
		</label>
	</li>
	<li class="qa-answers-item">
		<input id="qa-radio-error" type="radio" value="" name="qa-judeg" style="display:none">
		<label for="qa-radio-error">
			<i class="icon icon-qa-error-gray icon-normal"></i><i class="icon icon-qa-error icon-active"></i><br>
			<i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>
		</label>
	</li>
</ul>
{else if qType == 13}
<div class="qa-answers fs14 pb20 qa-input">
	<input type="text" value="" placeholder="请输入答案" class="qa-edit-item">
</div>
{else}
<ul class="qa-answers fs16 clearfix pb20 qa-select">
	<li class="qa-answers-item"><input id="qa-select1" type="radio" value="" name="qa-select" style="display:none"><label for="qa-select1"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>A</label></li>
	<li class="qa-answers-item"><input id="qa-select2" type="radio" value="" name="qa-select" style="display:none"><label for="qa-select2"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>B</label></li>
	<li class="qa-answers-item"><input id="qa-select3" type="radio" value="" name="qa-select" style="display:none"><label for="qa-select3"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>C</label></li>
	<li class="qa-answers-item"><input id="qa-select4" type="radio" value="" name="qa-select" style="display:none"><label for="qa-select4"><i class="icon icon-qa-selector icon-normal"></i><i class="icon icon-qa-selected icon-active"></i>D</label></li>
</ul>
{/if}
<div class="qa-btn-box tac">
	<button class="button-md qa-submit-btn fs16" id="qaSubmitBtn">提交答案</button>
</div>