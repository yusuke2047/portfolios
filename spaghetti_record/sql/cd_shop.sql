-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 
-- サーバのバージョン： 10.4.8-MariaDB
-- PHP のバージョン: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `cd_shop`
--
CREATE DATABASE IF NOT EXISTS `cd_shop` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cd_shop`;

-- --------------------------------------------------------

--
-- テーブルの構造 `diaries`
--

CREATE TABLE `diaries` (
  `id` int(11) NOT NULL,
  `text` varchar(200) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `diaries`
--

INSERT INTO `diaries` (`id`, `text`, `year`, `month`, `day`, `user_id`) VALUES
(35, '【祝】日記機能がとりあえず完成！！\r\n今日から制作するにあたって、思ったこと感じたことなどを書いていこうと思う。', 2020, 4, 28, 1),
(42, 'あとやりたいことは、レスポンシブ化とオブジェクト指向導入。今日は、メインページのレスポンシブ化に取りかかった。が、あまり進まず。ハンバーガメニュをちょっと作った。', 2020, 4, 30, 1),
(43, 'shopページのレスポンシブ化、なんとか終了！ほかのページはめんどいのでやらない(苦笑)あとやるべきことは、オブジェクト指向の導入とコード全体の見直し。明日から、ゴールデンウィーク。当アプリ制作は、持ち帰らなくても間に合いそうだ。', 2020, 5, 1, 1),
(44, '久々の学校。アプリ制作の時間かと思いきや、応募書類、面接などに関する講義だった。隙間時間で、GW中に思いついた改良案をひとつずつこなした。', 2020, 5, 7, 1),
(47, '午前は、求人応募、面接についての座学。午後は模擬面接。模擬面接は上手く話せずボロボロだった。最低限、志望動機、今までやってきたこと、これからやりたいことはきちんと話せるよう準備していこう！', 2020, 5, 8, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `histories`
--

CREATE TABLE `histories` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `histories`
--

INSERT INTO `histories` (`id`, `item_id`, `user_id`, `date`) VALUES
(3, 1, 1, '2020-04-24 02:16:25'),
(4, 2, 1, '2020-04-24 02:16:25'),
(11, 3, 1, '2020-04-28 00:57:55'),
(15, 10, 1, '2020-04-28 09:45:21'),
(16, 11, 1, '2020-04-28 09:45:21'),
(24, 12, 1, '2020-04-29 12:00:20'),
(29, 4, 1, '2020-04-29 13:13:37'),
(30, 5, 1, '2020-04-29 13:13:37'),
(31, 7, 1, '2020-04-30 01:41:58'),
(32, 13, 1, '2020-05-07 06:21:21'),
(33, 20, 1, '2020-05-07 06:21:21'),
(56, 43, 1, '2020-05-10 01:53:09'),
(57, 35, 1, '2020-05-10 01:53:09'),
(63, 31, 1, '2020-05-17 04:21:11'),
(64, 46, 1, '2020-05-17 04:21:11'),
(65, 30, 1, '2020-05-17 04:21:11');

-- --------------------------------------------------------

--
-- テーブルの構造 `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `artist` varchar(50) NOT NULL,
  `price` int(6) NOT NULL,
  `image` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `movie` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `items`
--

INSERT INTO `items` (`id`, `title`, `artist`, `price`, `image`, `description`, `keyword`, `movie`) VALUES
(1, 'Dead Man Walking', 'ARKANGEL', 2680, 'images/item1.jpg', 'ARKANGELの廃盤になっている1stアルバムがGSRからLP盤にて復活！2018のThis Is Hardcoreにも出演しその盛り上がりは凄かった様です！暴れているのがSANCTIONのメンバーであったりYEAR OF THE KNIFEのメンバーだったりアメリカの現在のバンドにおいてこのARKANGELとKIACKBACKはカリスマでしかない！EDGE METAL / FURYEDGEを語るには欠かす事のできない究極のアイテムがこれですね！ARKANGELの1stアルバムでオリジナルは1999年Goodlifeのリリース！名盤中の名盤で持ってない人はいないだろうがとにかくALL OUT WAR, KICKBACKのそれまで表現してきたサウンドによりSLAYERLISHなテイストを加えてオリジナルFURY EDGE METALを確立した作品です！', 'arkangel,dead man walking,fury edge,edge metal', 'https://www.youtube.com/embed/4lFv-Sk9qpM'),
(2, 'War', 'SENTENCE', 2180, 'images/item2.jpg', '再入荷！昨年のBURNING SEASONからのMCDが好評だったITALIAN FURY EDGE \"SENTENCE\"の名盤9曲入りセカンドアルバム！とにかくサウンドプロダクションの申し分ない事、相当ヘビーで完全にFURY EDGEの金字塔作品をARKANGEL \"DEAD MAN WALKING\"に続いて作り上げたといっても過言ではないリリース！REPRISAL, PURIFICATION, ARKANGEL, ONFALLこの辺りが好きであれば間違いなくたまらない作品！NASTYなんかが好きな人にも十分アピールできるMOSHYな作品です！解散が残念、。。', 'sentence,war,fury edge,edge metal', 'https://www.youtube.com/embed/S3SIXuZAnRk'),
(3, 'Banging The Drums Of War', 'PURIFICATION', 2180, 'images/item3.jpg', 'イタリア出身最強MILITANT VEGAN FURY EDGEバンドPURIFICATIONの1stアルバム！ザクザク単音にて切り刻むFURY EDGEをメインにTRIBAL MILITANTの要素も十二分に盛り込んだ楽曲！FURY EDGE+ドラマティックな路線を合体させ極悪テイストも入れた変態MILITANTサウンドと形容したいMILITANTすぎるITALY NEW SCHOOLを堪能できます。このアルバムはドラマティックな展開を取り入れたりEARTH CRISISのボーカルをFEATしたりと全て詰め込んだ作品になっています！', 'purification,banging the drums of war,fury edge,edge metal,新着商品', 'https://www.youtube.com/embed/exp7uVTN6t0'),
(4, 'Antigone', 'HEAVEN SHALL BURN', 2180, 'images/item4.jpg', '再入荷！HEAVEN SHALL BURNの2004年リリースの3rd Album！96年にドイツで結成し、現在ではCALIBAN、MAROONと共にジャーマン・メタルコアとしてヨーロッパを代表するバンドとして知られるHEAVEN SHALL BURN。CENTURY MEDIA RECORDSに移籍してからの本作「ANTIGONE」は、今までのデスメタル+ハードコアなスタイルの究極形で、過去最高の凶悪メタルコアに仕上がっている。MAROON, CALIBAN, NARZISS, DEADLOCK辺り好きな人は必須！', 'antigone,heaven shall burn,fury edge,edge metal,新着商品', 'https://www.youtube.com/embed/dNMIzC3qnVw'),
(5, 'Ad Vitam Rediti', 'SENTENCE', 1980, 'images/item5.jpg', '再入荷！EUROPE最強のFURY EDGE MASTERのSENTENCEの06年にリリースされていた希少なラスト作品！！Burning Seasonsからリリースでかなりやばいです！Goodlifeからリリースされていた頃のFURY EDGEをしっかりkeepしつつ少しEmotionalなリフを組み込んだ彼らのセンスだからこそ可能なサウンド！音質もかなりよく1st ALBUMのSWORD OF DOOMを再録している所あたりもかなりぐっと来ます。', 'ad vitam rediti,sentence,fury edge,edge metal', 'https://www.youtube.com/embed/N22mmaBsFuY'),
(6, 'Martyr', 'MORNING AGAIN', 2180, 'images/item6.jpg', '廃盤リリース！14年BLOODAXE FESTIVALに奇跡の来日を果たしたMORNING AGAINのベストタイトルを奇跡的に発掘！！ボーカルがDamienからKevinになり最高のリリースとなった2ndアルバム！来日もこの作品からがメインのFESでのセットとなると思われます！是非予習してくるべし！！ついにGoodlifeでももう在庫がなくなってしまいMORNING AGAINの音源も買う最後のチャンスになってしまっています。1997年GOODLIFEからリリースされた伝説の名盤MORNING AGAINの2ndアルバムで7曲入り！NEW SCHOOL HxCを知るにはこれを必ず聞かなければいけないというべき教科書みたいなもので叙情的な部分と攻撃的な単音のマッチングは当時完全にオリジナルともいえるサウンドで幾千ものバンドに影響を与えたであろう作品!', 'martyr,morning again,fury edge,edge metal', 'https://www.youtube.com/embed/mxBlytCn99Y'),
(7, 'Arkangel Is Your Enemy', 'ARKANGEL', 2280, 'images/item7.jpg', 'ARKANGEL IS BACK！遂にEDGE METALの帝王ARKANGELがニューアルバムをドロップ！9曲入りでGSRからのリリースとなった久しぶりの3rdフルレングスアルバムで全NEW SCHOOL / EDGE METALファンは必聴の音源！今や説明不要なベルギー出身EDGE METALの元祖的なバンドの一つでもありSLAYERの影響を落とし込み殺人的な鋭いギターリフとハイトーンのボーカル、そしてBRUTAL HEAVYなMOSH PARTの融合はまさにオリジナル！FURY EDGEスタイルを生み出したバンド。本作は変わる事の無いキレのよいEDGE METALサウンドを主体とし、さらに攻撃的になったボーカルの声質とARKANGELはARKANGEL以外の何物でもない事を証明した作品。前作に見られた若干ドラマティックに展開するEMOTIONALな部分は近作では陰りを見せて元々のARKANGELの本質でもある攻撃的な部分を前面に押しだした作品で、FURY EDGEサウンドに彼らのもつEVILな雰囲気を増長させタイトルも含めAGRESSIVEかつHATEな作品に仕上がっています！', 'arkangel is your enemy,arkangel,fury edge,edge metal', 'https://www.youtube.com/embed/lQCOVajUvRs'),
(8, 'Boundless Human Stupidity', 'REPRISAL', 2080, 'images/item8.jpg', '再入荷！既に廃盤になってしまって入手不能なITALY出身のVEGAN SxEバンドREPRISALが00年にリリースしていた1stアルバムがデラックスエディションとして再リリース！限定のリリースでスペシャルデジパックにボーナス二曲入りのマニアは狂喜する作品！！再マスタリングされてかなり音質も変わった印象あり！ヘビーさが増しています！とにかくFURY EDGEの金字塔とも言われた名盤で文句なしのBRUTAL EDGE METALサウンド！極上のサウンドクオリティに単音でひたすら切り刻むように展開していく冷たいリフの緊張感は極限に近いです。重苦しいダークな雰囲気を持ちつつBRUTALなMOSH PARTは確実にどの曲にも組み込まれておりARKANGEL, SENTENCE, PRIMAL AGE, PURGATORY, GERILJA, CHILDREN OF GAIA等好きであれば必須です！オリジナル盤もっているいる人でもこれは絶対に持っていたい！', 'boundless human stupidity,reprisal,fury edge,edge metal', 'https://www.youtube.com/embed/L4v-XS43wsE'),
(9, 'To Celebrate The Forlorn Seasons ', 'STATECRAFT', 1380, 'images/item9.jpg', '00年にBELGIUMのGOODLIFEからリリースされたSTATECRAFTのフルアルバムで90年代後半のNEW SCHOOLバンドのリリースではマストな作品でMORNING AGAIN, UPHEAVAL, LIAR, POISON THE WELL, ARKANGEL, DEAD BLUE SKY, LASHOUTなんかが好きな人に聞いてもらいたいです！叙情性とブルータルさを上手くミックスした鋭いサウンドにスケールのでかいサウンドが持ち味です！', 'to celebrate the forlorn seasons,statecraft,fury edge,edge metal', 'https://www.youtube.com/embed/RsBvfE3h2QE'),
(10, 'Under The Knife', 'HATEBREED', 1280, 'images/item10.jpg', '今や説明不要のMETAL/HARD COREバンドHATEBREEDのクラシックリリース！1st音源でSmorgasbord Recordsから7インチレコードで97年リリースされた音源！！JASTA 14とPUSHBUTTON WARFAREのメンバーが結成したという肩書きで当初注目されていたが、誰が聞いても極悪BRUTALなMOSH COREサウンドは今のHATEBREEDよりも粗々しくてBRUTALです。そしてファストビートからの強引な落としはシンプルがゆえの豪快さで今のBEATDOWN/MOSH COREの定義を作ったのは彼らといっても間違いがありません！MOSHPARTのために全てその前のファストパートがあるというDANCE/MOSHの為にある一枚！！', 'Under The Knife,hatebreed,tuff guy,beatdown', 'https://www.youtube.com/embed/girLpZSsVcY'),
(11, 'Satisfaction Is The Death Of Desire', 'HATEBREED', 1980, 'images/item11.jpg', '今や説明不要の人気バンドに登りつめたHATEBREEDの超名盤1stフルアルバムでVICTORYからのリリース14曲入り！ファスト&メタリック、そして豪快すぎる落とし込みは今のHCシーンに大きな影響を与えたバイブル的な作品ですべてのBRUTAL HC/METAL好きにささげられる作品！', 'satisfaction is the death of desire,hatebreed,tuff guy,beatdown', 'https://www.youtube.com/embed/TlUOJ_NbrhU'),
(12, 'From The Dead End Blocks Where Life Means Nothing', 'SHATTERED REALM', 2180, 'images/item12.jpg', 'NJHC！BEATDOWN HARDCOREバンドSHATTERED REALMによる2ndアルバム！既に廃盤になっておりEURO盤を少量発見！Alveran Recordsからのリリース！SLAYERLISHなリフに以前よりも更にブルータルに落としていくサウンドはREPERCUSSION, SECOND TO NONE, HATEBREED, ALL OUT WAR, IRATEからBULLDOZE等のBEATDOWN/NJHCが好きな人は必聴の一枚！', 'from the dead end blocks where life means nothing,shattered realm,tuff guy,beatdown', 'https://www.youtube.com/embed/hmr3n3vVCcM'),
(13, 'A Stroke Of Genius', 'AT ONE STROKE', 2200, 'images/item13.jpg', '東京発重鎮ともいえるメタリックハードコアバンドAT ONE STROKEによる新作3rdアルバムがImperium Recordingsからリリース！16年振りとなる本作！90年代の半ばから東京ハードコアのシーンを支える存在にてBRUTALでメタリック、そしてデスメタルの要素も早くからミックスさせた強烈なスタイルにてファンを魅了させ続けたバンド。本作もまさに金太郎飴というべくこれまでと何ら変わる事のないヘビーで落とし所多数の作風になっており原点を突き詰めた作品となっています！', 'a stroke of genius,at one stroke,tuff guy,beatdown,新着商品', 'https://www.youtube.com/embed/y0Dc5-PCgp4'),
(14, 'Antagonist', 'MAROON', 2180, 'images/item14.jpg', '今や貴重となっているドイツ産MILITANT VEGAN EDGE METALバンドMAROONの1stアルバム！！全米・全欧にMORNING AGAINの再来と知らしめたMAROONの伝説的な作品\"ANTAGONIST”だがここまでデフォルメできるほどMORNING AGAINのMartyrを彷彿させる作品があっただろうか？とにかく黄金と呼ぶにしかない00年代初期のNEW SCHOOLを聞かせてくれます。', 'antagonist,maroon,fury edge,edge metal', 'https://www.youtube.com/embed/T0mSr4F5rEE'),
(15, 'Sanctuarium', 'CONFRONTO', 2180, 'images/item15.jpg', 'ブラジル出身VEGAN SxE MILITANT EDGE METALの帝王CONFRONTOの2ndアルバムをドロップ！全10曲入りで78 LIFE RECORDINGS/Reality Recordsからのリリース！いや、、これは名盤です。南米というかヨーロッパのバンドにしか聞こえないクオリティの高い様式EDGE METALに仕上がっています！最高のサウンドプロダクションにいまだ単音で切り刻むFURY EDGEスタイルの楽曲に敷き詰められたバスドラ、そして叙情的に展開する楽曲はMAROONの\"Antagonist\"の現代版の様な楽曲で素晴らしいです。', 'sanctuarium,confronto,fury edge,edge metal,新着商品', 'https://www.youtube.com/embed/E4A911K6j-M'),
(16, 'Prayers Upon Deaf Ears', 'ARKANGEL', 2880, 'images/item16.jpg', '待望の12インチ盤がプレス！！現在手に入手の出来ないベルギー産FURY EDGE/EDGE METALの帝王ARKANGELによる1998年の1st音源が完全再現LPになって帰ってきた！一度ピクチャー盤10インチでリリースされていましたが、ジャケありのLP盤はさすがに見た目も凄い！ARKANGELの中でも最強音源といえるのがこれ！Dead Man Walkingがリリースされる前に出ていた音源で唯一バンドがMILITANT VEGAN SxEだった頃にリリースされた究極の作品！VEGAN EDGE METALの象徴といえるアートワーク、楽曲、リリックすべてにおいて世界観を作り上げた作品の一つですね！', 'prayers upon deaf ears,arkangel,fury edge,edge metal', 'https://www.youtube.com/embed/YCoGfDW4bp0'),
(17, 'Hope You Die By Overdose', 'ARKANGEL', 2680, 'images/item17.jpg', '待望のARKANGELのLPシリーズの第3弾が2020年GSRからリリース！ベルギー産EDGE METAL　KINGS！ARKANGELによる自主リリース2ndアルバム！Dead Man Walkingに続く2ndアルバムがPrivate Hellからリリース！SLAYERの影響をそのままに出すFURY EDGEサウンドは一切変わることのない破壊力抜群のNEW SCHOOLでこの手の音を出すバンドとは確実に一線を画すサウンド！！本作で初めて微妙にメロディックなリフを落としてくる辺り一歩先を行く感じがします！しかし基本はまったく変わらず単音で切り刻むPURE FURY EDGEスタイル！前作が好きだった貴方にもヒットする作品になるでしょう。', 'hope you die by overdose,arkangel,fury edge,edge metal', 'https://www.youtube.com/embed/Q3g3ES8R2zo'),
(18, 'Towards Last Horizon Special Edition', 'FROM THE DYING SKY', 1980, 'images/item18.jpg', 'BURNING SEASONから再発でリリースされるも即SOLDになってしまい今や絶対に手に入らないITALY VSEバンドFROM THE DYING SKYのMCDで6曲入りの超名盤！Burning SeasonのPatがリミテッドでCDRにて再プレスした！との連絡を受け即刻オーダー。今はTHE SECRETとして活動をしている彼らだがこちらのバンドの方が好きな人もかなりいるのではないだろうか？EARTH CRISIS, CONTEMPTからの影響を公言しFURY EDGEとTRIBAL MILITANTを完璧にブレンド、攻撃的、シャープでテクニカルながら泣きをたっぷり盛り込んだメロデスニュースクールは文句無しです。', 'towards last horizon special edition,from the dying sky,fury edge,edge metal', 'https://www.youtube.com/embed/7jlqNfuvqyI'),
(19, 'Last Leaves Of A Poisoned Tree', 'PURIFIED IN BLOOD', 1880, 'images/item19.jpg', 'ノルウェー出身VEGAN EDGE METALバンドPIBの1stアルバムでNEW EDENからのリリース！PURIFICATIONの影響下にあるPURE FURY EDGEサウンドをメインにメロデス、EDGE METALの叙情性もミックスさせた楽曲が素晴らしい！ボーカルもタフな感じでモッシーさも音質もかなり良いです！VEGAN NEW SCHOOLの枠に200%カテゴライズをおけるようなMILITANTすぎるサウンドがオススメです！', 'last leaves of a poisoned tree,purified in blood,fury edge,edge metal', 'https://www.youtube.com/embed/1m1JI7q_exY'),
(20, 'All I Want is...', 'AT ONE STROKE', 2200, 'images/item20.jpg', '東京発重鎮ともいえるメタリックハードコアバンドAT ONE STROKEによる新作3rdアルバムの発売に伴い2ndアルバムが限定で再発！2003年にリリースしていた音源で、今では廃盤になっておりレア値もついている程！東海岸EAST COAST ASSAULTなメタリックハードコアの図太さ、そしてデスメタリックなギターリフやドラム等もミックスさせ1stアルバムの頃よりも更にブルータリティを追求させた作品！', 'all i want is ...,at one stroke,tuff guy,beatdown', 'https://www.youtube.com/embed/GTwU8mY_bPc'),
(21, 'Dominicon', 'SHOCKWAVE', 2080, 'images/item21.jpg', '1999年にGOODLIFEからリリースされた武装集団SHOCKWAVEの1st 7インチ\"Warpath\"に続くMCD+ボーナス（1st 7inchの名曲, DEMO音源も収録）全9曲入り！いわずと知れたPAはERIE発の極悪バンドでDISCIPLEのメンバーを含む\"PA ALL STA\"的な伝統PAHCサウンドはCHUGA CHUGA MOSH COREの原点ともいうべき重厚かつミドル→ダウンしてくる展開にトリプルボーカルの畳み掛ける掛け合いボーカルとPAHC, NYHCそしてSURACUSEのHxCが好きな人にはたまらない逸材とも言えた名盤です！', 'dominicon,shockwave,hardcore,moshcore', 'https://www.youtube.com/embed/QXzvhQ7xb5E'),
(22, 'Autohate', 'SHOCKWAVE', 2680, 'images/item22.jpg', 'レアな2ndアルバムをVinylにて少量発見！2000年にGoodlife RecordingsからリリースされたPA産覆面TUFFGUY MOSHCORE\"SHOCKWAVE\"による2ndアルバム！既に廃盤音源にて名盤！PAはERIE発の極悪バンドでCHUGA CHUGA MOSH COREの原点ともいうべき重厚かつミドル→ダウンしてくる展開にトリプルボーカルの畳み掛ける掛け合いボーカルとPAHC, NYHCそしてSURACUSEのHxCが好きな人にはたまらない逸材とも言えた名盤です！', 'autohate,shockwave,hardcore,moshcore', 'https://www.youtube.com/embed/giCk_Y4RB4E'),
(23, 'A Torch To Pierce The Night', 'PURIFICATION', 2180, 'images/item23.jpg', 'イタリア出身MILITANT VEGAN FURY EDGEバンドPURIFICATION完全復活！信じられない新作アルバムをBastardizedからリリース！！末恐ろしい90s ROMA SxE LEGIONの伝説！遂にやってくれました。REPRISALと共に厳格さで1,2を争うイタリア産VEGAN FURY EDGEバンド！その最新作は96-2001時代の彼らに戻っています・・サウンドプロダクション、ギターのリフから全てMILITANT FURY EDGE路線を貫く恐ろしい内容。いきなり初期の名曲Legionの再録から幕を開ける信じられないアルバムの曲構成がリスナーを刺激します。前回の編集版に収録されていた新曲も再録されており、まさにPURIFICATIONというITALY MILITANT VSEサウンド堪能出来ます！！ジャケはSHOCKWAVEやFACEDOWN系リリースでも有名なDave Quiggle！', 'a torch to pierce the night,purification,fury edge,edge metal,新着商品', 'https://www.youtube.com/embed/EJlvVdWelKE'),
(24, 'S/T', 'FIRST FIGHT DOWN', 2680, 'images/item24.jpg', '特上レア盤！PURE FURY EDGE！オランダ出身NO.1 FURY EDGEバンドFIRST FIGHT DOWNの1stアルバムでCrossfire Cultからの08年リリースオリジナル盤1stアルバム全8曲入り！REPRISAL, SENTENCE, FROM THE DYING SKY, UNDYING, HEAVEN SHALL BURNからの影響を公言していたバンドでそのサウンドはメチャメチャFURY EDGE！以前に二枚のMCDをリリースしてからの本作が1stアルバム。初期の作品はSENTENCE/ARKANGELのコピーか！と言うほど直系なサウンドだったが、ここにきてより音質も向上してレベルアップを見せている。あくまで単音とシャープなFURYサウンドにこだわった完璧FURY EDGEを堪能できる作品になっています！ボーカルの高音ボイスがもろにFROM THE DYING SKY/HEAVEN SHALL BURNあたりに近くて単音で切り刻むヘビーでシャープなバック隊とのマッチングも素晴らしいです！とにかくこの機会に是非とも買い逃しのない様に！', 's/t,first fight down,new school', 'https://www.youtube.com/embed/6jAjEdjvHmY'),
(25, 'The Light To Purify Delux Reissue', 'PRIMAL AGE', 2380, 'images/item25.jpg', '再入荷！20周年記念盤として廃盤のPRIMAL AGEの歴史的名盤1stが豪華盤としてLP化！！再発されるも即完売となりまた廃盤となってしまったFRENCH MILITANT FURY EGDEバンドのPRIMAL AGEの1stがBound By Modern Ages RecordsからLPにてrelease！97年結成からのデビュー作としてリリースされたのがこの音源！完璧にARKANGEL, SENTENCE, REPRISALに勝るとも劣らないBRUTALすぎるFURY EDGEは微塵のメロディすら感じないPERFECTなEDGE METALでARKANGELのDead Man Walkingに並ぶ名盤音源の一つと言えるでしょう！ズンズン落とすモッシーな要素もありつつあくまで90\'s FURY EDGEのど真ん中を行く作品！メンバーはABSONEでもプレイしています！', 'the light to purify delux reissue,primal age,new school,新着商品', 'https://www.youtube.com/embed/3B9yAo0Qq1o'),
(26, 'Ai Mageddon', 'APOCALYPSE TRIBE', 2880, 'images/item26.jpg', 'これは熱い！EARTH CRISIS, SANTA SANGRE, FREYA, BORROWED TIME等のメンバーによるハードコアバンドAPOCALYPSE TRIBEの1stアルバムがIndecision Recordsからリリース！VoはEARTH CRISISのKarl！！17年にDEATH MARCHとのスプリットを経てまさかのアルバムとは！これは最高なハードコアパンクに仕上がっています！ヘビーでソリッドなファストパートはまさにSYRACUSE HARDCOREそのもの！そしてカールのシャウト、メロディを混ぜ込んだ巧みなボーカルスタイル、歌詞もいうまでも無いメッセージ性が100%伝わるRevolutionallyなサウンドにてOLD SCHOOL PUNK HARDCOREをSYRACUSEというフィルターを十分に通して放つ作品！哀愁あるギターの泣きとかも入っていてかなり聞き込める内容です！', 'ai mageddon,apocalypse tribe,new school', 'https://www.youtube.com/embed/cXi97Hgbdp8'),
(27, 'Through The Looking Grass', 'AFTeRSHOCK', 2480, 'images/item27.jpg', 'MA産90年代EVIL NEE SCHOOLバンドAFTeRSHOCKの今や入手困難2ndアルバム！Goodlifeからリリースされていた音源！KILLSWITCH ENGAGEのメンバーが昔やっていたバンド！叙情性、攻撃性を同居させたメタリックでブルータルな楽曲はいまだに色あせる事のないサウンド！これぞマサチューセッツなメタリックでEVILな楽曲でモッシーさも半端ないです！世界観を備えた新作で当時話題性も抜群過ぎた歴史的名盤！要所で奏でる叙情性もスウェディッシュメロデスからインスパイアされたギターリフでメタルコア好きにもマスト！', 'through the looking grass,aftershock,newschool', 'https://www.youtube.com/embed/WlER2c51MYE'),
(28, 'Out Of Darkness', 'BIRTHRIGHT', 1380, 'images/item28.jpg', 'これは熱い7\' Vinylで初入荷！CATALYSTのオーナーそして現RISENとして活動しているKURT率いるBIRTHRIGHTはEARTH CRISISに続いてもっとも重要な影響力を及ぼしていた90年代のバンドの一つで今だ多くの支持者を得ている。ABSENCEも昨年のBLOODAXE FESTIVALでEARTH CRISIS/BIRTHRIGHTのカバーをしていたというのもうなずける影響力の大きさだが彼らのGOODLIFEからリリースされた新曲とライブ音源も含む8曲入りのCD、サウンドは確実にEARTH CRISIS, BIRTHRITE, FALLINGDOWN, ADVANCE, EXTINCTION等好きであれば確実に気に入るであろうPURE VEGAN NEW SCHOOLサウンドで持ってない人はすぐにGETしてください！', 'out of darkness,birthright,new school', 'https://www.youtube.com/embed/vKKCii9Xk0k'),
(29, 'Firestorm', 'EARTH CRISIS', 1480, 'images/item29.jpg', '希少CD盤！！NYはSYRACUSE出身VEGAN SxEバンドEARTH CRISISの歴史的名盤2nd SINGLEがこれ！93年にレコーディングされリリースされている作品。All Out Warで衝撃のデビューを果たしたExCの次なる作品は、当時あまりにもシーンに衝撃を与える内容だった。とにかくメタリックでザクザクしたギターをメインに冷たく機械の様に刻み続けるミリタントな展開、それまでのMID-NEW SCHOOLバンドの派生からも、明らかに違うNEW SCHOOLの幕開け的な内容で、ミッド・スロウテンポ主体のザクザクしたギターと思いリズム、そして泣きを盛り込んだドラマティックな展開がまた素晴らしいです。SxE HARDCORE, 90\'s, SYRACUSE等色々なキーワードを生むこととなった名盤リリース！！', 'firestorm,earth crisis,new school', 'https://www.youtube.com/embed/dlwYzCQfvuw'),
(30, 'It\'s Getting Tougher To Say The Right Things', 'UNBROKEN', 2280, 'images/item30.jpg', '希少なCD盤をデッドストックで発掘！CD盤は熱い！ハードコアというジャンルにおける中で最も影響を大きく与えているバンドは数多くいるがその中の一つに添えたいサンディエゴのNEWSCHOOL HCバンドといえばUNBROKENでしょう。その彼らの03年にINDECISIONからリリースしていた92年から95年の間にリリースされていたアルバム以外に収録されていたコンピ、EP等の名音源を一つにまとめた作品で全11曲入り！コンピや、CDEP等の楽曲は通常そこまで重要視されない事もあるが彼らにとってはとっても重要で、アルバムでは聞けない名曲がたんまりあるのは言わずと知れています。End, Absentee Debate, Fall On Proverbその他、鳥肌物の楽曲全て収録されていて、二枚のアルバムを一つにしているThe Death Of True Spiritとこの一枚を持っていればすべてのUNBROKENを体感できます！', 'it\'s getting tougher to say the right things,unbroken,new school', 'https://www.youtube.com/embed/JY49LSJ3_IA'),
(31, 'Angermeans', 'STRIFE', 1980, 'images/item31.jpg', '再入荷！CA出身VictoryのSTRIFEの三枚目のアルバム（編集盤除く）で01年作品。それまでのアルバムではOLD SCHOOLベースにメタリックなNEW SCHOOLフレイバーを加えたサウンドが主体だったSTRIFEだがこの作品ではかなりメタリックになり完全にNEW SCHOOLファン向けの作品になった事で当時良くも悪くも話題になった作品！結論から言えばSTRIFEは何もしてもSTRIFEでありそれまでの作品よりもパワフルでヘビーになった低音の出たサウンドが印象的。Chuga Chuga Mosh HCのテイストを以前よりも色濃く出したNEW SCHOOLサウンドにRickのアンガーなボーカルが乗るあたりは貫禄を感じます。前の作品の頃から時折見られた泣きのパートも健在でエモーショナルなギターが効果的に挿入されています。この作品を出して二年後に解散していたまさにラスト作品でありスルーしていた人は是非一度トライしてみては？', 'angermeans,strife,new school', 'https://www.youtube.com/embed/kdvK6XtPvoQ'),
(32, 'Big Kiss Goodnight', 'TRAPPED UNDER ICE', 2880, 'images/item32.jpg', '待望の再発盤！メリーランド出身説明不要TRAPPED UNDER ICEが2ndアルバムを完成させてGood Fightからリリース！MADBALL, TERRORそしてTUI！言うまでもなく全世界規模での名実揃ったバンドで現代のハードコアを代表するバンド！NYHCそしてバルチモアのクラシックなTUFF GUYテイストをミックスさせたTUI節は本作でも変わる事無くセンス良すぎます！クラシックなNYHCから王道ハードコア、モダンなアメリカンハードコアのフレイバーも十分に混ぜ込んであり素晴らしいグルーブを放った作品になっています！前作よりも柔軟にノセてくるリズム感がかなり心地よく、相変わらずセンスいいボーカルパートはこの作品でもばっちり☆モッシーなドラムも最高！', 'big kiss goodnight,trapped under ice,hardcore,moshcore', 'https://www.youtube.com/embed/J_9Qp9_qaI4'),
(33, 'Seeking The Way: The Greatest Hits', 'SHADOWS FALL', 1980, 'images/item33.jpg', 'USでは廃盤のネタをEUROから少量入手！KILLSWITCH ENGAGE, ALL THAT REMAINS, UNEARTHと共にMAから巣立ったMETALCORE/METALバンドの中でも早い段階からブレイクしていた重鎮SHADOWS FALLが07年にリリースしたベスト盤で彼らがCENTURY MEDIAと契約して以降リリースしてきた6枚のアルバムから名曲をセレクトして、未発表曲を2曲加えた作品！ex OVERCASTのボーカルを迎えての衝撃作だった彼らのセカンドアルバム\"OF ONE BLOOD\"からいままでの軌跡が手に取るように分かるおいしいリリース！！全14曲入り！', 'seeking the way the greatest hits,shadows fall,metalcore,deathcore', 'https://www.youtube.com/embed/fEkgmjAL0t8'),
(34, 'Dance With The Devil', 'BORN FROM PAIN', 1980, 'images/item34.jpg', '再入荷！オランダ産TUFF GUY MOSHCOREバンドBORN FROM PAINが新作アルバムを完成させリリース！なんとBEATDOWN HARDWEARに移籍しての話題作！ボーカルがチェンジしての過去2作はメタルコアの要素を組み込んだ感じのサウンドをメインにメロディを随所にミックスさせた作風だったが本作ではBDHWからのリリースという自負もあり従来のブルータルでヘビーモッシュな作品に仕上がり久しぶりのBFP節が炸裂した内容になっています！本来のBORN FROM PAINの重さと図太さに重点を置いたモッシーなサウンドは重戦車のごとく刻むギターのリフとドラムのバスドラの敷き詰められたゴリゴリな楽曲！完全に初中期の雰囲気を見事に取り戻した重量級なアルバム！', 'dance with the devil,born from pain,metalcore,deathcore', 'https://www.youtube.com/embed/K67rcBTkiIs'),
(35, 'I Am Nemesis', 'CALIBAN', 2180, 'images/item35.jpg', 'ドイツ産メタルコアバンドCALIBANが8枚目となる新作を完成！！これは待ちに待った新作！Century Mediaからリリースの入魂作品を入荷！やっぱりかっこいいです。HEAVEN SHALL BURNと共にユーロメタルコアを牽引する存在！いい意味で変わらないメロディックさと欧州の王道でもあるドラマティックさを最大限に引き出したアルバムで、サビで王道CALIBANのキャッチーメロディが入ってくる、そして強烈な落としも待っているというお約束の仕上がり。とにかく楽曲の質が良く個人的にはUndying Darkness、he Awakeningの時の衝撃に近い程！間違い無くゲットすべし作品！ゲストでSUICIDE SILENCE, HEAVEN SHALL BURNのメンバーが参加しています！', 'i am nemesis,caliban,metalcore,deathcore', 'https://www.youtube.com/embed/1A2ZnEBEV4M'),
(36, 'What Counts', 'HAVE HEART', 2280, 'images/item36.jpg', '再入荷！BOSTON発OLD SCHOOL HCバンドHAVE HEARTの04年にリリースされていた彼らのデビュー7inch EPが10周年を記念して12インチLPにてリリース！Triple Bがリリースした音源で、全てリマスターされボーナストラックにOUTSPOKENのカバーを加え見開きゲイトフォルド仕様の豪華ジャケットにてリリース！これは非常に魅力的なアイテムかと！疾走感あるオールドスクールサウンドは非常に荒々しくエッジの効いたギターもヘビー！サビでエモーショナルなギターのオクターブが入ってくるスタイルで最高です！', 'what counts,have heart,old school,youth crew', 'https://www.youtube.com/embed/miTm_m235dc'),
(37, 'Get Loud!', 'AGNOSTIC FRONT', 2180, 'images/item37.jpg', 'NYHCの絶対的王者AGNOSTIC FRONTによる新作アルバムが到着！Nuclear Blast Recordsからのリリース！今作が本当に内容良いです！まずジャケを見た時に感じたCause For Alarmへの回帰感そのもの！Sean Taggartが手掛けています！そして中身も彼らのNYHCにおけるストレートさ、TUFFさを前に出した作品！最初に聞いた時にLiberty & Justice For...辺りの時代を思わす印象でした！Get Loud!まさにAFが世界に向けて放つメッセージの元、アグレッシブでメタリックさもありOLD SCHOOLそしてRogerのTUFFな声質も変わらず！徹頭徹尾NYHCのアグレッシブな部分に焦点を置いたHARDな作品と思われます！個人的にはDead Silence辺りが一番フェイバリットな曲で是非聞いて欲しいです。ただのAFなんですが90\'sハードコア的に言わせて貰うと25 TA LIFEが追っていたサウンド出しています！冒頭のSpray Painted Wallsから畳みかける素晴らしい作品！', 'get loud!,agnostic front,old school,youth crew', 'https://www.youtube.com/embed/rBU1wi_ijXU'),
(38, 'Total Retaliation', 'TERROR', 2180, 'images/item38.jpg', 'USHC最前線！誰も勢いを止められないTERRORの新作アルバムがリリース！Pure Noise Entertainmentからのリリースにて7作目となるアルバム！何一つ変わる事の無いまさにTERRORという内容は今回も変わらず！冒頭からストレートで走るバックにScottの畳みかけるボーカル、そして2ステップにTUFFさGROOVEを併せ持ったサウンド！エネルギーに満ち溢れた全編と近年のTERRORにおけるモッシーな要素もしっかり組み込んで新旧織り交ぜた完璧なパンク・ハードコアアルバムに仕上げています！', 'total retaliation,terror,old school,youth crew', 'https://www.youtube.com/embed/XevEWq5xkcY'),
(39, 'Never Better', 'ANXIOUS', 1280, 'images/item39.jpg', '再入荷！コネチカット出身INDIE/EMO・PUNK/HARDCOREバンドANXIOUSがTriple-B Recordsから1st EPをリリース！Promoの時点でそのセンス、メロディ、グルーブ全てにおいて世界観100点なバンドでしたがようやく4曲入りでリリース！メロディアス、緩いチルなサウンドをベースに哀愁感もある絶妙なサウンドはINDIE ROCK/EMO〜パンク・ハードコアのアティチュードも埋め込んだサウンドでTITLE FIGHTやFIDDLEHEAD, WESTPOINT〜TOUCHE AMOREなんか好きな人には大推薦な音源！DL可能！', 'never better,anxious,emo,screamo,新着商品', 'https://www.youtube.com/embed/BGs-z1hWdeY'),
(40, 'Supersonic Home', 'ADVENTURES', 1980, 'images/item40.jpg', 'アメリカ、ペンシルベニア州ピッツバーグ出身インディー・ロック/エモバンドADVENTURESの1stアルバムがRun For CoverからCDにて！ハードコアバンドCODE ORANGEのメンバー3人を含む注目のインディー・ロック/エモバンドにて幅広い層に支持されているのも周知です！！TITLE FIGHT、CIRCA SURVIVE、TIGERS JAW等を手掛ける名プロデューサーWill Yipを起用！\'90sオルタナティブ・ロック、エモとパンクのエナジーをブレンドしたサウンドはパワフルながらメロディの良さも光ります。', 'supersonic home,adventures,emo,screamo', 'https://www.youtube.com/embed/mgqAc2Ux1Tw'),
(41, 'Not Glitterer', 'GLITTERER', 1280, 'images/item41.jpg', 'これは激押し！TITLE FIGHTのベース/ヴォーカル、Ned Russinのソロ・プロジェクトGLITTERERの2作目がFlexi EPで入荷！まさかのフィジカル化最高です！\"Bedroom-Pop\"とも称されるムーディーなシンセサイザーが心地良く響き渡る、ゆるくチルなインディー・ポップ・サウンドで、TITLE FIGHTとはベクトルが違うものの歌詞、ヴォーカル、ソングライティングのセンスはさすがの一言。プロデュースはあのALEX G、マスタリングはCOLD WORLDやPOWER TRIPなどを手掛けるArthur Rizkが担当！Hyperviewのアルバムをサイケデリックにけだるくした感じのプロダクション、Nedのメロディラインも最高です！DL可能！確実に廃盤になりそうな音源ですのでお早めに！', 'not glitterer,glitterer,emo,screamo,新着商品', 'https://www.youtube.com/embed/XiVxW4zg6hg'),
(42, 'A Distant Call', 'SHEER MAG', 1880, 'images/item42.jpg', 'PAはフィラデルフィアのパンクロックバンドSHEER MAGによる新作アルバムがWilsuns Recording Companyからリリース！ボーカルのTina HalladayがTURNSTILEの新作でゲストしていたり、来日も果たしたり非常に日本でも人気が出ているバンドの一つ！クラシックでオールディーズなパンクからの影響を受けたメロディアスでまるで映画のBGMの様な世界観はローファイかつパワフル。THIN LIZZYやTHE SHIVVERS等の要素、よりハードロックなメロディとパワフルさも感じるサウンド確実に癖になります！', 'a distant call,sheer mag,melodic,punk', 'https://www.youtube.com/embed/z2ZEutg3oZk'),
(43, 'Old Record', 'A DAY TO REMEMBER', 1980, 'images/item43.jpg', 'フロリダ出身メロディックハードコア・イージーコアA DAY TO REMEMBERのIndianola recからリリースされていた1stを編曲、再録、再マスタリングしVICTORYがリリースした10曲入りCD！このお値段でボリュームたっぷりの作品になっています。一言で言えばMELODIC HC/PUNKにBRUTAL過ぎるBREAKDOWN MOSHPARTを組み合わせたバンドである意味そんな事が可能か・・・と思いがちだが彼らはそれを確実に持ち味にしているバンドでメロコア、ニュースクール、メタルコア全ての層のキッズにアピールできるサウンドに仕上げています。', 'old record,a day to remember,melodic,punk', 'https://www.youtube.com/embed/XI57WF16vmQ'),
(44, 'Man Is Black', 'xEDGEx', 1880, 'images/item44.jpg', 'ex BURN IT DOWN, TEARS OF GAIA, SENTIENT, VEGAN REICH, SEVEN GENERATIONSのメンバーでもあったカリフォルニアのVEGAN SxEラッパー \"xEDGEx\"が遂に8曲入りのCDをリリース！2008年 Four Rivers Mediaよりリリース！今までDEMO CDRを二枚リリースしてつちかったスキルを発展させたカリはウェッサイの本拠地LONG BEACHから発信された充実の一枚！8曲完璧なまでのラップアルバムに仕上がっておりLONG BEACHともあり西を意識させるレイドバックな甘いビートからNYC MID 90\'sを意識させたクラシックなビートまでバラエティ富んだ内容で女性レゲエDJをFEATしての楽曲もありでVEGAN / SxE LIFESTYLEをFULL UPRISEしたメッセージ性に富んだRAP SHIT!!', 'man is black,edge,rap', 'https://www.youtube.com/embed/QptcIHDmtEs'),
(45, 'Controverse', 'DJAMHELLVICE', 1980, 'images/item45.jpg', '再入荷！フランス出身ex PROVIDENCEのメンバーのJamelによるラッパー名義DJAMHELLVICEが2年ぶりの新作を完成！3枚目のリリースとなるフルアルバムでVocation Recordsからのリリース！既に様々な客演等スキルやキャリアを積みHARDCOREともクロスオーバーするHIPHOPのシーンでは欠かせない存在と成長しています！今回もDjamによるドスの効いたタフで強いラップにビートは様々なビートメイカーがトラックを提供しています！同じフランスで注目のKnives Out RecordsからもリリースしているデトロイトのSUICIDE KINGS等をゲストに迎えたり客演も様々！Res Turner, Skalpel (Première Ligne), VII, Joey Knuckles & Aztek The Barfly (Suicide Kings / Bully Camp), Doliath & ADM等がゲスト陣に名を連ねています！', 'controverse,djamhellvice,rap,新着商品', 'https://www.youtube.com/embed/aiPz-aneDs8'),
(46, 'Open The Cages', 'VARIOUS ARTISTS', 2180, 'images/item46.jpg', '久々の入荷！CATALYSTからのリリースは異常すぎるVEGAN STRAIGHT EDGEコンピレーションでPATH OF COMPASSION, STONES MARK A FIRE, ANIMAL TRUTH, MOTHER EARTHなんかにも匹敵するかのような完全に必聴の一枚！ドイツ、アメリカ、オランダ、南米等の若手から大物バンドまでなんと25曲も収録されています！参加バンドはxRISENx, GATHER, DAY OF SUFFERING(!), BIRTHRIGHT, NEW WINDS, 7 GENERATIOS等の名の知られたバンドから、REQUIEM, SWORDS, ATTRITION, xFisticuffsxという新しいところまで収録していてやばすぎます！HEADFIRST RECORDSとの共同リリース！', 'open the cages,various artists,新着商品', 'https://www.youtube.com/embed/Ox8WDw70w3g'),
(47, 'Split', 'UP FRONT / BUILDING', 1880, 'images/item47.jpg', '1999年SoberMindからリリースされていたUP FRONTとBUILDINGのSplit MCDで全6曲入り。どちらのバンドも80-90年代を代表するファストでユースクルーなOLD SCHOOLサウンドを展開！言うまでもなくUP FRONTは疾走するハイテンションなOLD SCHOOLに気持ちいいくらいのハイテンションボーカル、シンガロングがたまりません！BUILDINGも同じく若干エナジー溢れるOLD SCHOOLサウンドが時代背景を感じます！両バンド3曲ずつの計6曲入り', 'split,up front,building,split material', 'https://www.youtube.com/embed/imi7zG6RCiU');

-- --------------------------------------------------------

--
-- テーブルの構造 `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `text` varchar(200) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `reviews`
--

INSERT INTO `reviews` (`id`, `text`, `item_id`, `user_id`) VALUES
(43, 'イイネ！！', 1, 46),
(57, 'うむ。。', 1, 47),
(59, 'イイネ！！', 1, 46),
(62, 'イイネ！！', 1, 46),
(87, '最高！！', 1, 69),
(88, 'hahaha!!', 1, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'yusuke', 'southern_cross2047@yahoo.co.jp', '2047'),
(2, 'mai', '09@28', '0928'),
(3, 'harui', '05@14', '0514'),
(4, 'hiroyuki', '04@09', '0409'),
(5, 'yachiyo', '12@07', '1207'),
(46, 'ナポリたん', 'a@a', '1111'),
(47, 'ぺぺろんちー', 'b@b', '2222'),
(69, 'カルボ奈良', 'c@c', '3333');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `diaries`
--
ALTER TABLE `diaries`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `diaries`
--
ALTER TABLE `diaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- テーブルのAUTO_INCREMENT `histories`
--
ALTER TABLE `histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- テーブルのAUTO_INCREMENT `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- テーブルのAUTO_INCREMENT `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
