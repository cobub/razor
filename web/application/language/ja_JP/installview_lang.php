<?php 
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */
$lang["installview_installheader"] 	=	 "Cobub Razor セットアップウィザード";
$lang["installview_logotitle"] 	=	 "モバイルアプリケーション統計解析システム";
$lang["installview_welcomestep"] 	=	 "ようこそ";

$lang["installview_checkheader"] 	=	 "システム確認";
$lang["installview_databaseheader"] 	=	 "データベース作成";
$lang["installview_websiteheader"] 	=	 "サイトと管理者の作成";
$lang["installview_finshheader"] 	=	 "完了";

$lang["installview_checkstep"] 	=	 "1.システムチェック";
$lang["installview_databasestep"] 	=	 "2.データベース作成";
$lang["installview_websitestep"] 	=	 "3.サイトと管理者の作成";
$lang["installview_finshstep"] 	=	 "4.完了";

$lang["installview_licensetitle"] 	=	 "使用許諾";
$lang["installview_licensecontent1"] 	=	 "Cobub Razorをインストールする前にこの使用許諾をお読みください。";

$lang["installview_licensecontent3"] 	="<p>Cobub RazorのパッケージはHighChartsの製品を含んでいます. The HighChartsの製品はオープンソースの製品ではありません、<br>しかし正しい条件下においては無料で使用できます。次のURLを見てください。 <a href='http://shop.highsoft.com/highcharts.html' target='_blank'>http://shop.highsoft.com/highcharts.html</a>.</p>";
$lang["installview_licenselink"] 	=	 "使用許諾を読む";
$lang["installview_nextstep"] 	=	 "次へ";
$lang["installview_installselectlanguage"] ="インストールを開始する";

$lang["installview_installstep"] 	=	 "インストール";
$lang["installview_acceptcontent"] 	=	 "Cobub Razorに付属のライセンスに同意します。";
$lang["installview_versionerror"] 	=	 "PHPのバージョンが古すぎます。PHPのバージョンをアップグレードしてください。";
$lang["installview_mysqlierror"] 	=	 "あなたの mysqli 開けない。";
$lang["installview_curlerror"] = "あなたの curl 開けない。";
$lang["installview_mbstringerror"] = "あなたの mb_string 開けない";
$lang["installview_writeerror"]	=	"このファイルは書込み権限がありません。ファイルのアクセス権限を追加してください。";
$lang["installview_companyname"] 	=	 "DEV.COBUB.COM";

//welcome info
$lang["installview_welcome"] 	=	 "ようこそ Cobub Razor へ!";
$lang["installview_welcomeintro"] 	=	 " はモバイルアプリ専用のデータ解析ソフトウェアです。";
$lang["installview_welcomedemand"] 	=	 "次の指示に従ってあなた自身のCobub Razor Systemをインストール、デプロイしてください。";
//check info
$lang["installview_check"] 	=	 "システム確認";
$lang["installview_checkversion"] 	=	 "PHPバージョン(>=5.2.6)：";
$lang["installview_checkexpand"] 	=	 "MySqli サポート：";
$lang["installview_checkcurl"] = "curl サポート：";
$lang["installview_checkmbstring"] = "mb_string サポート：";
$lang["installview_checkpermission"] 	=	 "ディレクトリへの書込権限:";
//database info
$lang["installview_datawarn"] 	=	 "Cobub Razorはパフォーマンスを考慮するなら2つのデータベースを作成することを強く推奨します。 1つは運用向け、もう一つはデータ保管用です。";
$lang["installview_datawarninfo"] 	=	 "もし2つのデータベースを作成しない場合には、運用向けとデータ保管用に同じ設定をしてください。";
$lang["installview_dataset"] 	=	 "データベース設定";
$lang["installview_dataserve"] 	=	 "データベースサーバー:";
$lang["installview_dataaccount"] 	=	 "データベースアカウント:";
$lang["installview_datapassword"] 	=	 "データベースパスワード:";
$lang["installview_dataname"] 	=	 "データベース名";
$lang["installview_datatablehead"] 	=	 "データベーステーブルプリフィックス:";
$lang["installview_datadepotset"] 	=	 "データ保管設定";
$lang["installview_datadepotserve"] 	=	 "データ保管用サーバー:";
$lang["installview_datadepotaccount"] 	=	 "データ保管用アカウント:";
$lang["installview_datadepotpwd"] 	=	 "データ保管用パスワード:";
$lang["installview_datadepotname"] 	=	 "データ保管用DB名:";
$lang["installview_datadepottablehead"] 	=	 "データ保管用テーブルプリフィックス:";
//user info
$lang["installview_userinfo"] 	=	 "サイトの指定と管理者作成";
$lang["installview_userurl"] 	=	 "サーバーURL:";
$lang["installview_userurlreminder"] 	=	"例: http://example.com/razor";
$lang["installview_userlanguage"] 	=	 "言語:";
$lang["installview_userchinese"] 	=	 "中国語";
$lang["installview_userenglish"] 	=	 "英語";
$lang["installview_timezones"] 	=	 "タイムゾーン選択:";
$lang["installview_usersupperaccount"] 	=	 "管理者ユーザー名:";
$lang["installview_userpwd"] 	=	 "パスワード:";
$lang["installview_userconfirmpwd"]	=	"パスワード（確認）:";
$lang["installview_useremail"]	=	"メールアドレス:";
//finsh info
$lang["installview_finshinform"] 	=	 "インストール完了!";
$lang["installview_finshinfo"] 	=	 "Cobub Razorのインストールは完了しました。サイトにログインするためにリンクをクリックしてください。";
$lang["installview_finshlogin"] 	=	 "ログイン";
// formverfication
$lang["installview_verficationip"] 	=	 "データベースサーバーアドレス ";
$lang["installview_verficationdbname"] 	=	 "データベース名 ";
$lang["installview_verficationusername"] 	=	 "データベースアカウント ";
$lang["installview_verficationpassword"] 	=	 "データベースパスワード ";
$lang["installview_verficationtablehead"] 	=	 "データベーステーブルプリフィックス ";
$lang["installview_verficationconnecterror"] 	=	 "データベースに接続できません。 ";
$lang["installview_verficationdepotip"] 	=	 "データ保管用サーバーアドレス";
$lang["installview_verficationdepotdbname"] 	=	 "データ保管用DB名 ";
$lang["installview_verficationdepotusername"] 	=	 "データ保管用DBアカウント ";
$lang["installview_verficationdepotpassword"] 	=	 "データ保管用パスワード ";
$lang["installview_verficationdepottablehead"] 	=	 "データ保管用テーブルプリフィックス";
$lang["installview_verficationdepotconnecterror"] 	=	 "データ保管用DBに接続できません ";
$lang["installview_verficationsiteurl"] 	=	 "Webサイトアドレス";
$lang["installview_verficationsuperuser"] 	=	 "スーパーユーザーアカウント";
$lang["installview_verficationpwd"] 	=	 "パスワード";
$lang["installview_verficationverifypwd"] 	=	 "パスワード（確認）";
$lang["installview_verficationemail"] 	=	 "メールアドレス";
$lang["installview_verficationcreatefailed"] 	=	 "データベースの作成が失敗しました!";

$lang["installview_innodberror"] 	=	 "データベースのバージョンをアップグレードしてください!";
$lang["installview_innodberrordw"] 	=	 "データ保管用データベースのバージョンをアップグレードしてください!";
$lang["installview_innodbclose"] 	=	 "InnoDBを開始するためにデータベースの設定ファイルを修正してください!";
$lang["installview_innodbclosedw"] 	=	 "InnoDBを開始するためにデータ保管用データベースの設定ファイルを修正してください!";

$lang["installview_noexistdata"]	=	"入力されたデータベースは有りません。すでに存在するデータベースを指定してください。";
$lang["installview_noexistdatadw"]	=	"入力されたデータ保管用データベースは有りません。すでに存在するデータ保管用データベースを指定してください。";

$lang["installview_finshviewtip"]	="プロンプト：インストールが完了したら,<a href='http://dev.cobub.com/zh/docs/cobub-razor/auto-archiving/' target='_blank'>http://dev.cobub.com/docs/cobub-razor/auto-archiving/</a>";

$lang['UMSINSTALL_NEWSPAPER']='新聞や雑誌';
$lang['UMSINSTALL_SOCIAL']='ソーシャル';
$lang['UMSINSTALL_BUSINESS']='ビジネス';
$lang['UMSINSTALL_FINANCIALBUSINESS']='金融の';
$lang['UMSINSTALL_REFERENCE']='リファレンス';
$lang['UMSINSTALL_NAVIGATION']='ナビゲーション';
$lang['UMSINSTALL_INSTRUMENT']='ツール';
$lang['UMSINSTALL_HEALTHFITNESS']='健康とフィットネス';
$lang['UMSINSTALL_EDUCATION']='教育';
$lang['UMSINSTALL_TRAVEL']='旅行';
$lang['UMSINSTALL_PHOTOVIDEO']='写真とビデオ';
$lang['UMSINSTALL_LIFE']='人生';
$lang['UMSINSTALL_SPORTS']='スポーツの';
$lang['UMSINSTALL_WEATHER']='天気';
$lang['UMSINSTALL_BOOKS']='図書';
$lang['UMSINSTALL_EFFICIENCY']='効率性';
$lang['UMSINSTALL_NEWS']='ニュース';
$lang['UMSINSTALL_MUSIC']='音楽';
$lang['UMSINSTALL_MEDICAL']='メディカル';
$lang['UMSINSTALL_ENTERTAINMENT']='エンターテインメント';
$lang['UMSINSTALL_GAME']='ゲーム';
$lang['UMSINSTALLC_SYSMANAGER']='ユーザー管理';
$lang['UMSINSTALLC_MYAPPS']='マイアプリ';
$lang['UMSINSTALLC_ERRORDEVICE']='エラーデバイスの統計情報';
$lang['UMSINSTALLC_DASHBOARD']='アプリ統計量概要';
$lang['UMSINSTALLC_USERS']='ユーザー';
$lang['UMSINSTALLC_AUTOUPDATE']='自動更新';
$lang['UMSINSTALLC_CHANNEL']='チャンネル';
$lang['UMSINSTALLC_DEVICE']='デバイス';
$lang['UMSINSTALLC_EVENTMANAGEMENT']='イベント管理';
$lang['UMSINSTALLC_SENDPOLICY']='ポリシー送信';
$lang['UMSINSTALLC_OPERATORSTATISTICS']='オペレーター統計量';
$lang['UMSINSTALLC_OSSTATISTICS']='OSバージョン';
$lang['UMSINSTALLC_PROFILE']='プロフィール';
$lang['UMSINSTALLC_RESOLUTIONSTATISTICS']='解像度統計量';
$lang['UMSINSTALLC_REEQUENCYSTATISTICS']='周期的使用統計量';
$lang['UMSINSTALLC_USAGEDURATION']='使用継続時間統計量';
$lang['UMSINSTALLC_ERRORLOG']='エラーログ';
$lang['UMSINSTALLC_EVENTLIST']='イベント';
$lang['UMSINSTALLC_CHANNELSTATISTICS']='チャネル統計量';
$lang['UMSINSTALLC_GEOGRAPHYSTATICS']='地域等軽量';
$lang['UMSINSTALLC_ERRORONOS']='OS上のエラー数';
$lang['UMSINSTALLC_VERSIONSTATISTICS']='バージョン統計量';
$lang['UMSINSTALLC_APPS']='アプリケーション';
$lang['UMSINSTALLC_RETENTION']='ユーザー滞留';
$lang['UMSINSTALLC_PAGEVIEWSANALY']='ページ訪問';
$lang['UMSINSTALLC_NETWORKINGSTATISTIC']='ネットワーク統計量';
$lang['UMSINSTALLC_FUNNELMODEL']='ファンネル数';