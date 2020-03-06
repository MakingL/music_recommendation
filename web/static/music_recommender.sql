/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50513
Source Host           : 127.0.0.1:3306
Source Database       : music_recommender

Target Server Type    : MYSQL
Target Server Version : 50513
File Encoding         : 65001

Date: 2018-04-27 16:22:47
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
