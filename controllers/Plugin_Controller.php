<?php

declare(strict_types=1);

namespace n0nag0n;

class Plugin_Controller extends Base_Controller {
	public function indexAction(\Base $fw): void {
		$projects = [
			'projects' => [
				'database' => [
					[
						'title' => 'Cortex',
						'description' => 'A general purpose ORM with support for relations and so more.',
						'github' => 'https://github.com/ikkez/f3-cortex',
						'composer_package' => 'ikkez/f3-cortex'
					],
					[
						'title' => 'SQL Schema Builder',
						'description' => 'An extension for creating and manipulating SQL database tables.',
						'github' => 'https://github.com/ikkez/f3-schema-builder',
						'composer_package' => 'ikkez/f3-schema-builder'
					],
					[
						'title' => 'F3 Validation engine',
						'description' => 'This is an extension for the PHP Fat-Free Framework that offers a validation system, especially tailored for validating Cortex models. The validation system is based on the well known GUMP validator, with additional sugar of course.',
						'github' => 'https://github.com/ikkez/f3-validation-engine',
						'composer_package' => 'ikkez/f3-validation-engine'
					],
					[
						'title' => 'Ilgar',
						'description' => 'Quick and simple migration tool.',
						'github' => 'https://github.com/chez14/f3-ilgar',
						'composer_package' => 'chez14/f3-ilgar'
					],
					[
						'title' => 'F3-Migrations',
						'description' => 'F3-Migrations is a database helper plugin.',
						'github' => 'https://github.com/myaghobi/f3-migrations',
						'composer_package' => 'myaghobi/f3-migrations'
					],
					[
						'title' => 'F3-Pagination',
						'description' => 'Create quick and easy Pagination for your F3 application.',
						'github' => 'https://github.com/ikkez/f3-pagination',
						'composer_package' => 'ikkez/f3-pagination'
					],
					[
						'title' => 'F3-Soft Erase',
						'description' => 'Instead of removing records from your database, the SoftErase Trait will store records in a trashed state, and deleted records can be restored.',
						'github' => 'https://github.com/geofmureithi/f3-softerase',
						'composer_package' => 'geofmureithi/f3-softerase'
					],
				],
				'security' => [
					[
						'title' => 'XSS Filter',
						'description' => 'XSS Filter to properly clean your request data from XSS related attacks.',
						'github' => 'https://github.com/n0nag0n/fatfree-xss-filter',
						'composer_package' => 'n0nag0n/fatfree-xss-filter'
					],
					[
						'title' => 'Captcha',
						'description' => 'A better captcha.',
						'github' => 'https://github.com/myaghobi/F3-Captcha',
						'composer_package' => 'myaghobi/F3-Captcha'
					],
					[
						'title' => 'F3-opauth',
						'description' => 'This is a plugin for easy integration of Opauth.',
						'github' => 'https://github.com/ikkez/f3-opauth',
						'composer_package' => 'ikkez/f3-opauth'
					],
				],
				'data' => [
					[
						'title' => 'F3-Validate',
						'description' => 'An easy to use and strait to the point validation trait.',
						'github' => 'https://github.com/geofmureithi/f3-validate',
						'composer_package' => 'geofmureithi/f3-validate'
					],
					[
						'title' => 'F3-Validator',
						'description' => 'Easy to use Validation package for Fatfree-framework with using F3 built-in translations. You can also use it directly in the model.',
						'github' => 'https://github.com/AnandPilania/f3-validator',
						'composer_package' => 'AnandPilania/f3-validator'
					],
					[
						'title' => 'F3-Sheet',
						'description' => 'Some Excel and CSV utilities.',
						'github' => 'https://github.com/ikkez/f3-sheet',
						'composer_package' => 'ikkez/f3-sheet'
					],
				],
				'events' => [
					[
						'title' => 'Sugar Events',
						'description' => 'This is a event system for Fat-Free.',
						'github' => 'https://github.com/ikkez/f3-events',
						'composer_package' => 'ikkez/f3-events'
					],
				],
				'notifications' => [
					[
						'title' => 'F3-Flash',
						'description' => 'This is a little plugin to add simple Flash Messages and Flash Keys.',
						'github' => 'https://github.com/ikkez/f3-flash',
						'composer_package' => 'ikkez/f3-flash'
					],
				],
				'email' => [
					[
						'title' => 'F3-Mailer',
						'description' => 'A SMTP plugin wrapper.',
						'github' => 'https://github.com/ikkez/f3-mailer',
						'composer_package' => 'ikkez/f3-mailer'
					],
				],
				'router' => [
					[
						'title' => 'F3-Middleware',
						'description' => 'It\'s based on the F3 core router, that can be called independently before or after the main routing cycle. This can be useful if you want to hook into a group of other routes and want to do something right before processing the main route handler.',
						'github' => 'https://github.com/ikkez/f3-middleware',
						'composer_package' => 'ikkez/f3-middleware'
					],
					[
						'title' => 'F3-Access',
						'description' => 'Route access control.',
						'github' => 'https://github.com/xfra35/f3-access',
						//'composer_package' => 'xfra35/f3-access'
					],
				],
				'template' => [
					[
						'title' => 'F3-Template Directives',
						'description' => 'Collection of different template directives for the PHP Fat-Free Framework. This package gives you a base to write your own template tag handler (directive) easily.',
						'github' => 'https://github.com/ikkez/f3-template-directives',
						'composer_package' => 'ikkez/f3-template-directives'
					],
					[
						'title' => 'Gravatar and Form Builder',
						'description' => 'A very simple gravatar implementation and form generator which allows you to quickly build forms.',
						'github' => 'https://github.com/MissAllSunday/F3plugins',
						'composer_package' => 'MissAllSunday/F3plugins'
					],
					[
						'title' => 'F3-Template Sections',
						'description' => 'Section support for the F3 Template engine.',
						'github' => 'https://github.com/ikkez/f3-template-sections',
						'composer_package' => 'ikkez/f3-template-sections'
					],
					[
						'title' => 'F3-Assets',
						'description' => 'SASS addon for F3 Sugar Assets plugin.',
						'github' => 'https://github.com/ikkez/f3-assets',
						'webpage' => 'http://f3.ikkez.de/assets',
						'composer_package' => 'ikkez/f3-assets'
					],
					[
						'title' => 'F3-Assets SASS',
						'description' => 'SASS addon for F3 Sugar Assets plugin.',
						'github' => 'https://github.com/ikkez/f3-assets-sass',
						'composer_package' => 'ikkez/f3-assets-sass'
					],
				],
				'task' => [
					[
						'title' => 'F3-Cron',
						'description' => 'Cron Job scheduling.',
						'github' => 'https://github.com/xfra35/f3-cron',
						'composer_package' => 'xfra35/f3-cron'
					],
				],
				'international' => [
					[
						'title' => 'F3-Multilang',
						'description' => 'Create multilingual apps with this localization plugin.',
						'github' => 'https://github.com/xfra35/f3-multilang',
						'composer_package' => 'xfra35/f3-multilang'
					],
				],
				'debug' => [
					[
						'title' => 'F3-Falsum',
						'description' => 'Pretty error handling.',
						'github' => 'https://github.com/ikkez/f3-falsum',
						'composer_package' => 'ikkez/f3-falsum'
					],
				],
				'sms' => [
					[
						'title' => 'F3-SMS',
						'description' => 'A simple f3 plugin to send sms with Mailup.it service.',
						'github' => 'https://github.com/studiosacchetti/f3-sms',
						//'composer_package' => 'ikkez/f3-falsum'
					],
				],
				'api' => [
					[
						'title' => 'F3-wcurl',
						'description' => 'Bridge between your code and external REST API. F3-wcurl acts as a logical abstraction layer for cURL, which handles authentication and sucess response caching.',
						'github' => 'https://github.com/Pilskalns/f3-wcurl',
						'composer_package' => 'Pilskalns/f3-wcurl'
					],
					[
						'title' => 'TADL-CMS',
						'description' => 'A JSON headless CMS.',
						'github' => 'https://github.com/Joseffb/TADL-CMS',
						//'composer_package' => 'Pilskalns/f3-wcurl'
					],
					[
						'title' => 'F3-JsonAPI',
						'description' => 'A few helpful classes to implement a JsonAPI server using the FatFreeFramework and F3-Cortex.',
						'github' => 'https://github.com/delkano/f3-jsonapi',
						'composer_package' => 'delkano/f3-jsonapi'
					],
					[
						'title' => 'F3-Token-Middleware',
						'description' => 'A few helpful classes to implement a JsonAPI server using the FatFreeFramework and F3-Cortex.',
						'github' => 'https://github.com/AnandPilania/f3-token-middleware',
						'composer_package' => 'AnandPilania/f3-token-middleware'
					],
				],
				'misc' => [
					[
						'title' => 'Benchmark',
						'description' => 'A benchmark helper plugin.',
						'github' => 'https://github.com/myaghobi/F3-Benchmark',
						'composer_package' => 'myaghobi/F3-Benchmark'
					],
					[
						'title' => 'System Profile',
						'description' => 'A plugin for grabbing system information like online users and load levels. Also supports basic interpretation of load levels, which allows for adaptive throttling.',
						'github' => 'https://github.com/killsaw/F3-Plugins/blob/master/systemprofile.php',
						//'composer_package' => 'ikkez/f3-fal'
					],
					[
						'title' => 'wpf3',
						'description' => 'This is a dependancy plugin to add fat free framework into the wordpress infrastructure. Fat Free Framework is a microframework that is perfect for building secondary systems that need to integrate with wordpress, without using wordpress mechanisms (i.e. custom schemas, custom routes, etc)',
						'github' => 'https://github.com/Joseffb/wpf3',
					],
					[
						'title' => 'Environment Check',
						'description' => 'This is a small plugin that runs a series of checks on your environment to make sure you have the optimum setup for your Fat-Free Framework Project.',
						'github' => 'https://github.com/n0nag0n/fatfree-environment-check',
						'composer_package' => 'n0nag0n/fatfree-environment-check'
					],
				],
			],
			'examples' => [
				[
					'title' => 'CMS Demo',
					'description' => 'This content management system is designed for instructional purposes and help you get started on the use of the Fat-Free Framework.',
					'github' => 'https://github.com/f3-factory/f3-cms',
				],
				[
					'title' => 'Fabulog',
					'description' => 'fabulog is a lightweight blogging system, based on the awesome php fat-free framework. Check out the demo at http://www.ikkez.de/fabulog.',
					'github' => 'https://github.com/ikkez/fabulog',
				],
				[
					'title' => 'Phproject',
					'description' => 'A high performance full-featured project management system.',
					'web_site' => 'https://www.phproject.org/',
					'github' => 'https://github.com/Alanaktion/phproject',
				],
				[
					'title' => 'selfoss',
					'description' => 'The new multipurpose rss reader, live stream, mashup, aggregation web application.',
					'web_site' => 'https://selfoss.aditu.de/',
					'github' => 'https://github.com/fossar/selfoss'
				],
				[
					'title' => 'toothpaste',
					'description' => 'toothpaste is a lightweight pastebin.',
					'github' => 'https://github.com/sn0opy/toothpaste',
				],
				[
					'title' => 'F3ImageBoard',
					'description' => 'It is a Imageboard Script.',
					'github' => 'https://github.com/SenSeoUtopia/F3ImageBoard',
				],
				[
					'title' => 'API Boilerplate',
					'description' => 'API Boilerplate to make your life easier.',
					'github' => 'https://github.com/chez14/f3-api',
				],
				[
					'title' => 'F3 Multi Lang Site',
					'description' => 'A simple, multi-language website built with Bootstrap 4 on the Fat-Free framework.',
					'github' => 'https://github.com/RichDeBourke/simple-f3-multi-lang-site',
				],
				[
					'title' => 'F3 Boilerplate',
					'description' => 'Super duper fantastic bcosca/fatfree-core boilerplate + rafamds/falsum for debugging.',
					'github' => 'https://github.com/chez14/f3-boilerplate',
				],
				[
					'title' => 'FatFree-Template',
					'description' => 'Small Fat Free Framework application to create training records and comply with IATF audits and other certifications.',
					'github' => 'https://github.com/antoniodiazduran/FatFree-Template',
				],
				[
					'title' => 'F3 PowerDNS Web Interface',
					'description' => 'Powerdns admin frontend, built using the Fat Free Framework. Currently very work in progress, but eventual aim is a simple power dns control panel for Site Admins, Domain Admins and Users.',
					'github' => 'https://github.com/MrSleeps/F3-PDNS-Manager',
				],
				[
					'title' => 'PHP Quiz',
					'description' => 'PHP Quiz website built using Fat Free Framework, Twig template engine, PHP, JavaScript, HTML, CSS, bootstrap and some external libraries.',
					'github' => 'https://github.com/payalpatel178/PHP_Quiz',
				],
				[
					'title' => 'OWASP Mth3l3m3nt Framework',
					'description' => 'OWASP Mth3l3m3nt Framework is a penetration testing aiding tool and exploitation framework. It fosters a principle of attack the web using the web as well as pentest on the go through its responsive interface.',
					'github' => 'https://github.com/alienwithin/OWASP-mth3l3m3nt-framework',
				],
				[
					'title' => 'mtdashmore',
					'description' => 'MultiDashmore - multitenant dashboard. A good saas panel starter project.',
					'github' => 'https://github.com/slimdash/mtdashmore',
				],
				[
					'title' => 'CodeHive',
					'description' => 'CodeHive an Online Editor and Web Storage app with FatFree PHP Framework and CodeMirror.',
					'github' => 'https://github.com/AnandPilania/CodeHive-F3',
				],
				[
					'title' => 'Herohub',
					'description' => 'Herohub is a web application that connects Overwatch players based on their individual preferences.',
					'github' => 'https://github.com/effyn/herohub',
				],
				[
					'title' => 'Grump-Free-Framework',
					'description' => 'Fork of Fat-Free-Framework 3.6 with included MVC project structure with basic automated routing, Formantic-UI, and GrumpyPDO 1.4, Drag & Drop ready to reduce grumpiness.',
					'github' => 'https://github.com/GrumpyCrouton/grump-free-framework',
				],
				[
					'title' => 'Often',
					'description' => 'Often is a simple overtime-tracking tool, that fits you\'re needs.',
					'github' => 'https://github.com/jop-software/often',
				],
			],
		];
		ksort($projects['projects']);
		$this->renderHtml('plugins'.DIRECTORY_SEPARATOR.'index.htm', $projects);
	}

	public static function hasPackageInstalled(string $package_name): bool {
		$composer_json = file(\Base::instance()->PROJECT_BASE_DIR.'composer.json');
		return !!count(preg_grep("@".preg_quote($package_name)."@i", $composer_json));
	}
}
