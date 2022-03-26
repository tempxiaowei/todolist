<?php 
	require_once 'lib/common.php';
	
	//获取所在工作目录
	$root = getRootPath();
	//将工作目录与当前目录下的data目录下的data.sqlite文件相拼接
	$dtbase = getDatabasePath();
	
	$error = '';
	// 如果文件可读并且文件大小 > 0字节 
	if (is_readable($dtbase) && filesize($dtbase) > 0)
	{
		$error = '数据库已存在, 请手动删除现有数据库文件';
		// 如果变量$error 为空字符串
		if (!$error)
		{
			// 如果数据库文件不存在则创建
			$createdt_file = @touch($dtbase);
			$error = sprintf('无法创建数据库，请允许服务器在\'%s\'创建新文件',
				dirname($database));
		}	
	}
	
	// 如果$error为空字符串
	if (!$error)
	{
		// file_get_contents() 将整个文件读入一个字符串
		// 将当前工作目录拼接字符串'/data/init.sql'
		$sql = file_get_contents($root . '/data/init.sql');
		// 如果$sql全等于false
		if ($sql === false)
		{
			$error = '找不到sql文件';
		}
	}
	
	// 如果$error为空字符串
	if (!$error)
	{
		$pdo = getPDO();
		$result = $pdo->exec($sql);
		if ($result === false)
		{
			$error = '无法运行sql语句: ' . print_r($pdo->errorInfo(), true);
		}
	}
	
	$count = null;
	if (!$error)
	{
		$sql = "SELECT COUNT(*) AS c FROM todo";
		$stmt = $pdo -> query($sql);
		if ($stmt)
		{
			$count = $stmt -> fetchColumn();
		}
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>tdins</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <style type="text/css">
            .box {
                border: 1px dotted silver;
                border-radius: 5px;
                padding: 5px;
            }
            .error {
                background-color: #ff6666;
            }
            .success {
                background-color: #88ff88;
            }
        </style>
    </head>
    <body>
        <?php if ($error): ?>
            <div class="error box">
                <?php echo $error ?>
            </div>
        <?php else: ?>
            <div class="success box">
                数据库创建成功.                      
					<a href=".." >返回首页</a>        
            </div>
        <?php endif ?>
    </body>
</html>