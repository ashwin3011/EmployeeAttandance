-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 03, 2023 at 01:45 AM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biomatric`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `UID` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(50) NOT NULL,
  `Password` text NOT NULL,
  `IsoTemplate` text NOT NULL,
  `IsAct` tinyint(1) NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`UID`, `Email`, `Password`, `IsoTemplate`, `IsAct`) VALUES
(1, 'admin@drrajeshdave.com', '21232f297a57a5a743894a0e4a801fc3', 'Rk1SACAyMAAAAAG8AAABPAFiAMUAxQEAAAAoRUCbAKe2AEChAMHXAECwAJRuAIB7AKYqAECgAHmHAEB1AMjSAEDWALbAAECJAN9PAIBxANXRAEBbAKq2AEDoAItOAICcAFiAAIDPAPFAAEB9AQHaAEBFAIigAID6AM5JAEB2AEuQAEBiAFMQAEDjAQQ4AEESANPHAEEFAPPnAIBDAP5FAIDnAC/mAICkAKu8AEC1ALXFAIDBAKzGAIC8AJXRAICHAIEIAEBtALrBAEDUAL6qAIBvAMw9AECIAGyTAEBoANTMAECLAPNOAEDwAMLFAEBzAFyUAICXAQRSAEC+AQU2AEA8AKqtAIEDAMDLAEDdAP81AIESAMJUAED4AFXiAIBAAFaaAECvACruAEDwACluAICOAKmxAIChAI6pAECKAI6mAIDDALtGAECbANvPAEDUAKdTAIB3AH+dAICtAOZSAIDGAN84AEBcAJeZAEBUAJkaAIDmAHzOAED6AKDaAIB2AFQHAIDiAPK1AIByAQFMAIBHAODEAIA3AMm1AEA/AGSWAIA8APHKAEEBAFdiAEAlAOK4AEC8AAp3AAAA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `biomatricdata`
--

DROP TABLE IF EXISTS `biomatricdata`;
CREATE TABLE IF NOT EXISTS `biomatricdata` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `UID` int NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Contact` int NOT NULL,
  `Address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Bitmap` text NOT NULL,
  `Quality` text NOT NULL,
  `Nfic` text NOT NULL,
  `InWidth` text NOT NULL,
  `InHeight` text NOT NULL,
  `InArea` text NOT NULL,
  `Resolution` text NOT NULL,
  `GrayScale` text NOT NULL,
  `Bpp` text NOT NULL,
  `WsqCompressRatio` text NOT NULL,
  `WsqInfo` text NOT NULL,
  `IsoTemplate` text NOT NULL,
  `AnsiTemplate` tinytext NOT NULL,
  `IsoImage` text NOT NULL,
  `RawData` text NOT NULL,
  `WsqImage` text NOT NULL,
  `CaseId` int NOT NULL,
  `CreateDate` date NOT NULL,
  `IsAct` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `biomatricdata`
--

INSERT INTO `biomatricdata` (`ID`, `UID`, `Name`, `Contact`, `Address`, `Bitmap`, `Quality`, `Nfic`, `InWidth`, `InHeight`, `InArea`, `Resolution`, `GrayScale`, `Bpp`, `WsqCompressRatio`, `WsqInfo`, `IsoTemplate`, `AnsiTemplate`, `IsoImage`, `RawData`, `WsqImage`, `CaseId`, `CreateDate`, `IsAct`) VALUES
(1, 0, 'Neeta', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2023-02-26', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
