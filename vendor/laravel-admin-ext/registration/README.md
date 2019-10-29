为您的后台添加注册功能
======

这个扩展用来给您的后台增加注册功能，安装之后，用户可以使用邮箱+密码的组合进行网站后台注册。

插件包含下面的功能：

 * 邮箱注册 & 邮箱验证
 * 通过邮件重置密码
 * 图片验证码(登陆和注册)
 
> 注意：本插件只是实现了最基本的用户注册流程，效果参考[DEMO](https://registration.demo.laravel-admin.org/), 如果你有其它额外的功能，请这个插件的基础上自行做二次开发。
 
## 准备工作

这个扩展包依赖Laravel-admin, 所以先按照官方文档安装好Laravel和Laravel-admin, 并保证laravel-admin正常运行。

用户使用邮箱注册，并提供了邮箱验证和密码找回功能，所以需要先确保发送邮件功能正常使用。

邮件的配置和发送参考文档[邮件发送](https://learnku.com/docs/laravel/5.8/mail/3920)。

## 安装

解压压缩包之后放到`storage/extensions/registration`目录下，然后修改项目根目录的`composer.json`文件（**注意不是前面解压目录下的composer.json, 是项目根目录下的**），添加下面的配置：

```json
"repositories": [
    {
        "type": "path",
        "url": "storage/extensions/registration",
        "options": {
          "symlink": false
        }
    }
]
```

> 当然也可以解压之后放到任何其它目录，只要保证`url`正确即可。加上`symlink`选项，在完成安装之后这个目录可以删除。

然后运行下面的命令安装这个扩展包：

```shell
composer require laravel-admin-ext/registration -vvv
```

如果运行上面命令的过程中出现下面的错误：
```shell
[InvalidArgumentException]
  Could not find package laravel-admin-ext/registration at any version for your minimum-stability (dev). Check the package spelling or your minimum-stability
```

这是由于`composer`的最小稳定性设置不满足要求，建议在`composer.json`里面将`minimum-stability`设置为`dev`，另外`prefer-stable`设置为true, 这样给你的应用安装其它package的时候，还是会倾向于安装稳定版本, 
在根目录下的`composer.json`里添加下面的配置：
```json
{
    ...
    "minimum-stability": "dev",
    "prefer-stable": true,
    ...
}
```

运行下面的命令发布配置、语言、迁移文件：

```shell
php artisan vendor:publish --provider="Encore\Admin\Registration\RegistrationServiceProvider"
```

迁移文件发布之后运行下面的命令来创建和修改表:

```shell
php artisan migrate
```
> 如果你的表不是使用Laravel的迁移方案来管理，可以参考`database/migrations/2019_04_11_173148_alter_admin_user_table.php`文件来修改你的数据库。

最后打开`config/admin.php`，在路由配置部分，给后台路由增加一个邮箱验证的中间件`admin.verified`：

```php
    'route' => [

        ...

        'middleware' => ['web', 'admin', 'admin.verified'],
    ],
```

完成了上面的步骤之后，打开后台，就可以在登陆页面看到注册链接了。

## 配置

安装完成之后配置文件发布在`config/admin/registration.php`, 在里面可以对注册插件进行配置修改。

下面是几个配置的使用介绍

### 默认角色

安装Laravel-admin之后，默认只有一个超级管理员(administrator)角色，这个角色可以有查看所有菜单，拥有操作所有页面的权限，为了限制新注册用户的权限，建议先创建一个默认的新注册用户权限，给与基本的权限。

打开`http://localhost/admin/auth/roles`, 点击新增，标示和名称填写你定义的角色，比如`guest`和`访客`，然后在下面的权限选择中选择`Dashboard`和`User setting`，最后在配置文件`config/admin/registration.php`中设置：

```php
'default_role' => 'guest',
```

这样新注册的用户默认的角色就是上面设置的`guest`只有访问Dashboard页面和用户设置的权限

### 视图配置

`views`配置项用来配置注册、登陆等页面的视图模板，如果你需要对这些页面进行修改，比如修改样式或者增加一些文字提示，可以在`storage/extensions/registration/resources/views`目录下面找到页面对应视图文件
复制出来放到`resources/views`下面，修改之后在配置文件中修改指定视图。

比如假设你要修改注册页面，那么先复制`storage/extensions/registration/resources/views/register.blade.php`文件到`resources/views/registration/register.blade.php`, 然后修改这个文件，修改完成之后，在
`config/admin/registration.php`的views部分修改`register`项为`registration.register`即可。

### 图形验证码修改

该扩展依赖图形验证码扩展包[mews/captcha](https://github.com/mewebstudio/captcha), 这个包会随注册插件的安装一并安装，
如果你要修改登陆或者注册页面的图形验证码的尺寸或者长度等信息，先运行下面的命令来发布配置文件

```shell
php artisan vendor:publish --provider="Mews\Captcha\CaptchaServiceProvider"
```

然后打开`config/captcha.php`进行相关的配置。

注册插件默认使用`default`组的配置，可以直接在这个组下面修改配置，或者增加一项`admin`配置组，专门用来给登陆和注册页面使用:

```php

    'admin' => [
        'length'  => 9,
        'width'   => 120,
        'height'  => 36,
        'quality' => 90,
        'math'    => false,
    ],
    
```

### 注册邮件验证有效期设置

`verification_expire`用来设置注册验证链接的有效期，默认为60分钟。

## 翻译问题

把系统语言设置为`zh-CN`，参考项目[Laravel-lang](https://github.com/caouecs/Laravel-lang)安装好中文翻译文件

如果注册或者登陆页面提交表单之后返回了未翻译的错误，那需要到`resources/lang/zh-CN`目录下去增加相关的翻译。

如果出现了`validation.captcha`的错误提示，到`resources/lang/zh-CN/validation.php`下增加`'captcha' => '验证码错误',`翻译。

比如出现了`captcha 不能为空。`的错误提示，到`resources/lang/zh-CN/validation.php`的`attributes`下增加`'captcha' => '验证码'`。



## 版权声明

请尊重作者知识产权, 勿私下转让或传播。