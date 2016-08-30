<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Ipackage</title>
</head>
<link rel="shortcut icon" href="./favicon.ico">
<link rel="stylesheet" href="./static/css/bootstrap.min.css" />
<link rel="stylesheet" href="./static/css/dataTables.bootstrap4.css" />
<link rel="stylesheet" href="./static/css/common.css">
<body>
	<img src="./static/images/loading.gif" alt="loading" id="loading">
	<div class="content" >
		<table id="se">
			<tr>
				<td>项目根目录：</td>
				<td><input type="text" class="form-control" id="basic-url"    placeholder="请输入你要检索的路径"  value="<?php echo $src ?>"></td>
				<td><button type="button" class="btn btn-btn btn-success ml" id="search">确认</button></td>
    			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>选择日期：</td>
				<td><input type="datetime-local" class="form-control" id="gt" value="<?php echo date('Y-m-d',$gt)?>T<?php echo date('H:i:s',$gt)?>"></td>
			</tr>
		</table>
	<hr />
	<ol class="breadcrumb" style="margin-bottom: 5px;">
	请按右上方“确认”开始检索你的项目中某天过后你修改过的文件。
	</ol>
	<div class="alert alert-danger alert-dismissible fade in alertred" role="alert">
		<!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"> -->
		<!-- <span aria-hidden="true">&times;</span> -->
		<!-- <span class="sr-only">Close</span> -->
		<!-- </button> -->
		<strong>Notice:</strong> 请选择文件.
	</div>
	<div class="alert alert-danger alert-dismissible fade in alertred1" role="alert">
		<!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"> -->
		<!-- <span aria-hidden="true">&times;</span> -->
		<!-- <span class="sr-only">Close</span> -->
		<!-- </button> -->
		<strong>Notice:</strong> 你选择的文件夹不存在.
	</div>
	<hr />
		<form id="file">
			<table id="datatalbe"  class="table table-striped table-bordered" cellspacing="0" width="100%">
	     			<thead>
					<tr>
						<td width="50%">路径</td>
						<td width="30%">文件名</td>
						<td width="15%">修改日期</td>
						<td width="5%">全选<input type="checkbox" id="checkAll"></td>
					</tr>
	        			</thead>
				<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
			    </tbody>
			</table>
		</form>
	<hr />
		<table>
			<tr>
				<td>保存到：</td>
				<td><input type="text" class="form-control" id="outputDir" placeholder="请输入你要保存的路径" value="<?php echo $tar?>"></td>
				<td><button type="button" class="btn btn-btn btn-success ml"  id="saves">保存增量包</button></td>
				<td><button type="button" class="btn btn-btn btn-primary ml"  id="ftpup">上传到FTP</button></td>
				<td><button type="button" class="btn btn-btn  ml"  id="ftp">配置FTP</button></td>
			</tr>
		</table>
	<br>
		<div class="alert alert-result alert-info" role="alert">
			<strong>Result:</strong>
			<ul id="result">
			</ul>
		</div>
	</div>
</body>
</html>
<input type="hidden" id="dirurl" value="<?php echo $src ?>">
<script src="./static/js/jquery.js"></script>
<script src="./static/js/bootstrap.min.js"></script>
<script src="./static/js/jquery.dataTables.min.js"></script>
<script src="./static/js/dataTables.bootstrap4.min.js"></script>
<script>
	//调节样式
	$('#gt').height($('#basic-url').height());
</script>
<script>
$().ready(function(){
	var selectAll = false;
	var switchs = false;
	var table = $('#datatalbe').DataTable({
    	"bDestroy":true,
		"sServerMethod": "POST",
		language: {
		        "sProcessing": "处理中...",
		        "sLengthMenu": "显示 _MENU_ 项结果",
		        "sZeroRecords": "没有匹配结果",
		        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
		        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
		        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
		        "sInfoPostFix": "",
		        "sSearch": "搜索:",
		        "sUrl": "",
		        "sEmptyTable": "没有匹配结果",
		        "sLoadingRecords": "载入中...",
		        "sInfoThousands": ",",
		        "oPaginate": {
		            "sFirst": "首页",
		            "sPrevious": "上页",
		            "sNext": "下页",
		            "sLast": "末页"
		        },
		        "oAria": {
		            "sSortAscending": ": 以升序排列此列",
		            "sSortDescending": ": 以降序排列此列"
		        }
		    },
		
		"ajax": {
			"url": "./index.php/welcome/search",
			"data": function () {
				var src = $('#dirurl').val();
				return {src: src,search:Boolean(switchs),'gt':$('#gt').val()};
				},
			"dataSrc": function ( data ) {
				for (var i = data.length - 1; i >= 0; i--) {
					data[i].checkbox = '选择<input class="checkboxs" type="checkbox" value="'+data[i].path+data[i].filename+'"/>';
				}
				
				return data;
				}
		},
 		"aoColumnDefs": [ { "bSortable": false, "aTargets": [ 3 ] }],
		 "columns": [
		            { "data": "path" },
		            { "data": "filename" },
		            { "data": "filemtime" },
		            { "data": "checkbox" },
		        ],
	    });

	//修改项目路径
	





	//检索子文件夹
	$('#search').click(function(){
		$.post(
			'./index.php/welcome/show',
			{'show':'dir','src':$('#basic-url').val()},
			function(data){
				if(data != 0){
					var arr = $.parseJSON(data);
					var str = '可以选择你的子文件夹<br /><li class="change"><a href="javascript:void(0)" value="<?php echo $src ?>">全部</a></li>';
					for (var i = 0; i < arr.length; i++) {
						str += '<li class="change" ><a href="javascript:void(0)" value='+arr[i].path+'>'+arr[i].filename+'</a></li>';
					}
					$('.breadcrumb').html(str);
				}else{
					$('.alertred1').show(300);
				}
			}
			)
	})

	$('.breadcrumb').on('click','.change',function(){
		var me = this;
		$('.change span').each(function(){
			$(this).parent().html($(this).parent().html().replace('<span','<a').replace('</span','</a'));
		})
		$('.change').removeClass('active');
		$(me).addClass('active');
		$('#dirurl').val($(me).children('a').attr('value'));
		switchs = true;
		table.ajax.reload();
		$(me).html($(me).html().replace('<a','<span').replace('</a','</span'));
	})
	
	function select(){
		var tar = $('#outputDir').val();
		var obj = table.$('.checkboxs:checked');
		var str = '';
		if(obj.length <= 0){
			$('.alertred').show(300);
			return false;
		}
		for (var i = obj.length - 1; i >= 0; i--) {
			str += $(obj[i]).val()+',';
		}
		return str;
	}

	//保存
	$('#saves').click((function(event) {
		var tar = $('#outputDir').val();
		var str = select();
		if(str){
			$.post(
				'./index.php/welcome/save',
				{'tar':tar,'save':'save','array':str,'tar':$('#outputDir').val(),'gt':$('#gt').val(),'src':$('#dirurl').val()},
				function(data){
					var str = '';
					for (var i = $.parseJSON(data).length - 1; i >= 0; i--) {
						str += '<li>'+$.parseJSON(data)[i]+'</li>';
					}
					$('#result').append(str);
				}	
			)
		}
	}));

	//全选反选

	$('#datatalbe tbody').on( 'click', '.checkboxs', function () {
		$(this).parent('td').parent('tr').toggleClass('selected');
    	} );

	$('#checkAll').click( function () {
		var obj = table.rows('tr').nodes();
		$(obj).toggleClass('selected');
		var obj2 = table.$('.checkboxs');
		if(selectAll){
			obj2.prop("checked",false);
			selectAll = false;
		}else{
			obj2.prop("checked",true);
			selectAll = true;

		}
	})

	$('#gt').change(function(){
		table.ajax.reload();
	})

 	$(window).ajaxStart(function(){
 		$('body').css('cursor','wait');
 		$('#loading').show(0);
 		$('.alertred').hide(300);
 		$('.alertred1 ').hide(300);
  		$('.ml').attr("disabled","disabled");
 	});



 	$(window).ajaxStop(function(){
 		$('body').css('cursor','default');
 		$('#loading').hide(0);
 		$('.ml').attr("disabled",false);
 	})

 	$('#basic-url').change(function(){
 		var val = $(this).val();
 		while(val.indexOf('\\') != '-1') {
 			val = val.replace('\\','/');
 		};
 		$(this).val(val);
 	});
 	
 	
 	$('#ftpup').click(function(){
 		var str = select();
 		if(str){
	 		var basicUrl = $('#basic-url').val();
//	 		while( str.indexOf(basicUrl) != -1  ){
//	 			str = str.replace(basicUrl,'/');
//	 		}
	 		$.post(
					'./index.php/welcome/upload.php',
					{'ftp':'ftp','array':str,'local':basicUrl},
					function(data){
						console.log($.parseJSON(data))
					}	
				)
 		}
 	})
 	
 	
 	$('#ftp').click(function(){
 		window.open('./index.php?ftp=ftp');
 	})
})
</script>