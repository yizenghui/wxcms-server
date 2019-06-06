laravel-admin multitenancy
======

在同一套laravel框架中安装多个互相独立的后台。

## Installation

解压之后放到`storage/multitenancy`目录下，然后在`composer.json`中增加下面的配置

```json
    "repositories": [
        {
            "type": "path",
            "url": "storage/multitenancy",
            "options": {
              "symlink": false
            }
        }
    ]
```
然后运行`composer require laravel-admin-ext/multitenancy`安装它

如果运行上面的命令出现下面的错误：
```bash
[InvalidArgumentException]
  Could not find package laravel-admin-ext/multitenancy at any version for your minimum-stability (dev). Check the package spelling or your minimum-stability
```
这是由于composer的最小稳定性设置不满足，建议在composer.json里面将`minimum-stability`设置为`dev`，另外`prefer-stable`设置为true, 这样给你的应用安装其它package的时候，还是会倾向于安装稳定版本, 
composer.json的修改如下
```json
{
    ...
    "minimum-stability": "dev",
    "prefer-stable": true,
    ...
}
```


然后运行下面的命令发布配置文件

```bash
php artisan vendor:publish --provider="Encore\Admin\Multitenancy\MultitenancyServiceProvider"
```
运行完成之后会在生成另一个后台的配置文件`config/tenancy.php`, 这个文件和`config/admin.php`有一样的结构

由于新建的后台需要和原后台保持隔离，所以推荐新建一个新的数据库来存放对应表，这里要创建一个新的数据库，并且在`config/database.php`中添加一个名字为`tenancy`的数据库连接

> 当然数据库连接也可以是任何其它的名字，如果使用了其它的连接名，需要在`config/tenancy.php`中修改`database.connection`为相应的值。
> 如果你不想再创建一个新的数据库来安装新的后台，打开`config/tenancy.php`，将`database`下的`connection`设置为空字符串，然后下面的表配置全部重命名掉，避免和第一个后台的这几个表冲突，建议将`admin`前缀替换即可。

第二个后台将会默认安装在`app/Tenancy`目录下，如果你需要安装在其它目录，修改`config/tenancy.php`中的`directory`配置项即可，对应的`route.prefix`、`route.namespace`和`auth.controller`也要修改成对应的值。

把上面生成的配置文件路径写入`config/admin.php`的`extensions`下：

```php
    'extensions' => [
    
        'multitenancy' => [
            'tenancy' => config_path('tenancy.php'),
        ]
        
    ]
```
最后运行下面的命令完成安装：
```bash
php artisan admin:install:tenancy
```

打开`http://localhost:8080/tenancy`访问新建的后台，这个新后台的开发工作就在`app/Tenancy`目录下了，这个后台对应的配置文件是`config/tenancy.php`，根据你的需求修改里面的配置。

## 安装多个后台

如果需要在这个项目中安装更多的后台，复制`config/tenancy.php`文件到config目录下，比如`config/platform.php`，然后参考上面修改里面的数据库连接

因为需要安装在一个新的目录，所以同样要修改`config/platform.php`中的`directory`、`route.prefix`、`route.namespace`和`auth.controller`这几个配置项的值，

然后把配置文件路径写入`config/admin.php`的`extensions`下：

```php
    'extensions' => [
        'multitenancy' => [
            'tenancy' => config_path('tenancy.php'),
            
            // 增加下面一行
            'platform' => config_path('platform.php'),
        ]
    ]
```

最后运行下面的命令安装
```bash
php artisan admin:install:tenancy platform
```
如果你在`config/platform.php`设置的路由前缀是`paltform`, 那么就打开`http://localhost:8080/paltform`访问新建的后台。

## session冲突问题

如果你在同一个浏览器窗口打开两个后台，登入或者登出一个后台会影响到另一个后台，通过下面的方式来解决这个问题

### 区分路径访问

如果多个后台是通过不同的路径访问的，在每个后台的配置文件中（比如`config/admin.php`和`config/tenacy.php`）的route配置的中间件配置数据加上下面

```php
    'route' => [

        'prefix' => 'tenancy',

        'namespace' => 'App\\Tenancy\\Controllers',

        'middleware' => ['web', 'admin', 'multi-session:path,/tenancy'],
    ],
```

在`'multi-session:path,/tenancy'`中，`/tenancy`就是该后台的访问根路径

### 区分域名访问

如果多个后台是通过不同的域名访问的，分别在每个后台的配置文件中加上中间件

```php
    'route' => [

        'prefix' => 'tenancy',

        'namespace' => 'App\\Tenancy\\Controllers',

        'middleware' => ['web', 'admin', 'multi-session:domain,admin.laravel.com'],
    ],
```

在`'multi-session:domain,admin.laravel.com'`中，`admin.laravel.com`就是该后台的访问域名

## 版权声明

请尊重作者知识产权, 勿传播他人使用。

