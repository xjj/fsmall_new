<tr class="children-<{$itemdata.parent_id}> <{if $layer eq 3}>children-<{$parent_id}><{/if}>">
	<td>
		<{if $layer eq 1}>
			<span class="Tab" data-catid="<{$itemdata.cat_id}>">TAB</span>
			<span class="lpad"><{$itemdata.cat_name}></span>
		<{elseif $layer eq 2}>
			<span class="rpad gray"><{if $isend}>└───<{else}>├───<{/if}></span>
			<span class="Tab" data-catid="<{$itemdata.cat_id}>">TAB</span>
			<span class="lpad"><{$itemdata.cat_name}></span>
		<{else}>
			<span class="gray" style="padding-left:45px"><{if $isend}>└───<{else}>├───<{/if}></span>
			<span class="lpad"><{$itemdata.cat_name}></span>
		<{/if}>
	</td>
	<td><{$itemdata.cat_id}></td>
	<td><{if $itemdata.tb_cat_id gt 0}><{$itemdata.tb_cat_id}><{/if}></td>
	<td><{$itemdata.tb_cat_name}></td>
	<td><{if $itemdata.weight eq 0}>-<{else}><{$itemdata.weight}><{/if}></td>
	<td><{$itemdata.displayorder}></td>
	<td><{if $itemdata.status eq 1}>
			<a href="/<{$mod}>/<{$col}>/hide/<{$itemdata.cat_id}>" class="green">是</a>
		<{else}>
			<a href="/<{$mod}>/<{$col}>/show/<{$itemdata.cat_id}>" class="red">否</a>
		<{/if}></td>
	<td>
		<a href="/<{$mod}>/<{$col}>/edit/<{$itemdata.cat_id}>">编辑</a>
		<span class="dl">|</span>
		<a href="javascript:;" data-href="/<{$mod}>/<{$col}>/del/<{$itemdata.cat_id}>" class="btn-del">删除</a>
	</td>
</tr>