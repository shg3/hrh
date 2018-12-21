ローカル環境で開発した際のデータベースについてメモします。

データベース名：hrh

データベースユーザー名：hrhuser

パスワード：password

文字コード：UTF-8

テーブル：

(1)users
ログインするユーザー情報を記録するテーブル
カラムは下記通り

id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
profile TEXT


(2)post
掲示板に書き込まれたテキストを記録するテーブル
カラムは下記通り

id INT NOT NULL,
name VARCHAR(255) NOT NULL,
postnum INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
title VARCHAR(255) NOT NULL,
maintext TEXT NOT NULL,
date DATETIME NOT NULL
