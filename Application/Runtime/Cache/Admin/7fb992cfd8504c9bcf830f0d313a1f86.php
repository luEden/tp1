<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品分类</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h1>
    <span class="action-span"><a href="#">商品分类</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加分类 </span>
    <div style="clear:both"></div>
</h1>

<div class="main-div">
    


<div class="list-div" id="listDiv">
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th>编号</th>
            <th>属性名称</th>
            <th>所属类型</th>
            <th>属性类型</th>
            <th>录入方式</th>
            <th>默认值</th>
            <th>操作</th>
        </tr>
        <?php if(is_array($data["list"])): $i = 0; $__LIST__ = $data["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td align="center"><?php echo ($vo["id"]); ?></td>
            <td align="center" class="first-cell"><?php echo ($vo["attr_name"]); ?></td>
            <td align="center" class="first-cell"><?php echo ($vo["type_name"]); ?></td>
            <td align="center"><?php if(($vo["attr_type"]) == "1"): ?>唯一属性<?php else: ?>单选属性<?php endif; ?></td>
            <td align="center"><?php if(($vo["attr_type"]) == "1"): ?>手工录入<?php else: ?>列表选择<?php endif; ?></td>
            <td align="center"><?php echo ($vo["attr_values"]); ?></td>
            
            <td align="center">
            
            <a href="<?php echo U('edit','attr_id='.$vo['id']);?>" title="编辑"><img src="/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
            <a href="<?php echo U('dels','attr_id='.$vo['id']);?>" title="回收站"><img src="/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <!-- 分页开始 -->
    <table id="page-table" cellspacing="0">
        <tr>
            <td width="80%">&nbsp;</td>
            <td align="center" nowrap="true"> 
                <?php echo ($data["pageStr"]); ?>
            </td>
        </tr>
    </table>
<!-- 分页结束 -->
</div>

</div>
<div id="footer">
共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>

</body>
</html>
<script type="text/javascript" src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>