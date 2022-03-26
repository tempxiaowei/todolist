<?php
	require_once 'common.php';
	require_once 'fun.php';
	
	$edit_txt = ''; // 声明一下, 方便index.php 调用
	
	
	/**
	 *关于删除一条 
	 */
	if ($_POST)
	{
		@$deltodo = $_POST['deltodo'];	// 加@不是聪明的办法
		
		if ($deltodo)
		{
			//获取数组中所有键的索引
			$keyssy = array_keys($deltodo);
			$isL = $keyssy[0];
			
			if ($isL)
			{
				delete_todo(getPDO(), $isL);
				header('Location: ./../index.php');
				exit;
			}
		}
	}
	
	// 完成...
	if ($_POST)
	{
		@$todoid = $_POST['okorno'];
		
		if ($todoid)
		{
			$keyssy = array_keys($todoid); // 将参数的键为值与下标相映射
			$isL = $keyssy[0];
			
			// 如果数组中键的值是'完成' 则更改数据库状态字段的值 ...				
			upd_status(getPDO(), $isL, $todoid[$isL]);
			header('Location: ./../index.php');
			exit;
		}	
	}
	

	
