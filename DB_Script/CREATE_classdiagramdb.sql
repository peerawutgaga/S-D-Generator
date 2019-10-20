-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2019 at 08:45 AM
-- Server version: 5.6.34-log
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `classdiagramdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `ClassId` int(11) NOT NULL,
  `PackageId` int(11) NOT NULL,
  `ClassName` varchar(255) COLLATE utf8_bin NOT NULL,
  `InstanceType` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `diagram`
--

CREATE TABLE `diagram` (
  `DiagramId` int(10) NOT NULL,
  `DiagramName` varchar(255) COLLATE utf8_bin NOT NULL,
  `FilePath` mediumtext COLLATE utf8_bin NOT NULL,
  `CreateTimeStamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inheritance`
--

CREATE TABLE `inheritance` (
  `InheritId` int(10) NOT NULL,
  `SuperClassId` int(10) NOT NULL,
  `ChildClassId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `method`
--

CREATE TABLE `method` (
  `MethodId` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `MethodName` varchar(255) COLLATE utf8_bin NOT NULL,
  `Visibility` varchar(255) COLLATE utf8_bin NOT NULL,
  `ReturnType` varchar(255) COLLATE utf8_bin NOT NULL,
  `InstanceType` varchar(255) COLLATE utf8_bin NOT NULL,
  `isConstructor` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `PackageId` int(10) NOT NULL,
  `DiagramId` int(10) NOT NULL,
  `PackageName` varchar(255) COLLATE utf8_bin NOT NULL,
  `Namespace` mediumtext COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `param`
--

CREATE TABLE `param` (
  `ParamId` int(10) NOT NULL,
  `MethodId` int(10) NOT NULL,
  `ParamName` varchar(255) COLLATE utf8_bin NOT NULL,
  `SequenceIndex` tinyint(2) NOT NULL,
  `ParamType` varchar(255) COLLATE utf8_bin NOT NULL,
  `isObject` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`ClassId`),
  ADD KEY `ClassPackageFK` (`PackageId`);

--
-- Indexes for table `diagram`
--
ALTER TABLE `diagram`
  ADD PRIMARY KEY (`DiagramId`);

--
-- Indexes for table `inheritance`
--
ALTER TABLE `inheritance`
  ADD PRIMARY KEY (`InheritId`),
  ADD KEY `InheritanceClassFK` (`SuperClassId`);

--
-- Indexes for table `method`
--
ALTER TABLE `method`
  ADD PRIMARY KEY (`MethodId`),
  ADD KEY `MethodClassFK` (`ClassId`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`PackageId`),
  ADD KEY `PackageDiagramFK` (`DiagramId`);

--
-- Indexes for table `param`
--
ALTER TABLE `param`
  ADD PRIMARY KEY (`ParamId`),
  ADD KEY `ParamMethodFK` (`MethodId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `ClassId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `diagram`
--
ALTER TABLE `diagram`
  MODIFY `DiagramId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inheritance`
--
ALTER TABLE `inheritance`
  MODIFY `InheritId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `method`
--
ALTER TABLE `method`
  MODIFY `MethodId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `PackageId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `param`
--
ALTER TABLE `param`
  MODIFY `ParamId` int(10) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `ClassPackageFK` FOREIGN KEY (`PackageId`) REFERENCES `package` (`PackageId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inheritance`
--
ALTER TABLE `inheritance`
  ADD CONSTRAINT `InheritanceClassFK` FOREIGN KEY (`SuperClassId`) REFERENCES `class` (`ClassId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `method`
--
ALTER TABLE `method`
  ADD CONSTRAINT `MethodClassFK` FOREIGN KEY (`ClassId`) REFERENCES `class` (`ClassId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `PackageDiagramFK` FOREIGN KEY (`DiagramId`) REFERENCES `diagram` (`DiagramId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `param`
--
ALTER TABLE `param`
  ADD CONSTRAINT `ParamMethodFK` FOREIGN KEY (`MethodId`) REFERENCES `method` (`MethodId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
