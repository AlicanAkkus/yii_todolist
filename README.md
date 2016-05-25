TODO List Application
============

Kullanıcı uygulamayı kurup kullanabilmesi için aşağıdaki adımlalrı yapması gerekmektedir;

- Migrate
- Rbac
- Configuring
- Setup
- Restful API

```
Not : Kurulum yapılacak olan bilgisayar üzerinde xampp kurulu olması gerekmekedir.
Base directory olarak Xampp'in C:\ dizini altında oldugu varsayılmıştır.
```

1. Migrate
Uygulamada kullanılan tabloların taşınması ve hazır hale getirilmesi için gerekli olan adımdır.


Migrate oluşturmak için;
``` php
	${baseDirectory}\htdocs\advanced\console\migrations
	yii migrate
```

Migrate configürasyonu için;
```
	return [
		'controllerMap' => [
			'migrate' => [
				'class' => 'yii\console\controllers\MigrateController',
				'migrationTable' => 'backend_migration',
			],
		],
	];
```


2. Rbac

Kullanıcı erişim kuralları ve configürasyonu için configürasyonuna şunların yazılması gerekmektedir;

```
Base directory içerisinde apache server dosyalarının buludundu kısımda main-local.php(dev ortamı) bulunur.
```

${baseDirectory}\htdocs\advanced\common\config\main-local.php

```
	<?php
	return [
		....
		....
			'authManager' => [
				'class' => 'yii\rbac\DbManager',
			],
		....
	        ....

	];
```

Daha sonra Rbac için bir kontroller oluşturulur.  İlk önce rbac'i initialize etmek gerekmektedir;


RbacController

```
	${baseDirectory}\htdocs\advanced\console\controllers
	yii rbac/init
```

Yaratıcı olan kullanıcılar için rule tanımı;
AuthorRule

```
	${baseDirectory}\htdocs\advanced\common
	new folder "rbac"
	yii rbac/author-rule

```

Kullanıcı, author'lar için migrate tabloları;

```
yii migrate --migrationPath=@yii/rbac/migrations
yii rbac/init
yii rbac/author-rule

```

3. Configuring

${baseDirectory}\htdocs\advanced\composer.json
Uygulamanın git uzantısı buraya eklenmelidir;
######alicanakkus/yii_todolist":"dev-master"

```
	"require": {
        "php": ">=5.4.0",
		"yiisoft/yii2": ">=2.0.6",
		"yiisoft/yii2-bootstrap": "*",
		"yiisoft/yii2-swiftmailer": "*",
		"alicanakkus/yii_todolist":"dev-master"
        },
```


${baseDirectory}\htdocs\advanced\backend\config\main-local.php

```
	$config = [
		'modules' => [
			'blog' => [
				'class' => 'alicanakkus\yii_todolist\Module',
			],
		],
```


4. Setup

${baseDirectory}\htdocs\advanced
Composer cache temizlenebilir her ihtimale karşı.
Composer update ile birlikte require olarak belirtilen git uzantısındaki uygulama composer ile yüklenecektir.
```
	composer clear-cache
	commposer update
```

5. Restful API

${baseDirectory}\htdocs\advanced\backend\config\main-local.php

```
	$config = [
    'modules' => [
        'blog' => [
            'class' => 'alicanakkus\yii_todolist\Module',
        ],
    ],


    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'a20IHwCqs8sw3h1TwFMhGvfwI_jLmbzO',
            'class' => 'common\components\AppRequest',

            'parsers' => [
                'application\json' => 'yii\web\JsonParser',
            ],

            'web' => '/backend/web',
            'aliasUrl' =>'/admin',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'restful',
                'tokens' => [
                    '{id}' => '<id:\\w+'
                ]
            ]

            ],
        ],
    ],

];
```

${baseDirectory}\htdocs\advanced\frontend\config\main-local.php

```
	'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'bcsHZoIMxZ8Rkbpmt0SFvmZvDCQCK26d',
            'class' => 'common\components\AppRequest',
            'web' => '/frontend/web',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            ],
    ],
```

Apache servisinde alias kullanmak için "advanced.com" adresine gelen istekleri,
127.0.0.1 local ip adresine yönlendirme yapmamız gerekir.
Windows ortamında dizin 'C:\Windows\System32\drivers\etc\hosts', linux/unix ortamında ise 'etc/hosts' kısmıdır.

Hosts olarak şu girilmelidir;

```
127.0.0.1       advanced.com
```

${baseDirectory}\apache\conf\extra\httpd-vhosts.conf
Gelen advanced.com istekleri 80 HTTP portuna aktarılır.

```

NameVirtualHost *:80

<VirtualHost *:80>
    ServerAdmin webmaster@advanced.com
    DocumentRoot "${baseDirectory}/htdocs/advanced"
    ServerName advanced.com
    ErrorLog "logs/advanced.com.log"
    CustomLog "logs/advanced.com-access.log" common
</VirtualHost>

```


${baseDirectory}\htdocs\advanced\
######.htaccess

```
Options +FollowSymlinks
RewriteEngine On

RewriteCond %{REQUEST_URI} ^/(admin)
RewriteRule ^admin/assets/(.*)$ backend/web/assets/$1 [L]
RewriteRule ^admin/css/(.*)$ backend/web/css/$1 [L]

RewriteCond %{REQUEST_URI} !^/backend/web/(assets|css)/
RewriteCond %{REQUEST_URI} ^/(admin)
RewriteRule ^.*$ backend/web/index.php [L]


RewriteCond %{REQUEST_URI} ^/(assets|css)
RewriteRule ^assets/(.*)$ frontend/web/assets/$1 [L]
RewriteRule ^css/(.*)$ frontend/web/css/$1 [L]

RewriteCond %{REQUEST_URI} !^/(frontend|backend)/web/(assets|css)/
RewriteCond %{REQUEST_URI} !index.php
RewriteCond %{REQUEST_FILENAME} !-f [OR]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^.*$ frontend/web/index.php

RewriteCond %{REQUEST_URI} ^/(api)
RewriteRule ^api/assets/(.*)$ api/web/assets/$1 [L]
RewriteRule ^api/css/(.*)$ api/web/css/$1 [L]

RewriteCond %{REQUEST_URI} !^/api/web/(assets|css)/
RewriteCond %{REQUEST_URI} ^/(api)
RewriteRule ^.*$ api/web/index.php [L]
```

Uygulamanın main app request için şunu yazmamız gerekmekte;
\todo\components\AppRequest.php

```
<?php

namespace common\components;

use Yii;
use yii\web\Request;

class AppRequest extends Request {
    public $web;
    public $aliasUrl;

    public function getBaseUrl(){
        return str_replace($this->web, "", parent::getBaseUrl()) . $this->aliasUrl;
    }
}
```


Tüm bunlar tamamlandığıda tarayıcıda şunu girmek gerekir;
~~~
http://advanced.com/admin/todo/todo/index
~~~

Örnek ekran görüntüler;

Todo Listesi;
![Alt text](/ss/Selection_065.png?raw=true "Example TODO LIST UI")

Todo Görüntüleme;
![Alt text](/ss/Selection_066.png?raw=true "Example TODO VIEW UI")




