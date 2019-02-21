## 前提
開発はXAMPP(7.2.12)で行いました。MySQLはver5.7です。
さくらのレンタルサーバーにも置いてます(http://bnbnk.sakura.ne.jp/hrh/login.php)。データベースの管理はphpMyAdminです。
スキルの証明としては粗が多いと思いますので、あくまで学習用という位置づけです。

## データベース概要
- データベース名：`bnbnk_hrh`
- データベースユーザー名：`bnbnk`
- パスワード：`bnk_pass`
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

	userId INT NOT NULL
	name VARCHAR(255) NOT NULL
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT
	title VARCHAR(255) NOT NULL
	maintext TEXT NOT NULL
	date DATETIME NOT NULL

## 今後作り込んで行きたいところ
1. メール認証の部分
	1. アカウントを作ったり、パスワードを変更させたりするところ。暗証番号を自動生成してメールで送り、次の画面で認証させるというのを作っている。大したものではないはずなので、近々にやり切りたい。
	2. セキュリティ周りはフレームワークを導入して行った方が今後はいいような気がする。APIとか勉強してもいいのかもしれない。
2. (上と関連して)セキュリティ周り
	1. XSS対策でhtmlspecialchars()を一応使っているが、内部の機序がわからないのでモヤモヤする。→Javascriptをやる。
	2. セキュリティ関連の本を読んでおく。頭の片隅に入れる程度に。
3. デザイン周りとか画像とか
	1. 大部分CSS3でやってしまったけど本当は画像とかテクスチャを使っていきたい。奥行きがない印象がひどい。
	2. 画像添付の書き込みの実装。
	3. 言わずもがなレスポンシブル化。
4. 検索窓とList
	1. 本来的にやりたい部分。
	2. 予め検索ワードを登録して置いて、いつでも簡単にそのワードが検索できるというやつにして「エゴサのしやすいSNS」を作っていければ。
5. サーバー関係
	1. 他にも何件かWebアプリケーションを作って慣れていきたいところ。
	2. ワケ分からないワードが多いのでとにかく慣れ。
***
## 下記はローカル環境下で制作していた時のターミナルのコピペ
###### MySQLの起動とデータベースの作成

	cd /Applications/XAMPP/bin;
	./mysql -u root;
	CREATE DATABASE bnbnk_hrh;
	USE bnbnk_hrh;

###### テーブルの作成

	CREATE TABLE users(userId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,name VARCHAR(255) NOT NULL,email VARCHAR(255) NOT NULL,password VARCHAR(255) NOT NULL,profile TEXT)DEFAULT CHARACTER SET=utf8;

	CREATE TABLE posts(userId INT NOT NULL, name VARCHAR(255) NOT NULL, id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,title VARCHAR(255) NOT NULL, maintext TEXT NOT NULL, date DATETIME NOT NULL)DEFAULT CHARACTER SET=utf8;

###### ユーザーの追加

	GRANT ALL ON bnbnk_hrh.* to 'bnbnk'@'localhost' IDENTIFIED BY 'bnk_pass';

###### 登録したユーザーで再ログイン(チェック)

	exit;
	./mysql -u bnbnk -p;
	bnk_pass
	USE bnbnk_hrh;
	SELECT * FROM users;

###### その他メモ書き

	DROP TABLE users;
	DROP DATABASE bnbnk_hrh;
	// 本番環境のホスト名：mysql1014.db.sakura.ne.jp
