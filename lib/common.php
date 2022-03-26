<?php
/*
 * 获取根目录
 *
 * @return string 
 */
function getRootPath()
{
	return realpath(__DIR__ . '/..');
}

/*
 * 获取数据库文件所在路径
 *
 * @return string 
 */
function getDatabasePath()
 {
	 return getRootPath() . '/data/data.sqlite';
 }
 
/*
 * 获取数据库连接
 * 
 * @return string 
 */
function getDsn()
{
	return 'sqlite:' . getDatabasePath();
}

/*
 * 获取PDO对象
 *
 * @return PDO
 */
function getPDO()
{
	$pdo = new PDO(getDsn());

	$result = $pdo->query('PRAGMA foreign_keys = ON');
	echo $result === true;
	if ($result === false)
	{
		throw new Exception('无法打开外键约束');
	}
	return $pdo;
}

/*
 * 转义 HTML，以便安全输出
 *
 * @return string 
 */
function htmlEscape($st)
{
	return htmlspecialchars($st, ENT_HTML5, 'UTF-8');
}

function newDate($date)
{
	$d = DateTime::createFromFormat('Y-m-d', $date);
	return $d->format('Y.m.d');
}