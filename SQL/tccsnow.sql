-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Nov-2025 às 16:32
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tccsnow`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `diario`
--

CREATE TABLE `diario` (
  `id_diario` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_registro` date DEFAULT NULL,
  `dia_semana` char(1) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `diario`
--

INSERT INTO `diario` (`id_diario`, `descricao`, `data_registro`, `dia_semana`, `id_usuario`) VALUES
(66, 'oiteste', '2025-11-25', '2', 16),
(67, 'oi', '2025-11-13', '4', 16),
(68, 'erica', '2025-11-14', '6', 16),
(74, 'oiioiiiiii', '2025-11-09', '7', 16),
(75, 'mal', '2025-12-11', '4', 16),
(76, 'foi muito intenso', '2025-11-26', '3', 17),
(81, 'ruimm', '2025-11-11', '2', 20),
(83, 'oi', '2025-11-04', '2', 0),
(84, 'MUITO MAL', '2025-11-26', '3', 16),
(85, 'oi', '2025-11-04', '2', 16),
(86, 'iuiij', '2025-11-05', '3', 16),
(87, 'oi', '2025-11-03', '1', 16),
(89, 'oi', '2025-11-15', '6', 16),
(90, 'estou muito ansiosa para a apresentação\\r\\n', '2025-11-26', '3', 22),
(91, 'jdwjwd', '2025-11-19', '3', 22),
(93, 'muito bomm', '2025-11-26', '3', 24);

-- --------------------------------------------------------

--
-- Estrutura da tabela `diario_emocao`
--

CREATE TABLE `diario_emocao` (
  `id_diarioemocao` int(11) NOT NULL,
  `id_diario` int(11) DEFAULT NULL,
  `id_emocao` int(11) DEFAULT NULL,
  `intensidade` char(1) DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `diario_emocao`
--

INSERT INTO `diario_emocao` (`id_diarioemocao`, `id_diario`, `id_emocao`, `intensidade`, `hora`) VALUES
(100, 90, 2, '1', '13:50:11');

-- --------------------------------------------------------

--
-- Estrutura da tabela `emocao`
--

CREATE TABLE `emocao` (
  `id_emocao` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `icone` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `emocao`
--

INSERT INTO `emocao` (`id_emocao`, `nome`, `icone`) VALUES
(1, 'Tristeza', 0x5472697374657a612e706e67),
(2, 'Ansiedade', 0x416e73696f736f2e706e67),
(3, 'Estresse', 0x52616976612e706e67),
(4, 'Alegria', 0x46656c697a2e706e67),
(6, 'Preocupação', 0x5072656f63757061646f2e706e67),
(28, 'Frustaçao', 0x467275737461c3a7c3a36f2e706e67);

-- --------------------------------------------------------

--
-- Estrutura da tabela `feedback`
--

CREATE TABLE `feedback` (
  `id_feedback` int(11) NOT NULL,
  `id_historico` int(11) DEFAULT NULL,
  `data_feedback` date DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `avaliacao` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `feedback`
--

INSERT INTO `feedback` (`id_feedback`, `id_historico`, `data_feedback`, `comentario`, `avaliacao`) VALUES
(16, NULL, '2025-11-26', 'muito boaaaa', 'M'),
(17, NULL, '2025-11-26', 'top', 'M');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

CREATE TABLE `historico` (
  `id_historico` int(11) NOT NULL,
  `data` date DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `id_diario` int(11) DEFAULT NULL,
  `id_tecnicas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tecnicas`
--

CREATE TABLE `tecnicas` (
  `id_tecnicas` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `tempo_estimado` time DEFAULT NULL,
  `video` longblob DEFAULT NULL,
  `categoria` char(1) DEFAULT NULL,
  `data_criacao` date DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tecnicas`
--

INSERT INTO `tecnicas` (`id_tecnicas`, `nome`, `tempo_estimado`, `video`, `categoria`, `data_criacao`, `descricao`) VALUES
(1, '5-4-3-2-1', '05:00:00', 0x68747470733a2f2f7777772e796f75747562652e636f6d2f656d6265642f69334247374732373061773f73693d77426f486a46463768534a4839495151, 'R', '2024-11-25', ' Esta Técnica, tem como objetivo aliviar rápido aansiedade. Use seus sentidos: identifique 5 coisas que vê, 4 que pode tocar, 3 que ouve, 2 que cheira e 1 que saboreia. Essa prática interrompe pensamentos acelerados, trazendo sua mente de volta para o momento presente. É uma âncora sensorial para recuperar o controle em segundos.'),
(2, '4-4-4', '03:00:00', 0x68747470733a2f2f796f7574752e62652f53494233516755377573633f73693d7379374735716272436e72716f514871, 'R', '2025-11-11', ' A técnica de respiração 4-4-4-4 é uma ferramenta poderosa, portátil e gratuita para recuperar o controlo sobre o seu estado mental e emocional em qualquer lugar e a qualquer momento.'),
(27, 'vela', '00:02:00', 0x68747470733a2f2f7777772e796f75747562652e636f6d2f656d6265642f687241575274524f676f6f3f73693d4c78313063487366334b6f44796f4370, 'M', '2025-11-26', ' É um exercício de respiração para acalmar a ansiedade. Para executá-la, imagine-se cheirando o perfume de uma flor (inspire lentamente pelo nariz) e, em seguida, soprando suavemente uma vela (expire lentamente pela boca). Repita o processo, focando na respiração p');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `login` varchar(50) DEFAULT NULL,
  `senha` varchar(50) DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `login`, `senha`, `tipo`) VALUES
(2, 'admin@gmail.com', 'senhaadmin', 'A');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `diario`
--
ALTER TABLE `diario`
  ADD PRIMARY KEY (`id_diario`);

--
-- Índices para tabela `diario_emocao`
--
ALTER TABLE `diario_emocao`
  ADD PRIMARY KEY (`id_diarioemocao`),
  ADD KEY `id_diario` (`id_diario`),
  ADD KEY `id_emocao` (`id_emocao`);

--
-- Índices para tabela `emocao`
--
ALTER TABLE `emocao`
  ADD PRIMARY KEY (`id_emocao`);

--
-- Índices para tabela `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id_feedback`),
  ADD KEY `id_historico` (`id_historico`);

--
-- Índices para tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id_historico`),
  ADD KEY `id_diario` (`id_diario`),
  ADD KEY `id_tecnicas` (`id_tecnicas`);

--
-- Índices para tabela `tecnicas`
--
ALTER TABLE `tecnicas`
  ADD PRIMARY KEY (`id_tecnicas`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `diario`
--
ALTER TABLE `diario`
  MODIFY `id_diario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de tabela `diario_emocao`
--
ALTER TABLE `diario_emocao`
  MODIFY `id_diarioemocao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de tabela `emocao`
--
ALTER TABLE `emocao`
  MODIFY `id_emocao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id_feedback` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id_historico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tecnicas`
--
ALTER TABLE `tecnicas`
  MODIFY `id_tecnicas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `diario_emocao`
--
ALTER TABLE `diario_emocao`
  ADD CONSTRAINT `diario_emocao_ibfk_1` FOREIGN KEY (`id_diario`) REFERENCES `diario` (`id_diario`),
  ADD CONSTRAINT `diario_emocao_ibfk_2` FOREIGN KEY (`id_emocao`) REFERENCES `emocao` (`id_emocao`);

--
-- Limitadores para a tabela `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`id_historico`) REFERENCES `historico` (`id_historico`);

--
-- Limitadores para a tabela `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `historico_ibfk_1` FOREIGN KEY (`id_diario`) REFERENCES `diario` (`id_diario`),
  ADD CONSTRAINT `historico_ibfk_2` FOREIGN KEY (`id_tecnicas`) REFERENCES `tecnicas` (`id_tecnicas`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
