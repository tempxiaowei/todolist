<?php
/**
 *@param PDO $pdo 
 *@param integer $todoId
 *@return 删除成功时返回true 
 *@throws Exception
 */

function delete_todo(PDO $pdo, $todoId)
{
	
	$jg = $pdo -> prepare(" DELETE FROM todo WHERE id = $todoId ");

	if ($jg === false)
	{
		throw new Exception('运行此查询语句时出现问题');
	}
	
	$jg->execute();
	$rC = $jg -> rowCount();
	
	return $jg !== false;
}

function new_text(PDO $pdo, $txt)
{
    $sql = "
        INSERT INTO
            todo
            (todotxt, okorno, created_at)
            VALUES
            (:todotxt, :okorno, :created_at)
    ";
    $jg = $pdo -> prepare($sql);
    if ($jg === false)
    {
        throw new Exception('有误检查语句');
    }
    // Now run the query, with these parameters
    $ret = $jg -> execute(
        array(
            ':todotxt' => $txt,
            ':okorno' => '',
            ':created_at' => date('Y-m-d'),
        )
    );
	
	
	
    if ($ret === false)
    {
        throw new Exception('无法运行插入语句');
    }
	if ($ret !== false)
	{
		/** 为了避免按F5重复提交表单内容而这样写. 
			我搜索解决方案时看到很多网友不是这样写的.
			
			数据多的情况下这样写会影响效率吧? 那到时候再改吧.
		*/
		$hr = header('Location: /../index.php');
	}
    return $hr;

}

function upd_status(PDO $pdo, $todoId, $status)
{	
	$sql = "
			UPDATE
				todo
			SET
				okorno = :okorno
			WHERE
				id = :id
	";
	
	$jg = $pdo -> prepare($sql);
    if ($jg === false)
    {
        throw new Exception('有误检查语句');
    }
   
	if ($status === 'Finish')
	{
		$okorno = 'ok';
	}
	if ($status === 'Cancel')
	{
		$okorno = 'no';
	}
   
    $ret = $jg -> execute(array(':okorno' => $okorno, ':id' => $todoId));
	
	if ($ret === false)
	{
		throw new Exception('无法运行插入语句');
	}
	return $ret !== false;
	
}

/**
 *	查询一条
 */
function select_one(PDO $pdo, $todoId)
{
	$sql = "
			SELECT todotxt FROM todo WHERE id = $todoId
	";
	
	$st = $pdo -> query($sql);
	$ret = $st -> fetch(PDO::FETCH_ASSOC);
	
	return $ret;
}

/*
 * 更改一条
 * PDO $pdo 
 * Integer $todoId 
 * string $todotxt 
 * return bool
 */
function update_one(PDO $pdo, $todotxt, $todoId)
{

	$sql = "
			UPDATE
				todo
			SET
				todotxt = :todotxt
			WHERE
				id = :todoId
	";

	$jg = $pdo -> prepare($sql);
	

	$ret = $jg -> execute(array(':todotxt'=>$todotxt, ':todoId'=>$todoId));

	
	return $ret !== false;
}	

