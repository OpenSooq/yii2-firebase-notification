# Firebase Notifications Yii2

This extension will make send firebase notifications easy to do for the Yii2 framework.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisities

Yii2 application 
```
composer require yiisoft/yii2
```

### Installing

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist "opensooq/yii2-firebase-notifications": "dev-master"

```

or add

```
"opensooq/yii2-firebase-notifications": "dev-master"
```
to the require section of your composer.json file.

## Usage

```ruby
$service = new FirebaseNotifications('YOUR_KEY');

$service->sendNotification($tokens,$message);
```
you can clone the android build [here](https://github.com/Amr-alshroof/Fcm-Android),
and use it to test your code.


