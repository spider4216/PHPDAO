# PHPDAO
Data Access Object for PHP 7+

##Get started
###Init
```php
$mysqlGenerator = DAOFactory::initial(1);
$mysqlGenerator->setHost('host');
$mysqlGenerator->setUsername('username');
$mysqlGenerator->setPassword('password');
$mysqlGenerator->setDbName('dbname');
$mysqlGenerator->createConnection();

/** @var \DAObjects\MysqlDAO $daoMysql */
$daoMysql = $mysqlGenerator->generalDAO();
```
####Insert
```php
$res = $daoMysql
    ->table('news')
    ->insert([
        'title' => 'my title',
        'description' => 'my description'
    ])->execute();
```
####Update
```php
$res = $daoMysql
	->table('news')
	->update([
		'title' => 'my title updated',
		'description' => 'my description updated',
	])
	->where([
		'id' => 3,
	])
	->execute();
```
####Delete
```php
$res = $daoMysql
	->delete()
	->from('news')
	->where([
		'id' => 2,
	])
	->execute();
```
####fetchAll
```php
$res = $daoMysql
	->select('title')
	->from('news')
	->fetchAll();
```

####fetchRow
```php
$res = $daoMysql
	->select('title')
	->from('news')
	->where([
		'id' => 3,
	])
	->fetchRow();
```
####innerJoin
```php
$res = $daoMysql
    ->select('n.*, a.*, p.*')
    ->from('news n')
    ->innerJoin('authors a', 'a.id = n.author_id')
    ->innerJoin('profile p', 'p.id = a.id')
    ->where(['a.username' => 'admin'])
    ->fetchAll();
```
