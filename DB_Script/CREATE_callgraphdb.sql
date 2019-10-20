-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2019 at 08:22 AM
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
-- Database: `callgraphdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `argument`
--

CREATE TABLE `argument` (
  `ArguId` int(10) NOT NULL,
  `MessageId` int(10) NOT NULL,
  `ArguName` varchar(255) COLLATE utf8_bin NOT NULL,
  `SequenceIndex` tinyint(2) NOT NULL,
  `DataType` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `callgraph`
--

CREATE TABLE `callgraph` (
  `CallGraphId` int(10) NOT NULL,
  `CallGraphName` varchar(255) COLLATE utf8_bin NOT NULL,
  `FilePath` mediumtext COLLATE utf8_bin NOT NULL,
  `CreateTimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `gateobject`
--

CREATE TABLE `gateobject` (
  `GateObjId` int(10) NOT NULL,
  `CallGraphId` int(10) NOT NULL,
  `ObjectId` int(10) NOT NULL,
  `MessageId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `guardcondition`
--

CREATE TABLE `guardcondition` (
  `GuardCondId` int(10) NOT NULL,
  `MessageId` int(10) NOT NULL,
  `Statement` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `MessageId` int(10) NOT NULL,
  `FromObjectId` int(10) NOT NULL,
  `ToObjectId` int(10) NOT NULL,
  `MessageName` varchar(255) COLLATE utf8_bin NOT NULL,
  `MessageType` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `object`
--

CREATE TABLE `object` (
  `Objectid` int(10) NOT NULL,
  `CallGraphId` int(10) NOT NULL,
  `ObjectName` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `referencediagram`
--

CREATE TABLE `referencediagram` (
  `RefDiagramId` int(10) NOT NULL,
  `CallGraphId` int(10) NOT NULL,
  `ObjectId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `returnmessage`
--

CREATE TABLE `returnmessage` (
  `ReturnMsgId` int(10) NOT NULL,
  `MessageId` int(10) NOT NULL,
  `DataType` varchar(255) COLLATE utf8_bin NOT NULL,
  `ParentMsgId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `argument`
--
ALTER TABLE `argument`
  ADD PRIMARY KEY (`ArguId`),
  ADD KEY `ArgumentMessageFK` (`MessageId`);

--
-- Indexes for table `callgraph`
--
ALTER TABLE `callgraph`
  ADD PRIMARY KEY (`CallGraphId`),
  ADD UNIQUE KEY `CallGraphId` (`CallGraphId`);

--
-- Indexes for table `gateobject`
--
ALTER TABLE `gateobject`
  ADD PRIMARY KEY (`GateObjId`),
  ADD KEY `GateObjCallGraphFK` (`CallGraphId`),
  ADD KEY `GateObjObjectFK` (`ObjectId`),
  ADD KEY `GateObjMessageFK` (`MessageId`);

--
-- Indexes for table `guardcondition`
--
ALTER TABLE `guardcondition`
  ADD PRIMARY KEY (`GuardCondId`),
  ADD KEY `GuardCondMessageFK` (`MessageId`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`MessageId`),
  ADD KEY `MessageObjectFK` (`FromObjectId`);

--
-- Indexes for table `object`
--
ALTER TABLE `object`
  ADD PRIMARY KEY (`Objectid`),
  ADD KEY `ObjectCallGraphFK` (`CallGraphId`);

--
-- Indexes for table `referencediagram`
--
ALTER TABLE `referencediagram`
  ADD PRIMARY KEY (`RefDiagramId`),
  ADD KEY `RefObjectFK` (`CallGraphId`);

--
-- Indexes for table `returnmessage`
--
ALTER TABLE `returnmessage`
  ADD PRIMARY KEY (`ReturnMsgId`),
  ADD KEY `ReturnMsgMessageFK` (`MessageId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `argument`
--
ALTER TABLE `argument`
  MODIFY `ArguId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `callgraph`
--
ALTER TABLE `callgraph`
  MODIFY `CallGraphId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gateobject`
--
ALTER TABLE `gateobject`
  MODIFY `GateObjId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `guardcondition`
--
ALTER TABLE `guardcondition`
  MODIFY `GuardCondId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `MessageId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `object`
--
ALTER TABLE `object`
  MODIFY `Objectid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `referencediagram`
--
ALTER TABLE `referencediagram`
  MODIFY `RefDiagramId` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `returnmessage`
--
ALTER TABLE `returnmessage`
  MODIFY `ReturnMsgId` int(10) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `argument`
--
ALTER TABLE `argument`
  ADD CONSTRAINT `ArgumentMessageFK` FOREIGN KEY (`MessageId`) REFERENCES `message` (`MessageId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gateobject`
--
ALTER TABLE `gateobject`
  ADD CONSTRAINT `GateObjCallGraphFK` FOREIGN KEY (`CallGraphId`) REFERENCES `callgraph` (`CallGraphId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `GateObjMessageFK` FOREIGN KEY (`MessageId`) REFERENCES `message` (`MessageId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `GateObjObjectFK` FOREIGN KEY (`ObjectId`) REFERENCES `object` (`Objectid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guardcondition`
--
ALTER TABLE `guardcondition`
  ADD CONSTRAINT `GuardCondMessageFK` FOREIGN KEY (`MessageId`) REFERENCES `message` (`MessageId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `MessageObjectFK` FOREIGN KEY (`FromObjectId`) REFERENCES `object` (`Objectid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `object`
--
ALTER TABLE `object`
  ADD CONSTRAINT `ObjectCallGraphFK` FOREIGN KEY (`CallGraphId`) REFERENCES `callgraph` (`CallGraphId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `referencediagram`
--
ALTER TABLE `referencediagram`
  ADD CONSTRAINT `RefCallGraphFK` FOREIGN KEY (`CallGraphId`) REFERENCES `callgraph` (`CallGraphId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RefObjectFK` FOREIGN KEY (`CallGraphId`) REFERENCES `object` (`Objectid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `returnmessage`
--
ALTER TABLE `returnmessage`
  ADD CONSTRAINT `ReturnMsgMessageFK` FOREIGN KEY (`MessageId`) REFERENCES `message` (`MessageId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
