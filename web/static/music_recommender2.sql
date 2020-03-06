/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : music_recommender

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-12-31 14:19:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for echonest_information
-- ----------------------------
DROP TABLE IF EXISTS `echonest_information`;
CREATE TABLE `echonest_information` (
  `echonest_id` varchar(255) NOT NULL,
  `song_name` varchar(255) NOT NULL,
  `artist_name` varchar(255) NOT NULL,
  PRIMARY KEY (`echonest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of echonest_information
-- ----------------------------

-- ----------------------------
-- Table structure for song_information
-- ----------------------------
DROP TABLE IF EXISTS `song_information`;
CREATE TABLE `song_information` (
  `song_id` varchar(255) NOT NULL COMMENT '歌曲ID',
  `song_name` varchar(255) NOT NULL COMMENT '歌曲名',
  `artist_name` varchar(255) NOT NULL COMMENT '歌手名',
  `album_id` varchar(255) NOT NULL COMMENT '专辑ID',
  `album_name` varchar(255) NOT NULL COMMENT '专辑名',
  `album_picture` varchar(255) DEFAULT NULL COMMENT '专辑图片',
  PRIMARY KEY (`song_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of song_information
-- ----------------------------
INSERT INTO `song_information` VALUES ('111129', '十年(Live) - live', '刘德华', '10966', '反转红馆倒转地球96香港演唱会', 'http://p1.music.126.net/XhYWJnNZOmi3NUvBGmu9yg==/65970697682644.jpg');
INSERT INTO `song_information` VALUES ('1293886117', '年少有为', '李荣浩', '73914415', '耳朵', 'http://p1.music.126.net/tt8xwK-ASC2iqXNUXYKoDQ==/109951163606377163.jpg');
INSERT INTO `song_information` VALUES ('1303019637', '说书人', '暗杠/寅子', '72385845', '说书人', 'http://p1.music.126.net/X9-wFEy1DfWOx5R2iCXqIg==/109951163509761545.jpg');
INSERT INTO `song_information` VALUES ('1306400549', '写给黄淮（demo）', '解忧邵帅', '72780839', '我在黄淮', 'http://p1.music.126.net/PNNpIr9mQY98jS9H9PV9eA==/109951163525904130.jpg');
INSERT INTO `song_information` VALUES ('1309130038', '幻听（Cover：许嵩）', '隔壁老樊', '36157658', '大风遇到了雨', 'http://p1.music.126.net/t6HyCeDaCRZwOBY3k08yew==/109951163638149565.jpg');
INSERT INTO `song_information` VALUES ('1318733599', 'Sunflower (Spider-Man: Into the Spider-Verse)', 'Post Malone/Swae Lee', '74878945', 'Spider-Man: Into the Spider-Verse (Soundtrack From & Inspired by the Motion Picture)', 'http://p2.music.126.net/LuFoxA6oT1tLhgzGUbzovQ==/109951163738801836.jpg');
INSERT INTO `song_information` VALUES ('1327456179', '绝代风华', '许嵩', '74519732', '绝代风华', 'http://p1.music.126.net/Ngh65GwhHtufNRSs9KgEIA==/109951163681417636.jpg');
INSERT INTO `song_information` VALUES ('1330348068', '起风了', '买辣椒也用券', '74715426', '起风了', 'http://p1.music.126.net/diGAyEmpymX8G7JcnElncQ==/109951163699673355.jpg');
INSERT INTO `song_information` VALUES ('1331322046', 'Everything I Need (Film Version)', 'Skylar Grey', '74777564', 'Aquaman (Original Motion Picture Soundtrack)', 'http://p1.music.126.net/C5xuNmW_v1hy3vF0ESjSyQ==/109951163707318402.jpg');
INSERT INTO `song_information` VALUES ('1332942790', '十年（Cover：陈奕迅）', '张寒铭', '74837804', '十年', 'http://p1.music.126.net/WxaTRE60pS26NvrTrF62Ww==/109951163721876099.jpg');
INSERT INTO `song_information` VALUES ('1334270281', '别再闹了', '毛不易', '74914739', '别再闹了', 'http://p2.music.126.net/rvX5AGzJW10FPHkKkYFWWQ==/109951163739268118.jpg');
INSERT INTO `song_information` VALUES ('1334327077', '世本常态', '隔壁老樊', '74934635', ' 世本常态', 'http://p1.music.126.net/ouMDxBVUg7Ny54QXskYLOQ==/109951163739191176.jpg');
INSERT INTO `song_information` VALUES ('1334647784', '天份', '薛之谦', '74999481', '怪咖', 'http://p1.music.126.net/TOkRGd59o3hAOKsnMMmMMA==/109951163755246383.jpg');
INSERT INTO `song_information` VALUES ('1334659052', '我要给世界最悠长的湿吻', '蔡健雅', '74946019', '我要给世界最悠长的湿吻', 'http://p2.music.126.net/5rsbWByBqJRNWK9-sMbqfg==/109951163737387160.jpg');
INSERT INTO `song_information` VALUES ('1334849028', '进阶', '林俊杰', '74956171', '进阶', 'http://p1.music.126.net/SCKSIhCbxlqkC9rTjIWVAQ==/109951163739212997.jpg');
INSERT INTO `song_information` VALUES ('1335350268', '圣诞夜', '朱星杰', '74973282', '圣诞夜', 'http://p2.music.126.net/FwkduTpyoRaRX3MgZoCuYw==/109951163745459483.jpg');
INSERT INTO `song_information` VALUES ('167655', '幻听', '许嵩', '16932', '梦游计', 'http://p1.music.126.net/6TNYBV2rezZLiwsGYBgmPw==/123145302311773.jpg');
INSERT INTO `song_information` VALUES ('167827', '素颜', '许嵩/何曼婷', '16949', '素颜', 'http://p1.music.126.net/rUBN_BOm5C2zXrX6deiH7g==/111050674419060.jpg');
INSERT INTO `song_information` VALUES ('167844', '灰色头像', '许嵩', '16951', '寻雾启示', 'http://p1.music.126.net/3hqcQrXZ39kDCCzV7QbZjA==/34084860473122.jpg');
INSERT INTO `song_information` VALUES ('167850', '庐州月', '许嵩', '16951', '寻雾启示', 'http://p1.music.126.net/3hqcQrXZ39kDCCzV7QbZjA==/34084860473122.jpg');
INSERT INTO `song_information` VALUES ('167870', '如果当时', '许嵩', '16953', '自定义', 'http://p1.music.126.net/Md3RLH0fe2a_3dMDnfqoQg==/18590542604286213.jpg');
INSERT INTO `song_information` VALUES ('167876', '有何不可', '许嵩', '16953', '自定义', 'http://p1.music.126.net/Md3RLH0fe2a_3dMDnfqoQg==/18590542604286213.jpg');
INSERT INTO `song_information` VALUES ('167882', '清明雨上', '许嵩', '16953', '自定义', 'http://p1.music.126.net/Md3RLH0fe2a_3dMDnfqoQg==/18590542604286213.jpg');
INSERT INTO `song_information` VALUES ('167937', '断桥残雪', '许嵩', '16960', '断桥残雪', 'http://p1.music.126.net/PEQ69_EwVpuaBmmSkAY0SQ==/58274116284456.jpg');
INSERT INTO `song_information` VALUES ('26188040', '十年', '沙宝亮', '2415003', '我是歌手第一季 总决赛', 'http://p1.music.126.net/MG01RVbmHEsh6LVMKmvPpg==/2525578209026524.jpg');
INSERT INTO `song_information` VALUES ('27646687', '玫瑰花的葬礼', '许嵩', '16959', '许嵩单曲集', 'http://p1.music.126.net/2iwn7NnfNwtdyu1enlJw_w==/83562883723773.jpg');
INSERT INTO `song_information` VALUES ('28854182', '惊鸿一面', '许嵩/黄龄', '2893090', '不如吃茶去', 'http://p1.music.126.net/WoR2LbM1IFauFpvhBWOjqA==/6642149743396577.jpg');
INSERT INTO `song_information` VALUES ('31877628', '十年(Live)', '韩红/陈奕迅', '3142085', '我是歌手第三季 总决赛', 'http://p1.music.126.net/C69zO6_wapO94TOE5DnIBg==/2941193605503524.jpg');
INSERT INTO `song_information` VALUES ('34040696', '十年', '王啸坤', '3270689', '最佳前男友 电视原声带', 'http://p1.music.126.net/JlI104QnoQqhfd2dDgzjQw==/7729566744421584.jpg');
INSERT INTO `song_information` VALUES ('34040700', '十年(伴奏)', '群星', '3270689', '最佳前男友 电视原声带', 'http://p1.music.126.net/JlI104QnoQqhfd2dDgzjQw==/7729566744421584.jpg');
INSERT INTO `song_information` VALUES ('34040853', '十年', '赵丽颖', '3270733', '十年', 'http://p1.music.126.net/kKhrHrRBgHg-I3jQm6tPlA==/3274345627819236.jpg');
INSERT INTO `song_information` VALUES ('358462', '十年', '黄金年代', '35401', '黄金年代 同名EP', 'http://p1.music.126.net/H5xBpH6QTZtzxpAMCGfDvw==/84662395353954.jpg');
INSERT INTO `song_information` VALUES ('411214279', '雅俗共赏', '许嵩', '34749138', '青年晚报', 'http://p1.music.126.net/Wcs2dbukFx3TUWkRuxVCpw==/3431575794705764.jpg');
INSERT INTO `song_information` VALUES ('412902950', '最佳歌手', '许嵩', '34749138', '青年晚报', 'http://p1.music.126.net/Wcs2dbukFx3TUWkRuxVCpw==/3431575794705764.jpg');
INSERT INTO `song_information` VALUES ('436699207', '十年', '费玉清', '34929266', '天籁之战 第二期', 'http://p1.music.126.net/TMWbXFcYWvylMV3JBcYpNw==/18720284975245736.jpg');
INSERT INTO `song_information` VALUES ('441491828', '水星记', '郭顶', '35005583', '飞行器的执行周期', 'http://p1.music.126.net/wSMfGvFzOAYRU_yVIfquAA==/2946691248081599.jpg');
INSERT INTO `song_information` VALUES ('446875807', '十年', '梁朝伟/李宇春', '35052152', '十年', 'http://p1.music.126.net/96FnT-ztJjJkE1EbV-nGcg==/18781857627302141.jpg');
INSERT INTO `song_information` VALUES ('449818741', '光年之外', 'G.E.M.邓紫棋', '35093341', '光年之外', 'http://p1.music.126.net/fkqFqMaEt0CzxYS-0NpCog==/18587244069235039.jpg');
INSERT INTO `song_information` VALUES ('486194110', '十年 ', '韩丹彤', '35661175', '2017跨界歌王 第十一期', 'http://p1.music.126.net/TqGCB0KK2LK9X9zKr0rqAQ==/18517974836952021.jpg');
INSERT INTO `song_information` VALUES ('513791211', '广东十年爱情故事', '广东雨神', '36589315', '广东十年爱情故事', 'http://p1.music.126.net/SWDOrvO3f6L8Q1xGPTbb6w==/109951163102543599.jpg');
INSERT INTO `song_information` VALUES ('515143108', '十年', '孙露', '36525627', '十大华语金曲', 'http://p1.music.126.net/FY2Yhy-n9g84lIH8DykCHg==/109951163049862087.jpg');
INSERT INTO `song_information` VALUES ('516728102', '浪子回头', '茄子蛋', '36693907', '卡通人物', 'http://p1.music.126.net/emWwYFceRZ2plNWgnGUDfg==/109951163333175426.jpg');
INSERT INTO `song_information` VALUES ('518781004', '生僻字', '陈柯宇', '36789430', '生僻字', 'http://p1.music.126.net/fzy5I3GvAjiDfwhIEbgXuw==/109951163062323125.jpg');
INSERT INTO `song_information` VALUES ('5255987', '你若成风', '许嵩', '512106', '乐酷', 'http://p1.music.126.net/OjibHiyRong4S0RgBFp-Pw==/2301277836958388.jpg');
INSERT INTO `song_information` VALUES ('543965875', '十年', '校长', '37873337', '十年', 'http://p1.music.126.net/Uk35MMkUcvI9mM7P_t-GtA==/109951163179111104.jpg');
INSERT INTO `song_information` VALUES ('553755659', '可不可以', '张紫豪', '38385235', '可不可以', 'http://p1.music.126.net/WafK2OQfEtqXitdDXJ772Q==/109951163252847249.jpg');
INSERT INTO `song_information` VALUES ('574566207', '盗将行', '花粥/马雨阳', '39752444', '粥请客（二）', 'http://p1.music.126.net/-qHPT3rhxDlu5zQV9NcQ-A==/109951163555860423.jpg');
INSERT INTO `song_information` VALUES ('94639', '相依为命', '陈小春', '9259', '十年选', 'http://p1.music.126.net/T9nGuH3jv0Rq0iWYeOhvAQ==/30786325589804.jpg');

-- ----------------------------
-- Table structure for song_url
-- ----------------------------
DROP TABLE IF EXISTS `song_url`;
CREATE TABLE `song_url` (
  `song_id` varchar(255) NOT NULL,
  `song_url` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `md5` varchar(255) DEFAULT NULL,
  `size` double DEFAULT NULL,
  PRIMARY KEY (`song_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of song_url
-- ----------------------------
INSERT INTO `song_url` VALUES ('1293886117', 'http://m8.music.126.net/20181231094301/b65fe55f094f16dad102116abca7a54b/ymusic/2242/0271/8dd1/b6da3ffc8d2561b321284004c81da136.mp3', 'mp3', 'b6da3ffc8d2561b321284004c81da136', '4467609');
INSERT INTO `song_url` VALUES ('1309130038', 'http://m8c.music.126.net/20181231094415/3f6dd73deabedabafa1fccd53b343b87/ymusic/5051/e42d/a47a/19430d1db4a3a889143d91ca0adf18a5.mp3', 'mp3', '19430d1db4a3a889143d91ca0adf18a5', '11001774');
INSERT INTO `song_url` VALUES ('1330348068', 'http://m8.music.126.net/20181231094246/a86b204659743676170f0d3a31e2691a/ymusic/0758/550f/545f/028d3b9421be8425d60dc57735cf6ebc.mp3', 'mp3', '028d3b9421be8425d60dc57735cf6ebc', '5214920');
INSERT INTO `song_url` VALUES ('1332942790', 'http://m7.music.126.net/20181231144252/3e086c12c128174a656ab7345ef7ebec/ymusic/95ba/d61f/b571/2413e68d58fd44b416759a74788696cc.mp3', 'mp3', '2413e68d58fd44b416759a74788696cc', '8066656');
INSERT INTO `song_url` VALUES ('1334647784', 'http://m7c.music.126.net/20181231094241/e1337ef394720f989529c3237bf182d2/ymusic/005b/055c/520e/71203622f626bf8143cbed5db6e2d677.mp3', 'mp3', '71203622f626bf8143cbed5db6e2d677', '9926574');
INSERT INTO `song_url` VALUES ('167827', 'http://m7c.music.126.net/20181231094420/60fed5700eb32a25591bc7c407be80f4/ymusic/6775/40b5/cfc1/ee973add1ad4c10dd5b23260983d8744.mp3', 'mp3', 'ee973add1ad4c10dd5b23260983d8744', '9551455');
INSERT INTO `song_url` VALUES ('167850', 'http://m8c.music.126.net/20181231094406/075a3aeef4988147825c3a4aee61b9f2/ymusic/86de/4d7b/c0dc/b45d9ddf9d7837f0d0bb71707ee1decf.mp3', 'mp3', 'b45d9ddf9d7837f0d0bb71707ee1decf', '10223324');
INSERT INTO `song_url` VALUES ('167876', 'http://m7c.music.126.net/20181231094418/bf1f0130c984ad7b7035e2787a159f75/ymusic/1b34/2328/a0e3/decc4a0da8f73007fd3b17c038a0c1b6.mp3', 'mp3', 'decc4a0da8f73007fd3b17c038a0c1b6', '9675799');
INSERT INTO `song_url` VALUES ('168091', 'http://m7c.music.126.net/20181231143642/4da8c61b74ffaa4bba524251874f65ac/ymusic/ce70/d44c/021f/31d3215f707625a65663ab51858d6a86.mp3', 'mp3', '31d3215f707625a65663ab51858d6a86', '4329682');
INSERT INTO `song_url` VALUES ('168093', 'http://m7.music.126.net/20181231143951/7113d8a8d99b5539623d197a4610f3d0/ymusic/7064/6d98/2c3d/c5b436fa3d8245e6d44ee5cc07742a6b.mp3', 'mp3', 'c5b436fa3d8245e6d44ee5cc07742a6b', '4857564');
INSERT INTO `song_url` VALUES ('28854182', 'http://m7c.music.126.net/20181231094411/60e4c9532a5533c7cd3c7b103b3c4f75/ymusic/4c7f/1cf0/8d3a/cec0dfa20742f5c0349d1bf4bb7bfa39.mp3', 'mp3', 'cec0dfa20742f5c0349d1bf4bb7bfa39', '10259896');
INSERT INTO `song_url` VALUES ('34040696', 'http://m8.music.126.net/20181231144230/d6560168126f079f1b42842945340c6d/ymusic/fb65/8d7f/1f83/8c7210882fec5c4888d567c12d393f92.mp3', 'mp3', '8c7210882fec5c4888d567c12d393f92', '3229613');
INSERT INTO `song_url` VALUES ('441491828', 'http://m7.music.126.net/20181231094258/8fcad0d20d7f2947745d7197657ce47e/ymusic/3dd2/3efd/8621/aaf0881569565f9fd2946ad9551ab491.mp3', 'mp3', 'aaf0881569565f9fd2946ad9551ab491', '5205307');
INSERT INTO `song_url` VALUES ('446875807', 'http://m7c.music.126.net/20181231144226/5ad53b8cb35dff8a3be165b7027fdb3c/ymusic/9141/2cd2/6ac8/adc4676fcd3c56598386498fe2516b45.mp3', 'mp3', 'adc4676fcd3c56598386498fe2516b45', '10103162');
INSERT INTO `song_url` VALUES ('449818741', 'http://m8c.music.126.net/20181231094333/cf21c816e1936c6ebd5e7f306c33995a/ymusic/1606/426f/10a6/a01cace34f2df73c384bbcfe3e30b827.mp3', 'mp3', 'a01cace34f2df73c384bbcfe3e30b827', '3769199');
INSERT INTO `song_url` VALUES ('516728102', 'http://m7.music.126.net/20181231094254/7b83c9611f6d64c1560a6f3b698f4448/ymusic/0a18/e88d/979f/6d5282fba78b1674f3103ab87342846a.mp3', 'mp3', '6d5282fba78b1674f3103ab87342846a', '4151214');
INSERT INTO `song_url` VALUES ('518781004', 'http://m7c.music.126.net/20181231094251/a833950ff9dc63c02ac0ca7164c5d522/ymusic/5b06/2890/55c0/b4201bcf1f6135427ad0cf1b667c6451.mp3', 'mp3', 'b4201bcf1f6135427ad0cf1b667c6451', '3456984');
INSERT INTO `song_url` VALUES ('5255987', 'http://m8c.music.126.net/20181231094425/63c1fed0a9b60e75d687db39696f883f/ymusic/f057/5618/8f51/8c3b819649bc0179d9886849e73815cd.mp3', 'mp3', '8c3b819649bc0179d9886849e73815cd', '8863973');
INSERT INTO `song_url` VALUES ('553755659', 'http://m8.music.126.net/20181231094322/8e57915e41984bb963ea03519a0b2f91/ymusic/341e/9cc2/7c4f/b13ac6e62d3625524dde95fd1b1628bf.mp3', 'mp3', 'b13ac6e62d3625524dde95fd1b1628bf', '3855299');
INSERT INTO `song_url` VALUES ('574566207', 'http://m8c.music.126.net/20181231094325/c2ef71e85ff0768885f0e235cad1c2d5/ymusic/07fa/a2a1/35ea/732937117d6d0a8c13a81bb40184662e.mp3', 'mp3', '732937117d6d0a8c13a81bb40184662e', '3171100');

-- ----------------------------
-- Table structure for user_information
-- ----------------------------
DROP TABLE IF EXISTS `user_information`;
CREATE TABLE `user_information` (
  `uid` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_information
-- ----------------------------
INSERT INTO `user_information` VALUES ('30c85982a6d34f6ccd50c38072c53740', 'admin', '0192023a7bbd73250516f069df18b500');

-- ----------------------------
-- Table structure for user_rating
-- ----------------------------
DROP TABLE IF EXISTS `user_rating`;
CREATE TABLE `user_rating` (
  `record_id` varchar(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `song_id` varchar(255) NOT NULL,
  `rating` varchar(255) NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_rating
-- ----------------------------
INSERT INTO `user_rating` VALUES ('b0809abbc0ee8b2b84c4e1fdd5c4ac3d', '30c85982a6d34f6ccd50c38072c53740', '34040696', '5.0');
