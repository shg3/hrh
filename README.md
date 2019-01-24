## 前提
ローカル環境で開発した際のデータベースについてメモします。
開発はXAMPP(7.2.12)で行いました。
MySQL Databaseはターミナルで設定し、
このメモの一番最後に制作時のターミナルのコマンドをコピペ用に残します。
スキルの証明としては粗が多いと思いますので、
プログラミングに対して苦手意識を持たないか、続けていけそうか、
ということを知るためにまず作ったもの、
と認識していただければと思います。

## データベース概要
- データベース名：`hrh`
- データベースユーザー名：`hrhuser`
- パスワード：`password`
- 文字コード：`UTF-8`

## テーブル1：`users`
ログインするユーザー情報を記録するテーブル

	userId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	profile TEXT

## テーブル2：`posts`
掲示板に書き込まれたテキストを記録するテーブル

	userId INT NOT NULL,
	name VARCHAR(255) NOT NULL,
	postId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	title VARCHAR(255) NOT NULL,
	maintext TEXT NOT NULL,
	date DATETIME NOT NULL

## mb_send_mailについて
'makeNewAccount.php'と'makeNewAccount_on.php'では
'mb_send_mail'を使ってメールを送信/受信し、
メール記載の暗証番号を入力しないと先に進めないようになっておりますが、
ローカルホストでの開発からまだ脱していないため、
メール送受信はまだ適いません。テスト用の暗証番号'119'を入力して進んでください。

***
## 下記ターミナルのコピペになります。
###### MySQLの起動とデータベースの作成

	cd /Applications/XAMPP/bin;
	./mysql -u root;
	CREATE DATABASE hrh;
	USE hrh;

###### テーブルの作成

	CREATE TABLE users(
	userId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	profile TEXT
	)DEFAULT CHARACTER SET=utf8;

	CREATE TABLE posts(
	userId INT NOT NULL,
	name VARCHAR(255) NOT NULL,
	postId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	title VARCHAR(255) NOT NULL,
	maintext TEXT NOT NULL,
	date DATETIME NOT NULL
	)DEFAULT CHARACTER SET=utf8;

###### ユーザーの追加

	GRANT ALL ON hrh.*to 'hrhuser'@'localhost' IDENTIFIED BY 'password';

###### 登録したユーザーで再ログイン(チェック)

	exit;
	./mysql -u hrhuser -p;
	password;
