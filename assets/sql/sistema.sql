-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/10/2023 às 12:22
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidade`
--

CREATE TABLE `cidade` (
  `idCid` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `uf` char(2) NOT NULL,
  `qtdeCli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cidade`
--

INSERT INTO `cidade` (`idCid`, `descricao`, `uf`, `qtdeCli`) VALUES
(1, 'Tarumã', 'SP', 0),
(2, 'Londrina', 'PR', 0),
(3, 'Assis', 'SP', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `idCli` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `cidade_idCid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`idCli`, `nome`, `cidade_idCid`) VALUES
(14, 'Miryan', 1),
(16, 'Aurora', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `idFor` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`idFor`, `descricao`) VALUES
(49, 'Terabyte'),
(50, 'Pichau'),
(53, 'teste');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itemvendas`
--

CREATE TABLE `itemvendas` (
  `idtmVenda` int(11) NOT NULL,
  `produto_idProd` int(11) NOT NULL,
  `venda_idVenda` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtofornecedor`
--

CREATE TABLE `produtofornecedor` (
  `Fornecedores_idFor` int(11) NOT NULL,
  `Produtos_idProd` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `descricao` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor` float NOT NULL,
  `idProd` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`descricao`, `quantidade`, `valor`, `idProd`) VALUES
('Chocolate', 3, 3, 13);

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `idVenda` int(11) NOT NULL,
  `cliente_idCli` int(11) NOT NULL,
  `dataVenda` date NOT NULL,
  `dataVcto` date NOT NULL,
  `dataPgto` date NOT NULL,
  `valor` float NOT NULL,
  `valorPg` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cidade`
--
ALTER TABLE `cidade`
  ADD PRIMARY KEY (`idCid`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCli`),
  ADD KEY `clientes_idCli` (`cidade_idCid`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`idFor`);

--
-- Índices de tabela `itemvendas`
--
ALTER TABLE `itemvendas`
  ADD PRIMARY KEY (`idtmVenda`),
  ADD KEY `produto_idProd` (`produto_idProd`),
  ADD KEY `venda_idVenda` (`venda_idVenda`);

--
-- Índices de tabela `produtofornecedor`
--
ALTER TABLE `produtofornecedor`
  ADD PRIMARY KEY (`Fornecedores_idFor`),
  ADD KEY `Produtos_idProd` (`Produtos_idProd`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idProd`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`idVenda`),
  ADD KEY `cliente_idCli` (`cliente_idCli`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cidade`
--
ALTER TABLE `cidade`
  MODIFY `idCid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idFor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `itemvendas`
--
ALTER TABLE `itemvendas`
  MODIFY `idtmVenda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `idVenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_idCli` FOREIGN KEY (`cidade_idCid`) REFERENCES `cidade` (`idCid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `itemvendas`
--
ALTER TABLE `itemvendas`
  ADD CONSTRAINT `produto_idProd` FOREIGN KEY (`produto_idProd`) REFERENCES `produtos` (`idProd`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `venda_idVenda` FOREIGN KEY (`venda_idVenda`) REFERENCES `vendas` (`idVenda`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `produtofornecedor`
--
ALTER TABLE `produtofornecedor`
  ADD CONSTRAINT `Fornecedores_idFor` FOREIGN KEY (`Fornecedores_idFor`) REFERENCES `fornecedores` (`idFor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Produtos_idProd` FOREIGN KEY (`Produtos_idProd`) REFERENCES `produtos` (`idProd`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `cliente_idCli` FOREIGN KEY (`cliente_idCli`) REFERENCES `clientes` (`idCli`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
