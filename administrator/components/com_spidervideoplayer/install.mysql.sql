CREATE TABLE IF NOT EXISTS `#__spidervideoplayer_playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `videos` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__spidervideoplayer_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `required` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__spidervideoplayer_theme` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__spidervideoplayer_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL,
  `urlHtml5` varchar(255) NOT NULL,
  `urlHD` varchar(200) NOT NULL,
  `urlHdHtml5` varchar(255) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `published` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `fmsUrl` varchar(256) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `#__spidervideoplayer_theme` (`id`, `default`, `title`, `appWidth`, `appHeight`, `playlistWidth`, `startWithLib`, `autoPlay`, `autoNext`, `autoNextAlbum`, `defaultVol`, `defaultRepeat`, `defaultShuffle`, `autohideTime`, `centerBtnAlpha`, `loadinAnimType`, `keepAspectRatio`, `clickOnVid`, `spaceOnVid`, `mouseWheel`, `ctrlsPos`, `ctrlsStack`, `ctrlsOverVid`, `ctrlsSlideOut`, `watermarkUrl`, `watermarkPos`, `watermarkSize`, `watermarkSpacing`, `watermarkAlpha`, `playlistPos`, `playlistOverVid`, `playlistAutoHide`, `playlistTextSize`, `libCols`, `libRows`, `libListTextSize`, `libDetailsTextSize`, `appBgColor`, `vidBgColor`, `framesBgColor`, `ctrlsMainColor`, `ctrlsMainHoverColor`, `slideColor`, `itemBgHoverColor`, `itemBgSelectedColor`, `textColor`, `textHoverColor`, `textSelectedColor`, `framesBgAlpha`, `ctrlsMainAlpha`, `itemBgAlpha`, `show_trackid`, `openPlaylistAtStart`) VALUES
INSERT INTO `#__spidervideoplayer_playlist` (`id`, `title`, `thumb`, `published`, `videos`) VALUES
(1, 'Nature', '../media/com_spidervideoplayer/upload/sunset4.jpg', 1, '1,2,');

INSERT INTO `#__spidervideoplayer_tag` (`id`, `name`, `required`, `published`, `ordering`) VALUES
(1, 'Year', 1, 1, 2),
(2, 'Genre', 1, 1, 1);

INSERT INTO `#__spidervideoplayer_video` (`id`, `url`, `urlHD`, `thumb`, `title`, `published`, `type`, `fmsUrl`, `params`) VALUES
(1, 'http://www.youtube.com/watch?v=eaE8N6alY0Y', '', '../media/com_spidervideoplayer/upload/red-sunset-casey1.jpg', 'Sunset 1', 1, 'youtube', '', '2#===#Nature#***#1#===#2012#***#'),
(2, 'http://www.youtube.com/watch?v=y3eFdvDdXx0', '', '../media/com_spidervideoplayer/upload/sunset10.jpg', 'Sunset 2', 1, 'youtube', '', '2#===#Nature#***#1#===#2012#***#');