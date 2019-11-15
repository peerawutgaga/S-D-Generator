-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 14, 2019 at 01:50 PM
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
-- Database: `sdgeneratordb`
--

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.argument`
--

CREATE TABLE `callgraph.argument` (
  `arguId` int(10) NOT NULL COMMENT 'Argument Id. This field is meaningless running number',
  `messageId` int(10) NOT NULL COMMENT 'Parent message Id',
  `arguName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Argument name',
  `seqIdx` int(2) NOT NULL COMMENT 'Sequence index in message',
  `dataType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Argument data type',
  `paramId` int(10) DEFAULT NULL COMMENT 'Reference parameter Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records arguments of each message';

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.gateobject`
--

CREATE TABLE `callgraph.gateobject` (
  `objectId` int(10) NOT NULL COMMENT 'Object Id, This field refers to object Id in object table',
  `callGraphId` int(10) NOT NULL COMMENT 'Reference call graph Id',
  `gateMsgId` int(10) NOT NULL COMMENT 'Gate invoking message Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records gate objects to other call graph';

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.graph`
--

CREATE TABLE `callgraph.graph` (
  `callGraphId` int(10) NOT NULL COMMENT 'Call graph ID. This field is meaningless running number',
  `callGraphName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Call graph name',
  `filePath` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'XML file path in repository',
  `createTimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Upload timestamp',
  `classDiagramId` int(10) DEFAULT NULL COMMENT 'Reference class diagram ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records call graph information';

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.graphprocessing`
--

CREATE TABLE `callgraph.graphprocessing` (
  `FromObjectId` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Source object original Id',
  `ToObjectId` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Destination object original Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table is process call graph working DB';

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.guardcondition`
--

CREATE TABLE `callgraph.guardcondition` (
  `guardCondId` int(10) NOT NULL COMMENT 'Guard condition ID. This field is meaningless running number.',
  `messageId` int(10) NOT NULL COMMENT 'Corresponding message Id',
  `statement` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Condition statement'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records guard condition which annotates to specific message';

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.message`
--

CREATE TABLE `callgraph.message` (
  `messageId` int(10) NOT NULL COMMENT 'Message Id. This field is meaningless running number',
  `fromObjectId` int(10) NOT NULL COMMENT 'Source object Id',
  `toObjectId` int(10) NOT NULL COMMENT 'Destination object Id',
  `messageName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Message name',
  `messageType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Message type; Calling, Create, Destroy, Return',
  `methodId` int(10) DEFAULT NULL COMMENT 'Reference method Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records message between object nodes';

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.objectnode`
--

CREATE TABLE `callgraph.objectnode` (
  `objectId` int(10) NOT NULL COMMENT 'Object Id. This field is meaningless running number',
  `callGraphId` int(10) NOT NULL COMMENT 'Parent call graph Id',
  `objectName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Object name',
  `classId` int(10) DEFAULT NULL COMMENT 'Reference class Id',
  `baseIdentifier` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Base identifier object name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records object nodes in each call graph';

-- --------------------------------------------------------

--
-- Table structure for table `callgraph.returnmessage`
--

CREATE TABLE `callgraph.returnmessage` (
  `messageId` int(10) NOT NULL COMMENT 'Message Id. This field is refer to message id in message table.',
  `dataType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Return message data type',
  `parentMessageId` int(10) NOT NULL COMMENT 'Previous calling message Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records returning message';

-- --------------------------------------------------------

--
-- Table structure for table `classdiagram.class`
--

CREATE TABLE `classdiagram.class` (
  `classId` int(10) NOT NULL COMMENT 'Class Id. This field is meaning less running number',
  `packageId` int(10) NOT NULL COMMENT 'Contained package Id',
  `className` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Class name',
  `instanceType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Class instance type. E.g. Concrete, Abstract, Interface'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records classes in each package';

-- --------------------------------------------------------

--
-- Table structure for table `classdiagram.diagram`
--

CREATE TABLE `classdiagram.diagram` (
  `diagramId` int(10) NOT NULL COMMENT 'Class diagram ID. This field is meaningless running number',
  `diagramName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Class diagram name',
  `filePath` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'XML file path in repository',
  `createTimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Upload timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records class diagram info';

-- --------------------------------------------------------

--
-- Table structure for table `classdiagram.inheritance`
--

CREATE TABLE `classdiagram.inheritance` (
  `inheritId` int(10) NOT NULL COMMENT 'Inheritance pair Id. This field is meaning less running number',
  `superClassId` int(10) NOT NULL COMMENT 'Super class id',
  `childClassId` int(10) NOT NULL COMMENT 'Descendant class Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records inheritance between classes';

-- --------------------------------------------------------

--
-- Table structure for table `classdiagram.method`
--

CREATE TABLE `classdiagram.method` (
  `methodId` int(10) NOT NULL COMMENT 'Method Id. This field is meaningless running number',
  `classId` int(10) NOT NULL COMMENT 'Class Id',
  `methodName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Method name',
  `visibility` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Visibility of method. E.g. public, private',
  `returnType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Method return type',
  `instanceType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Method abstraction. E.g.concrete, abstract, static',
  `isConstructor` tinyint(3) NOT NULL COMMENT 'Indicator whether method is a constructor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records methods of each class';

-- --------------------------------------------------------

--
-- Table structure for table `classdiagram.package`
--

CREATE TABLE `classdiagram.package` (
  `packageId` int(10) NOT NULL COMMENT 'Package Id. This field is meaning less running number',
  `diagramId` int(10) NOT NULL COMMENT 'Reference class diagram Id',
  `packageName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Package name',
  `namespace` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Package namespace'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records packages in class diagram';

-- --------------------------------------------------------

--
-- Table structure for table `classdiagram.param`
--

CREATE TABLE `classdiagram.param` (
  `paramId` int(10) NOT NULL COMMENT 'Parameter Id. This field is meaningless running number',
  `methodId` int(10) NOT NULL COMMENT 'Method Id.',
  `paramName` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Parameter name',
  `dataType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Parameter''s data type',
  `seqIdx` int(10) NOT NULL COMMENT 'Parameter sequence index',
  `isObject` tinyint(3) NOT NULL COMMENT 'Indicator whether parameter data type is an object'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records parameter list of each method';

-- --------------------------------------------------------

--
-- Table structure for table `code.sourcecodefile`
--

CREATE TABLE `code.sourcecodefile` (
  `fileId` int(10) NOT NULL COMMENT 'File Id. This field is meaningless running number',
  `filename` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Filename',
  `filePayload` longtext COLLATE utf8_bin NOT NULL COMMENT 'File content',
  `language` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Source code language. E.g. Java',
  `createTimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create timestamp',
  `lastUpdateTimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update timestamp',
  `sourceType` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Source code type. E.g. Stub'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table records source code content';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `callgraph.argument`
--
ALTER TABLE `callgraph.argument`
  ADD PRIMARY KEY (`arguId`),
  ADD KEY `ArgumentMessageFK` (`messageId`),
  ADD KEY `ArguParamFK` (`paramId`);

--
-- Indexes for table `callgraph.gateobject`
--
ALTER TABLE `callgraph.gateobject`
  ADD PRIMARY KEY (`objectId`),
  ADD KEY `GateObjObjectFK` (`gateMsgId`),
  ADD KEY `GateObjCallGraphFK` (`callGraphId`);

--
-- Indexes for table `callgraph.graph`
--
ALTER TABLE `callgraph.graph`
  ADD PRIMARY KEY (`callGraphId`),
  ADD KEY `GraphDiagramFK` (`classDiagramId`);

--
-- Indexes for table `callgraph.graphprocessing`
--
ALTER TABLE `callgraph.graphprocessing`
  ADD PRIMARY KEY (`FromObjectId`);

--
-- Indexes for table `callgraph.guardcondition`
--
ALTER TABLE `callgraph.guardcondition`
  ADD PRIMARY KEY (`guardCondId`),
  ADD KEY `GuardConditionMessageFK` (`messageId`);

--
-- Indexes for table `callgraph.message`
--
ALTER TABLE `callgraph.message`
  ADD PRIMARY KEY (`messageId`),
  ADD KEY `MessageObjectFK` (`fromObjectId`),
  ADD KEY `MessageMethodFK` (`methodId`);

--
-- Indexes for table `callgraph.objectnode`
--
ALTER TABLE `callgraph.objectnode`
  ADD PRIMARY KEY (`objectId`),
  ADD KEY `ObjectNodeCallGraphFK` (`callGraphId`),
  ADD KEY `ObjectClassFK` (`classId`);

--
-- Indexes for table `callgraph.returnmessage`
--
ALTER TABLE `callgraph.returnmessage`
  ADD PRIMARY KEY (`messageId`);

--
-- Indexes for table `classdiagram.class`
--
ALTER TABLE `classdiagram.class`
  ADD PRIMARY KEY (`classId`),
  ADD KEY `ClassPackageFK` (`packageId`);

--
-- Indexes for table `classdiagram.diagram`
--
ALTER TABLE `classdiagram.diagram`
  ADD PRIMARY KEY (`diagramId`);

--
-- Indexes for table `classdiagram.inheritance`
--
ALTER TABLE `classdiagram.inheritance`
  ADD PRIMARY KEY (`inheritId`),
  ADD KEY `InheritanceClassFK` (`superClassId`);

--
-- Indexes for table `classdiagram.method`
--
ALTER TABLE `classdiagram.method`
  ADD PRIMARY KEY (`methodId`),
  ADD KEY `MethodClassFK` (`classId`);

--
-- Indexes for table `classdiagram.package`
--
ALTER TABLE `classdiagram.package`
  ADD PRIMARY KEY (`packageId`),
  ADD KEY `PackageDiagramFK` (`diagramId`);

--
-- Indexes for table `classdiagram.param`
--
ALTER TABLE `classdiagram.param`
  ADD PRIMARY KEY (`paramId`),
  ADD KEY `ParamMethodFK` (`methodId`);

--
-- Indexes for table `code.sourcecodefile`
--
ALTER TABLE `code.sourcecodefile`
  ADD PRIMARY KEY (`fileId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `callgraph.argument`
--
ALTER TABLE `callgraph.argument`
  MODIFY `arguId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Argument Id. This field is meaningless running number', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `callgraph.graph`
--
ALTER TABLE `callgraph.graph`
  MODIFY `callGraphId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Call graph ID. This field is meaningless running number', AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `callgraph.guardcondition`
--
ALTER TABLE `callgraph.guardcondition`
  MODIFY `guardCondId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Guard condition ID. This field is meaningless running number.', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `callgraph.message`
--
ALTER TABLE `callgraph.message`
  MODIFY `messageId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Message Id. This field is meaningless running number', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `callgraph.objectnode`
--
ALTER TABLE `callgraph.objectnode`
  MODIFY `objectId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Object Id. This field is meaningless running number', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `classdiagram.class`
--
ALTER TABLE `classdiagram.class`
  MODIFY `classId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Class Id. This field is meaning less running number', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `classdiagram.diagram`
--
ALTER TABLE `classdiagram.diagram`
  MODIFY `diagramId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Class diagram ID. This field is meaningless running number', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `classdiagram.inheritance`
--
ALTER TABLE `classdiagram.inheritance`
  MODIFY `inheritId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Inheritance pair Id. This field is meaning less running number', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `classdiagram.method`
--
ALTER TABLE `classdiagram.method`
  MODIFY `methodId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Method Id. This field is meaningless running number', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `classdiagram.package`
--
ALTER TABLE `classdiagram.package`
  MODIFY `packageId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Package Id. This field is meaning less running number', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `classdiagram.param`
--
ALTER TABLE `classdiagram.param`
  MODIFY `paramId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Parameter Id. This field is meaningless running number', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `code.sourcecodefile`
--
ALTER TABLE `code.sourcecodefile`
  MODIFY `fileId` int(10) NOT NULL AUTO_INCREMENT COMMENT 'File Id. This field is meaningless running number', AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `callgraph.argument`
--
ALTER TABLE `callgraph.argument`
  ADD CONSTRAINT `ArguParamFK` FOREIGN KEY (`paramId`) REFERENCES `classdiagram.param` (`paramId`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `ArgumentMessageFK` FOREIGN KEY (`messageId`) REFERENCES `callgraph.message` (`messageId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `callgraph.gateobject`
--
ALTER TABLE `callgraph.gateobject`
  ADD CONSTRAINT `GateObjCallGraphFK` FOREIGN KEY (`callGraphId`) REFERENCES `callgraph.graph` (`callGraphId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `GateObjObjectFK` FOREIGN KEY (`objectId`) REFERENCES `callgraph.objectnode` (`objectId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `callgraph.graph`
--
ALTER TABLE `callgraph.graph`
  ADD CONSTRAINT `GraphDiagramFK` FOREIGN KEY (`classDiagramId`) REFERENCES `classdiagram.diagram` (`diagramId`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `callgraph.guardcondition`
--
ALTER TABLE `callgraph.guardcondition`
  ADD CONSTRAINT `GuardConditionMessageFK` FOREIGN KEY (`messageId`) REFERENCES `callgraph.message` (`messageId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `callgraph.message`
--
ALTER TABLE `callgraph.message`
  ADD CONSTRAINT `MessageMethodFK` FOREIGN KEY (`methodId`) REFERENCES `classdiagram.method` (`methodId`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `MessageObjectFK` FOREIGN KEY (`fromObjectId`) REFERENCES `callgraph.objectnode` (`objectId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `callgraph.objectnode`
--
ALTER TABLE `callgraph.objectnode`
  ADD CONSTRAINT `ObjectClassFK` FOREIGN KEY (`classId`) REFERENCES `classdiagram.class` (`classId`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `ObjectNodeCallGraphFK` FOREIGN KEY (`callGraphId`) REFERENCES `callgraph.graph` (`callGraphId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `callgraph.returnmessage`
--
ALTER TABLE `callgraph.returnmessage`
  ADD CONSTRAINT `ReturnMsgMessageFK` FOREIGN KEY (`messageId`) REFERENCES `callgraph.message` (`messageId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classdiagram.class`
--
ALTER TABLE `classdiagram.class`
  ADD CONSTRAINT `ClassPackageFK` FOREIGN KEY (`packageId`) REFERENCES `classdiagram.package` (`packageId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classdiagram.inheritance`
--
ALTER TABLE `classdiagram.inheritance`
  ADD CONSTRAINT `InheritanceClassFK` FOREIGN KEY (`superClassId`) REFERENCES `classdiagram.class` (`classId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classdiagram.method`
--
ALTER TABLE `classdiagram.method`
  ADD CONSTRAINT `MethodClassFK` FOREIGN KEY (`classId`) REFERENCES `classdiagram.class` (`classId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classdiagram.package`
--
ALTER TABLE `classdiagram.package`
  ADD CONSTRAINT `PackageDiagramFK` FOREIGN KEY (`diagramId`) REFERENCES `classdiagram.diagram` (`diagramId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classdiagram.param`
--
ALTER TABLE `classdiagram.param`
  ADD CONSTRAINT `ParamMethodFK` FOREIGN KEY (`methodId`) REFERENCES `classdiagram.method` (`methodId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
