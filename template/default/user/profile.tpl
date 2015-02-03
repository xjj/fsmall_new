<{include file="common/header.tpl"}>
<link type="text/css" rel="stylesheet" href="/images/<{$template}>/user.css" />

<!--面包屑导航-->
<div class="breadcrumb wrap">
	<a href="/">首页</a><span>&gt;</span><a href="/user/profile">个人资料</a>
</div>

<div class="wrap clearfix">
	<div class="sidebar">
		<{include file="user/navbar.tpl"}>
	</div>
	<div class="mainbox">
    	<div class="user-title">我的个人资料</div>
        <div class="user-main">
            <form name="f" method="post" action="/user/profile">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="user-tbl">
              <tr>
                <td width="80" class="ltd">用户名：</td>
                <td><input type="text" name="uname" class="txt" size="20" value="<{$user_data.uname}>" /></td>
              </tr>
              <tr>
                <td class="ltd">性别：</td>
                <td><input type="radio" name="sex" value="2" <{if $user_data.sex eq 2}>checked="checked"<{/if}> /><span class="lpad rpad">男</span>
                    <input type="radio" name="sex" value="1" <{if $user_data.sex eq 1}>checked="checked"<{/if}> /><span class="lpad rpad">女</span>
                </td>
              </tr>
              <tr>
                <td class="ltd">手机号码：</td>
                <td><input type="text" name="mobile" class="txt" size="20" value="<{$user_data.mobile}>" /></td>
              </tr>
              <tr>
                <td class="ltd">QQ：</td>
                <td><input type="text" name="qq" class="txt" size="20" value="<{$user_data.qq}>" /></td>
              </tr>
              <tr>
                <td class="ltd">出生日期：</td>
                <td><select name="birth_year">
                    <option value="0">-年-</option>
                    <{$now = $smarty.now|date_format:'Y' - 10}>
                    <{for $i=$now to 1940 step -1}>
                    <option value="<{$i}>" <{if $user_data.birth_year eq $i}>selected="selected"<{/if}>><{$i}></option>
                    <{/for}>
                    </select>
                    <select name="birth_month">
                    <option value="0">-月-</option>
                    <{for $i=1 to 12}>
                    <option value="<{$i}>" <{if $user_data.birth_month eq $i}>selected="selected"<{/if}>><{if $i lt 10}>0<{/if}><{$i}></option>
                    <{/for}>
                    </select>
                    <select name="birth_day">
                    <option value="0">-日-</option>
                    <{for $i=1 to 31}>
                    <option value="<{$i}>" <{if $user_data.birth_day eq $i}>selected="selected"<{/if}>><{if $i lt 10}>0<{/if}><{$i}></option>
                    <{/for}>
                    </select>
                </td>
              </tr>
              <tr>
                <td class="ltd">居住地：</td>
                <td>
                    <div id="sebox">
                    <select name="province_id">
                    <option value="0">-省/直辖市-</option>
                    <{if $province_items}>
                    <{foreach $province_items as $item}>
                    <option value="<{$item.region_id}>" <{if $user_data.province_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
                    <{/foreach}>
                    <{/if}>
                    </select>
                    <select name="city_id">
                    <option value="0">-城市-</option>
                    <{if $city_items}>
                    <{foreach $city_items as $item}>
                    <option value="<{$item.region_id}>" <{if $user_data.city_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
                    <{/foreach}>
                    <{/if}>
                    </select>
                    <select name="county_id">
                    <option value="0">-县区-</option>
                    <{if $county_items}>
                    <{foreach $county_items as $item}>
                    <option value="<{$item.region_id}>" <{if $user_data.county_id eq $item.region_id}>selected="selected"<{/if}>><{$item.region_name}></option>
                    <{/foreach}>
                    <{/if}>
                    </select>
                    </div>
                </td>
              </tr>
              
              <tr>
                <td class="ltd">详细地址：</td>
                <td><input type="text" name="address" class="txt" size="60" value="<{$user_data.address}>" /></td>
              </tr>
              <tr>
                <td class="ltd">个人简介：</td>
                <td><textarea name="about" class="txt" cols="60" rows="3"><{$user_data.about}></textarea></td>
              </tr>
              
              <tr>
                <td class="ltd"></td>
                <td><input type="submit" name="submit" class="btn2" value="提 交" /></td>
              </tr>
            </table>
            </form>
        </div>
	</div>
</div>

<script type="text/javascript" src="/js/region.js"></script>
<script type="text/javascript">
$(function(){
	tabLine();
	$('#sebox select').change(function(){
		var id  = $(this).val();
		var iem = $(this).next('select');
		var cem = iem.nextAll('select');
		var c = {'id' : id};
		if (iem.length > 0){
			c.iem = iem[0];
			if (cem.length > 0){c.cem = cem;}
			region.children(c);
		}
	});
});
</script>
<{include file="common/footer.tpl"}>