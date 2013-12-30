-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Máquina: 127.0.0.1
-- Data de Criação: 09-Dez-2013 às 03:33
-- Versão do servidor: 5.5.32
-- versão do PHP: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `projeto_inicial`
--
CREATE DATABASE IF NOT EXISTS `projeto_inicial` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `projeto_inicial`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `acl`
--

CREATE TABLE IF NOT EXISTS `acl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module` varchar(100) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `controller` (`controller`,`action`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=17 ;

--
-- Extraindo dados da tabela `acl`
--

INSERT INTO `acl` (`id`, `module`, `controller`, `action`) VALUES
(1, '', 'auth', 'login'),
(3, 'adm', 'produtos', 'add'),
(4, 'adm', 'produtos', 'index'),
(5, 'adm', 'categorias', 'add'),
(6, 'adm', 'galerias', 'index'),
(7, 'adm', 'banners', 'index'),
(8, 'adm', 'videos', 'index'),
(9, 'auth', 'user', 'index'),
(10, 'auth', 'user', 'acl'),
(15, 'auth', 'auth', 'logout'),
(16, 'adm', 'marcas', 'index');

-- --------------------------------------------------------

--
-- Estrutura da tabela `acl_to_roles`
--

CREATE TABLE IF NOT EXISTS `acl_to_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `acl_id` int(10) NOT NULL,
  `role_id` tinyint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_id` (`acl_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_titulo` varchar(255) NOT NULL,
  `banner_imagem` varchar(100) NOT NULL,
  `banner_link` varchar(255) NOT NULL DEFAULT 'NULL',
  `banner_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `banners`
--

INSERT INTO `banners` (`banner_id`, `banner_titulo`, `banner_imagem`, `banner_link`, `banner_data`) VALUES
(1, 'gil 222222222222222', '1502445.jpg', 'gggggggggggggggg', '2013-12-02 01:47:15'),
(2, 'fffffffffffffffffffff', '946967867.jpeg', 'jjjjjjjjjjjjjjjjjjjjjjjjjj', '2013-12-02 02:04:17'),
(3, 'teste', '1272475191.png', '', '2013-11-30 20:12:42'),
(4, 'teste', '1882911836.png', '', '2013-11-30 20:16:31'),
(5, 'teste', '699834757.png', '', '2013-11-30 21:59:17'),
(6, 'teste', '229763389.png', '', '2013-11-30 22:10:31'),
(7, 'teste', '1028761541.png', '', '2013-11-30 22:11:03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_nome` varchar(100) NOT NULL,
  `categoria_descricao` text,
  `categoria_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`categoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `categoria_nome`, `categoria_descricao`, `categoria_data`) VALUES
(1, 'categoria gil', 'produtod dg dfg dfg dfg', '2013-11-16 22:44:32'),
(2, 'teste 1', 'categoria produto', '0000-00-00 00:00:00'),
(8, 'teste 1 ssdfsadfsdfas df', 'produto', '2013-11-15 21:17:50'),
(12, 'teste 1', 'produto', '0000-00-00 00:00:00'),
(13, 'teste 1 cvxcvxv ', 'produto', '0000-00-00 00:00:00'),
(16, 'teste 1', 'produto', '0000-00-00 00:00:00'),
(17, 'Categoria nova', 'nova categoria', '0000-00-00 00:00:00'),
(18, 'hghghgh', 'mnm,m,,m,mm,m,', '2013-11-17 03:34:09');

-- --------------------------------------------------------

--
-- Estrutura da tabela `galerias`
--

CREATE TABLE IF NOT EXISTS `galerias` (
  `galeria_id` int(11) NOT NULL AUTO_INCREMENT,
  `marca_id` int(11) NOT NULL,
  `galeria_titulo` varchar(255) DEFAULT NULL,
  `galeria_descricao` text,
  `galeria_img_capa` varchar(255) DEFAULT NULL,
  `galeria_data` date DEFAULT NULL,
  PRIMARY KEY (`galeria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `galerias`
--

INSERT INTO `galerias` (`galeria_id`, `marca_id`, `galeria_titulo`, `galeria_descricao`, `galeria_img_capa`, `galeria_data`) VALUES
(1, 0, 'teste', 'ert sertsertert se', '1_4.jpg', '2013-07-07'),
(2, 0, 'teste', 'ert sertsertert se', NULL, '2013-07-07'),
(3, 0, 'erter', 'erts ert', '1_3.jpg', '2013-04-04');

-- --------------------------------------------------------

--
-- Estrutura da tabela `galerias_imagens`
--

CREATE TABLE IF NOT EXISTS `galerias_imagens` (
  `galeria_imagem_id` int(11) NOT NULL AUTO_INCREMENT,
  `galeria_id` int(11) NOT NULL,
  `galeria_imagem` varchar(255) DEFAULT NULL,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`galeria_imagem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Extraindo dados da tabela `galerias_imagens`
--

INSERT INTO `galerias_imagens` (`galeria_imagem_id`, `galeria_id`, `galeria_imagem`, `data`) VALUES
(2, 1, '1_1.jpg', '2013-11-25 23:03:24'),
(4, 1, '1_3.jpg', '2013-11-25 23:03:29'),
(5, 1, '1_4.jpg', '2013-11-25 23:03:31'),
(6, 3, '1.jpg', '2013-11-27 22:29:35'),
(12, 8, '1.jpg', '2013-12-04 00:56:04'),
(13, 8, '1_1.jpg', '2013-12-04 00:56:07'),
(14, 8, '1_2.jpg', '2013-12-04 00:56:09'),
(15, 8, '1_3.jpg', '2013-12-04 00:56:11'),
(16, 8, '1_4.jpg', '2013-12-04 00:56:57'),
(17, 8, '1_5.jpg', '2013-12-04 00:57:00'),
(18, 8, '1_6.jpg', '2013-12-04 00:57:02'),
(19, 8, '1_7.jpg', '2013-12-04 00:57:04'),
(20, 8, '1_8.jpg', '2013-12-04 00:59:30'),
(21, 8, '1_9.jpg', '2013-12-04 00:59:32'),
(22, 8, '1_10.jpg', '2013-12-04 00:59:35'),
(23, 8, '1_11.jpg', '2013-12-04 00:59:37');

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

CREATE TABLE IF NOT EXISTS `marcas` (
  `marca_id` int(11) NOT NULL AUTO_INCREMENT,
  `marca_titulo` varchar(255) NOT NULL,
  `marca_imagem` varchar(100) NOT NULL,
  `marca_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`marca_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `marcas`
--

INSERT INTO `marcas` (`marca_id`, `marca_titulo`, `marca_imagem`, `marca_data`) VALUES
(1, 'marca 1', '2041425346.jpg', '2013-12-02 22:57:10'),
(2, 'marca 2', '1639836928.png', '2013-11-28 22:23:43'),
(3, 'marca 2', '563497281.png', '2013-11-28 22:33:24'),
(4, 'marca 2', '978252173.png', '2013-11-29 01:17:01'),
(5, 'marca 2', '651632196.png', '2013-11-29 01:17:03'),
(6, 'ghjdghj', '1540604461.png', '2013-11-29 01:54:21'),
(7, 'teste', '856707867.png', '2013-12-01 14:32:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(255) NOT NULL,
  `post_content` longtext NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(150) NOT NULL,
  `author_website` varchar(100) DEFAULT NULL,
  `post_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE IF NOT EXISTS `produtos` (
  `produto_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `produto_titulo` varchar(255) NOT NULL,
  `produto_resumo` text NOT NULL,
  `produto_descricao` longtext NOT NULL,
  `produto_preco` decimal(10,2) NOT NULL,
  `produto_img_capa` varchar(255) DEFAULT NULL,
  `produto_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`produto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`produto_id`, `categoria_id`, `sub_cat_id`, `produto_titulo`, `produto_resumo`, `produto_descricao`, `produto_preco`, `produto_img_capa`, `produto_data`) VALUES
(6, 8, 5, 'produto 44', 'hjhgkjhg j', 'klklnk', '88.00', '1_4.jpg', '2013-12-04 23:08:03'),
(7, 1, 1, 'ggfgf dfg dfg df', 'fg dfg sfgsdg dfg', 'df gsdfgsdfg d', '444.00', '1.jpg', '2013-11-24 20:29:08'),
(8, 1, 5, 'ggfgf dfg dfg df', 'fg hfgh f', 'fgh fghf', '33.00', '1_2.jpg', '2013-11-24 20:39:44'),
(9, 1, 2, 'produto 4', 'rte rert ert ert', 'e rtertert ewrt e', '122.00', NULL, '2013-12-08 20:54:49');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_imagens`
--

CREATE TABLE IF NOT EXISTS `produtos_imagens` (
  `prod_img_id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_id` int(11) NOT NULL,
  `prod_img_imagem` varchar(255) DEFAULT NULL,
  `prod_img_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`prod_img_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

--
-- Extraindo dados da tabela `produtos_imagens`
--

INSERT INTO `produtos_imagens` (`prod_img_id`, `produto_id`, `prod_img_imagem`, `prod_img_data`) VALUES
(54, 6, '1_4.jpg', '2013-11-24 00:31:34'),
(55, 6, '1_5.jpg', '2013-11-24 00:31:36'),
(56, 7, '1.jpg', '2013-11-24 20:28:33'),
(57, 7, '1.png', '2013-11-24 20:28:36'),
(58, 7, '1_1.jpg', '2013-11-24 20:28:38'),
(59, 8, '1.jpg', '2013-11-24 20:37:34'),
(62, 8, '1_2.jpg', '2013-11-24 20:37:41'),
(63, 8, '1_1.jpg', '2013-12-06 00:19:23'),
(64, 8, '1_3.jpg', '2013-12-06 00:19:25'),
(65, 8, '1_4.jpg', '2013-12-06 00:19:27'),
(66, 8, '1_5.jpg', '2013-12-06 00:19:30'),
(67, 8, '1_6.jpg', '2013-12-06 00:19:32'),
(68, 8, '1_7.jpg', '2013-12-06 00:19:34'),
(69, 8, '1_8.jpg', '2013-12-06 00:19:36'),
(70, 8, '1_9.jpg', '2013-12-06 00:19:38'),
(71, 8, '1_10.jpg', '2013-12-06 00:19:41');

-- --------------------------------------------------------

--
-- Estrutura da tabela `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'Anonymous'),
(2, 'Registered'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subcategorias`
--

CREATE TABLE IF NOT EXISTS `subcategorias` (
  `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `sub_cat_nome` varchar(100) NOT NULL,
  `sub_cat_descricao` text,
  `sub_cat_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sub_cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `subcategorias`
--

INSERT INTO `subcategorias` (`sub_cat_id`, `categoria_id`, `sub_cat_nome`, `sub_cat_descricao`, `sub_cat_data`) VALUES
(1, 13, 'subcategoria 1 add ALTERADO', 'teste 1 ALTERADO', '2013-11-17 17:21:40'),
(2, 17, 'subcategoria 1 add', 'teste 1', '2013-11-15 20:39:13'),
(5, 31, 'subcategoria 1 add', 'teste 1', '2013-11-15 20:56:24'),
(6, 1, 'subcategoria 1 ffff', 'teste 1 bbon ggilbbh', '2013-11-16 22:40:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` tinyint(1) DEFAULT '1',
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `salt` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_index` (`login`),
  KEY `password_index` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `role_id`, `login`, `password`, `salt`) VALUES
(1, 1, 'Guest', '0ac7ce619d525b58abc6c975b07370716a29ca72', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e'),
(2, 3, 'admin', '9b695e8d7c9cb47592fa3ce3bc6221f4287efede', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e');

-- --------------------------------------------------------

--
-- Estrutura da tabela `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_titulo` varchar(255) NOT NULL,
  `video_miniatura` char(20) NOT NULL,
  `video_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`video_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Extraindo dados da tabela `videos`
--

INSERT INTO `videos` (`video_id`, `video_titulo`, `video_miniatura`, `video_data`) VALUES
(11, 'STILO TURBO MR BORRACHAS.', 'dVyzuV0Dl2s', '2012-05-26 14:17:56'),
(9, 'Equipe MR borrachas terminando o primeiro dia em 1° lugar na categoria Graduado.Dupla José Augusto e Paulo Alcântara (CE).', 'vHBR8oek75Y', '2012-06-20 01:14:07'),
(13, 'teste gilcer', 'UGvEFP5UBbs', '2012-06-18 22:57:36'),
(14, 'teste fuelphp video 1', 'jfLpP2E51Q4', '0000-00-00 00:00:00'),
(15, 'teste ffff', '4zNY3ErJNms', '2013-10-31 00:19:04'),
(16, 'teste 333', 'QhvbK83-Hjc', '2013-10-31 00:42:34'),
(17, 'bob marley 1111', 'p963CeTtJVM', '2013-10-31 22:26:39'),
(18, 'bob marley 2', 'y1FtneYOoaA', '2013-10-31 22:29:14'),
(19, 'bob marley 3', '5fUqfgxwsKA', '2013-10-31 22:30:22'),
(21, 'testes', '5r4cJ8TnJU8', '2013-11-30 03:22:31'),
(22, 'testes zend', 'ieXjxAjQN2U', '2013-11-30 03:36:25');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `acl_to_roles`
--
ALTER TABLE `acl_to_roles`
  ADD CONSTRAINT `acl_to_roles_ibfk_1` FOREIGN KEY (`acl_id`) REFERENCES `acl` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acl_to_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
