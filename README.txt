### 微信项目
### 环境要求
php5.3（最佳）以上+ mysql+ apache；php函数需要开启openssl，独立服务器或云主机可以在php.ini里设置开启，虚拟主机请联系空间商，若不能开启请不要购买该主机

切记不能放在子目录，最好使用apache，IIS不建议用或者就不要用，一些营销活动兼容性差，因为IIS导致营销模块不兼容，请配合换为apache的已满足使用要求。可以用phpstudy等集成环境配置


将程序解压缩到您网站根目录
在mysql数据库中建立一个数据库，并导入db.sql文件
修改data目录下的config.php文件
$config['db']['host'] = '填写您的数据库地址，本地请填写为127.0.0.1';
$config['db']['username'] = '填写数据库用户名';
$config['db']['password'] = '填写数据库密码';
$config['db']['port'] = '数据库端口，一般为3306';
$config['db']['database'] = '填写数据库名';


分销管理请直接访问您的站点域名（网址）即可
账号 密码分别为  admin   admin999	
