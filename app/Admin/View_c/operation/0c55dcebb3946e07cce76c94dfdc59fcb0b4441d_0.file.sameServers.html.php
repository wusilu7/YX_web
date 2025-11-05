<?php
/* Smarty version 3.1.30, created on 2024-01-18 19:46:37
  from "/lnmp/www/app/Admin/View/operation/sameServers.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65a90f9db5a9d2_17410802',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0c55dcebb3946e07cce76c94dfdc59fcb0b4441d' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/sameServers.html',
      1 => 1678771401,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65a90f9db5a9d2_17410802 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	<style type="text/css">
		.col-sm-1 {
	        width: 90px;
	        padding-top: 8px;
	    }
	    .col-sm-12 {
	        width: 9%;
	        padding-top: 8px;
	    }
	    .alert-info{
	        color: white;
	    }
	    .form-group{
	        margin-bottom: 35px;
	    }
	    
	    .text_content{
	        margin-left: 35px;
	    }
	    #group{
	        width: 100%
	    }
		.same{
			margin: auto;
		    width: 150px;
		    top: 20%;
		    position: inherit;
		    font-weight: 600
		}
		#server1,#server2{
			width: 300px;
			display: inline-block;
			position: relative;
			border: 1px solid #337ab7;
			text-align: left;
			float:left;
			margin-left: 10px;
			height: 600px;
			overflow: auto;
		}
		label{
			cursor:pointer;
		}
		#server1 input,
		#server2 input{
			margin-left: 20px
		}
		#div_do{
			text-align: center;
			display:none;
			width: 1000px;
			margin: auto;
		}
		#jin_do{
			float: left;
    		margin-left: -70px;
		}
		.same_si{
			font-weight: 600;
		    font-size: 20px;
		    margin-top: 25px;
		    margin-left: 15px;
		    margin-bottom: 10px;
		}
		.server_div{
			width:300px;
			float:left
		}
		.server_right{
			text-align:right;
			margin-right:90px
		}
		#diff_content{
			text-align:center; 
			font-weight: 600;
			margin-top: 50px;
		}
	</style>

	<div class="jin-content-title"><span>同步指定服配置</span></div>
	<div class="alert alert-info">
	    <div id="group_server_12"> </div>
	</div>

	<div id="div_do">
		<div class="server_div">
			<div class="server_right">
				<button id="check_val1_all" type="button" class="btn btn-primary">全选</button>
				<button id="check_val1_noall" class="btn btn-primary">全不选</button>
			</div>
			<div id="server1"></div>
		</div>
		
		<div class="server_div">
			<div class="server_right">
				<button id="check_val2_all" type="button" class="btn btn-primary">全选</button>
				<button id="check_val2_noall" class="btn btn-primary">全不选</button>
			</div>
			<div id="server2"></div>
		</div>
		<a id="jin_do" class="btn btn-warning">同步</a>
		<a id="jin_do1" style="float: left;" class="btn btn-warning">合服</a>
		<a id="jin_do2" style="float: left; margin-left: 20px;" class="btn btn-warning">新增</a>
	</div>
	
	<div id="diff_content"></div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect6('#g1', '#g');
	
	var data = {}
	$("#jin_search").click(function () {
        data.g1 = $('#g1').val();
        data.g2 = $('#g').val();

        if (data.g1 && data.g2) {
            $.cookie('cookie_g', $(g).val(), {expires: 30});
            $.ajax({
                type: "POST",
                url: "/?p=Admin&c=Operation&a=server&jinIf=9140",
                data: data,
                dataType: "json",
                success: function (json) {
                	layui.use('form', function(){
				      	var form = layui.form;
				      	form.render(); 
				    });

                	var c1 = '<div class="same_si">被同步的服</div><ul>';
                	for(var i in json.res1){				　　
						c1 += '<li><label><input type="checkbox" name="check_val1" value="'+json.res1[i]['server_id']+'">&nbsp;&nbsp;'+json.res1[i]['name']+'<label></li>';	
	                }
	                c1 += '</ul>';
	                $('#server1').html(c1);

	                var c2 = '<div class="same_si">同步到的服</div><ul>';
                	for(var i in json.res2){				　　
						c2 += '<li><label><input type="checkbox" name="check_val2" value="'+json.res2[i]['server_id']+'">&nbsp;&nbsp;'+json.res2[i]['name']+'<label></li>';
	                }
	                c2 += '</ul>';
	                $('#server2').html(c2);

	                $('#div_do').show();
	            }
            }); 
        } else {
        	layer.alert('请选择渠道', {icon: 2});
        }
    });
	
	$("#jin_do").click(function () {
		obj1 = document.getElementsByName("check_val1");
		check_val1 = [];
	    for(k in obj1){
	        if(obj1[k].checked)
	            check_val1.push(obj1[k].value);
	    }
        data.s1 = check_val1;
        
        obj2 = document.getElementsByName("check_val2");
		check_val2 = [];
	    for(k in obj2){
	        if(obj2[k].checked)
	            check_val2.push(obj2[k].value);
	    }
        data.s2 = JSON.stringify(check_val2);

    	layer.open({
            type: 1,
            closeBtn: 2,
            title: '同步指定服配置',
            area: ['300px', '200px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="same">确定同步吗？(服务器名字相同的一一对应同步)</div>',
            yes: function (index) {
            	layer.close(index);
            	
                $.ajax({
                    type: "POST",
                    url: "/?p=Admin&c=Operation&a=server&jinIf=9139",
                    data: data,
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if (json != 2) {
                        	layer.alert('同步成功', {icon: 1});
                        } else {
                        	layer.alert('同步失败', {icon: 2});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    });

    $("#jin_do1").click(function () {
        obj1 = document.getElementsByName("check_val1");
        check_val1 = [];
        for(k in obj1){
            if(obj1[k].checked)
                check_val1.push(obj1[k].value);
        }
        if(check_val1.length!=1){
            layer.alert('左边请选择一个服务器', {icon: 2});
            return false;
		}
        data.s1 = check_val1;

        obj2 = document.getElementsByName("check_val2");
        check_val2 = [];
        for(k in obj2){
            if(obj2[k].checked)
                check_val2.push(obj2[k].value);
        }
        data.s2 = JSON.stringify(check_val2);

        layer.open({
            type: 1,
            closeBtn: 2,
            title: '同步指定服配置',
            area: ['300px', '200px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="same">确定同步吗？(左边为基准,同步到右边所有选中的)</div>',
            yes: function (index) {
                layer.close(index);

                $.ajax({
                    type: "POST",
                    url: "/?p=Admin&c=Operation&a=server&jinIf=9144",
                    data: data,
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if (json != 2) {
                            layer.alert('同步成功', {icon: 1});
                        } else {
                            layer.alert('同步失败', {icon: 2});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    });

    $("#jin_do2").click(function () {
        obj1 = document.getElementsByName("check_val1");
        check_val1 = [];
        for(k in obj1){
            if(obj1[k].checked)
                check_val1.push(obj1[k].value);
        }
        data.s1 = check_val1;
        data.g2 = $('#g').val();



        layer.open({
            type: 1,
            closeBtn: 2,
            title: '新增指定服配置',
            area: ['300px', '200px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="same">确定新增吗？</div>',
            yes: function (index) {
                layer.close(index);

                $.ajax({
                    type: "POST",
                    url: "/?p=Admin&c=Operation&a=server&jinIf=9141",
                    data: data,
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if (json != 2) {
                            layer.alert('新增成功', {icon: 1});
                        } else {
                            layer.alert('新增失败', {icon: 2});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    });

    $("#check_val1_all").click(function(){ 
		$("input[name=check_val1]").prop("checked", true); 
	}) 
	$("#check_val1_noall").click(function(){ 
		$("input[name=check_val1]").prop("checked", false);
	}) 
	$("#check_val2_all").click(function(){ 
		$("input[name=check_val2]").prop("checked", true); 
	}) 
	$("#check_val2_noall").click(function(){ 
		$("input[name=check_val2]").prop("checked", false);
	}) 
<?php echo '</script'; ?>
>
<?php }
}
