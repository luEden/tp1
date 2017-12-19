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
            <th width="10%">
                <input type="button" id="selectAll" value="全选">
                <input type="button" id="selectOther" value="反选">
                <input type="button" id="dels" value="删除">
            </th>
            <th>类型名称</th>
            <th>操作</th>
        </tr>
        <?php if(is_array($data["list"])): $i = 0; $__LIST__ = $data["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td align="center"><input type="checkbox" name="ids" value="<?php echo ($vo["id"]); ?>"></td>
            <td align="center" class="first-cell"><?php echo ($vo["type_name"]); ?></td>
            
            <td align="center">
            <a href="<?php echo U('edit','type_id='.$vo['id']);?>" title="编辑"><img src="/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
            <a href="<?php echo U('dels','type_id='.$vo['id']);?>" title="回收站"><img src="/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
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

    <script type="text/javascript">
        // 实现全选
        $('#selectAll').click(function(){
            $('input[name="ids"]').prop('checked',true);
        });

        // 实现反选
        $('#selectOther').click(function(){
            $('input[name="ids"]').each(function(){
                var value = $(this).prop('checked');
                if(value){
                    $(this).prop('checked',false);
                }else{
                    $(this).prop('checked',true)
                }
            });
        });

        // 删除触发ajax
        $('#dels').click(function(){
            // 获取到目前已经被选中的值
            var ids = []; 
            $('input[name="ids"]').each(function(){
                var value = $(this).prop('checked');
                if(value){
                    ids.push($(this).val());
                }
            });
            if(ids.length<=0){
                return;
            }
            $.ajax({
                url:'<?php echo U("dels");?>',
                data:{type_id:ids},
                type:'post',
                success:function(res){
                    location.href='<?php echo U("index");?>';
                }
            });
        });
    </script>