<!DOCTYPE html
			PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php 
	require_once 'lib/common.php';
	require_once 'lib/fun.php';
	
	global $dai_upd_tdtxt;
	global $tdid;
	global $new_or_upd;
	
	$ers = '';
	$addorEdit = 'Add';
	$exi = null;

	session_start();
	
if ($_POST)
	
{		// 编辑按钮被点击, 输入框显示指定id的文本 同时添加按钮变更为确认. 
	if (isset($_POST['todoupd']))
	{	
		$str_array = $_POST['todoupd'];
		// 获取id
		$tdid = array_Keys($str_array)[0];
		
		$_SESSION['tdid'] = $tdid;
		
		// 根据id 查询相应todotxt 
		$tdtxt = select_one(getPDO(), $tdid);
		$tdtxt = array_Values($tdtxt)[0];
		if (isset($tdtxt))
		{		
			$addorEdit = 'confirm changes';
			$new_or_upd = 'updtext';
			$dai_upd_tdtxt = $tdtxt;		
		}
	}
	
	if (@'confirm changes' == @$qrxg = $_POST['Submit'])
	{
		
		if(isset($_POST['updtext']))
		{		
			$genggaihou = $_POST['updtext'];
		
			$tid = $_SESSION['tdid'];

			// 将待更改的字符, 相应id 作为参数 		
			update_one(getPDO(), $genggaihou, $tid);
	
		}
	}

	
	/*
	 * 添加一条
	 */

	if ($_POST)
	{
		/* 发现空格也可以提交, 列表会显示新的一行空白. 
		*  用trim 函数去除字符串两边的空格.
		*/
		if (isset($_POST['newtext']))
		{
			if ($addorEdit == $st = $_POST['Submit'])
			{
				$newText = $_POST['newtext'];
				$newText_tr = trim($newText);
				if ($newText_tr == '')
				{
					$ers = '内容为空';
					
				}
				else
				{
					new_text(getPDO(), $newText_tr);	
				}
			}

		}

	}

}
	
	$pdo = getPDO();
	$stmt = $pdo -> query(
			'SELECT 
					id, todotxt, okorno, created_at 
			FROM 
					todo
			ORDER BY 
					created_at DESC'
	);
	
	if ($stmt === false)
	{
		throw new Exception('运行此查询时出现问题');
	}
?>
<html>
	<head>
		<title>todo</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<style type="text/css">
			body {
				margin: 8px;
				font-size: 21px;
			}
			.mainkj {
				background-color:#f6f6ef;
			}
			.anniu {
			
			}
			.maintb {
				width: 100%;
				background-color: black;
				border: 0px;
			}
			.todoid {
				width: 1%;
			}
			.todobody {
				width: 85%;
			}
			.subdata {
				text-align: right;
			}
			.button {
				text-align: right;
			}
			.tr1 {
				background-color: #f6f6ef;
			}
			.bton {
				font-size: 21px;
			}
		</style>
	</head>
	<body>
		<div class="mainkj">
			<table class="maintb">
				<?php $ymid = 0; // 页面显示的序号 ?>
				<?php while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)): 
						// 页面显示的序号
						$ymid++;
						$bodytxt = htmlEscape($row['todotxt']);
						$paratxt = str_replace("\n", "<br />&nbsp;&nbsp;", $bodytxt);
						
				?>
				<?php /**
						点击完成, 从form_crud页面获取被点击那一行对应的数据库id字段的值, 将值作为参数来使用
						fun脚本中对应的函数 
						
				
						点击完成后, 整行的字体颜色变淡
						如果 数据库中的状态字段(okorno)为 ok 就变淡 同时 '完成'按钮 变更为取消 
						否则不变	
					*/
					if ('ok' === $row['okorno'])
					{
						$dwithbd = '#808080';
						$submit_button = 'Cancel';
					}
					else
					{
						$dwithbd = '';
						$submit_button = 'Finish';
					}
				?>
				<form action="./lib/form_crud.php" method="POST">
					<tr class="tr1" style="color: <?php echo $dwithbd; ?>;">
						<td class="todoid"><?php echo htmlEscape($ymid . "."); ?></td>
						<td class="todobody">&nbsp;&nbsp;<?php echo $paratxt; ?></td>
						<td class="subdata"><?php echo newDate($row['created_at']); ?></td>
						<td class="button"><input type="submit"
												  name="okorno[<?php echo $row['id']; ?>]"
												  value="<?php echo $submit_button; ?>" 
												  class="bton"
											/>
						</td>
						<td class="button"><input type="submit"
												  value="Del" 
												  name="deltodo[<?php echo $row['id']?>]" 
												  class="bton"
											/>
						</td>
				</form>
						<td class="button">
				<form method="POST" >
							<input  type="submit" 
								    name="todoupd[<?php echo $row['id']?>]" 
									value="Edit"
									class="bton"
							/>
				</form>
				
						</td>
					</tr>
				<!-- </form> -->
				
			<?php endwhile ?>
				<table class="">
					<p />
					<form method="POST">
						<tr class="tr1">
							<label for="newtext"></label>
							<textarea class="bton" id="newtext" rows="12" style="width: 100%; height: 170px;" name="<?php if(isset($new_or_upd)){echo $new_or_upd;}else{echo'newtext';}; ?>" ><?php echo $dai_upd_tdtxt; ?></textarea>													
						</tr>
						<tr>
							<td><input class="bton" type="submit" name="Submit" value="<?php echo $addorEdit; ?>" /></td>
							<td><span style="background-color: red"><b><?php if ($ers){ echo $ers; }?></b></span></td>
						<tr>
					</form>
				</table>
			</table>
		</div>
	</body>
</html>