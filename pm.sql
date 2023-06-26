-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2023 at 09:37 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pm`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `Id` int NOT NULL,
  `Name` longtext NOT NULL,
  `Description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `UserId` int NOT NULL,
  `ProjectId` int NOT NULL,
  `StatusId` int NOT NULL,
  `DueDate` datetime(6) NOT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`Id`, `Name`, `Description`, `UserId`, `ProjectId`, `StatusId`, `DueDate`, `isDeleted`) VALUES
(1, 'add Assignment to db', '', 20, 1, 4, '2023-05-20 00:00:00.000000', 0),
(2, 'add Active Assignments', '', 20, 1, 2, '2023-05-26 00:00:00.000000', 0),
(3, 'edit project', '', 20, 1, 1, '2023-05-22 00:00:00.000000', 0),
(4, 'revise mesurments', '', 20, 2, 1, '2023-05-20 00:00:00.000000', 0),
(5, 'demo', '', 7, 6, 1, '2023-06-04 00:00:00.000000', 0),
(6, 'authenticate user and generate tokens', '', 20, 3, 1, '2023-05-20 00:00:00.000000', 0),
(7, 'delete me too', '', 13, 4, 1, '2023-05-27 00:00:00.000000', 1),
(8, 'finished task', 'finished task details ....', 13, 10, 4, '2024-03-04 00:00:00.000000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `Id` int NOT NULL,
  `ProjectId` int NOT NULL,
  `Logs` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `Id` int NOT NULL,
  `Name` longtext NOT NULL,
  `UserId` int NOT NULL,
  `DueDate` datetime(6) NOT NULL,
  `IsArchived` tinyint(1) NOT NULL DEFAULT '0',
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`Id`, `Name`, `UserId`, `DueDate`, `IsArchived`, `CreatedAt`, `UpdatedAt`, `isDeleted`) VALUES
(1, 'make PEF project', 20, '2023-05-31 00:00:00.000000', 0, '2023-05-19 07:28:44', '2023-05-19 07:28:44', 0),
(2, 'vr visual therapy', 20, '2023-05-26 00:00:00.000000', 0, '2023-05-19 07:41:14', '2023-05-19 07:41:14', 0),
(3, 'make web App ASP back end Angular Front', 20, '2023-05-27 00:00:00.000000', 0, '2023-05-19 07:56:17', '2023-05-19 07:56:17', 0),
(4, 'overdue', 20, '2023-05-18 00:00:00.000000', 0, '2023-05-19 09:11:07', '2023-05-19 09:11:07', 0),
(5, 'Today', 20, '2023-05-19 00:00:00.000000', 0, '2023-05-19 09:12:33', '2023-05-19 09:12:33', 1),
(6, 'this week', 20, '2023-05-20 00:00:00.000000', 0, '2023-05-19 09:13:17', '2023-05-19 09:13:17', 1),
(7, 'project Q', 2, '2023-05-19 00:00:00.000000', 0, '2023-05-19 09:34:29', '2023-05-19 09:34:29', 0),
(8, 'goal keeper', 22, '2023-05-20 00:00:00.000000', 0, '2023-05-19 09:54:03', '2023-05-19 09:54:03', 0),
(9, 'delete me', 20, '2023-05-27 00:00:00.000000', 0, '2023-05-20 13:56:41', '2023-05-20 13:56:41', 1),
(10, 'finished project', 20, '2023-06-03 00:00:00.000000', 1, '2023-05-20 15:00:23', '2023-05-20 15:00:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `Id` int NOT NULL,
  `Label` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`Id`, `Label`) VALUES
(1, 'manager'),
(2, 'assignee');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `Id` int NOT NULL,
  `Label` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`Id`, `Label`) VALUES
(1, 'TODO'),
(2, 'IN PROGRESS'),
(3, 'ON HOLD'),
(4, 'FINISHED');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int NOT NULL,
  `Username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `Password` longtext NOT NULL,
  `RoleId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Username`, `Email`, `Password`, `RoleId`) VALUES
(2, 'qorok', 'qorok@mailinator.com', '123', 1),
(3, 'bimixepyw', 'bimixepyw@mailinator.com', 'Pa$$w0rd!', 1),
(4, 'tusiciri', 'tusiciri@mailinator.com', 'Pa$$w0rd!', 1),
(5, 'hivoh', 'hivoh@mailinator.com', 'Pa$$w0rd!', 1),
(6, 'fyjygyh', 'fyjygyh@mailinator.com', 'Pa$$w0rd!', 1),
(7, 'butixyxo', 'butixyxo@mailinator.com', 'Pa$$w0rd!', 1),
(13, 'myra', 'myra@mailinator.com', 'Pa$$w0rd!', 1),
(17, 'hynuqa', 'hynuqa@mailinator.com', 'Pa$$w0rd!', 1),
(20, 'kulatos', 'kulatos@mailinator.com', 'Pa$$w0rd!', 1),
(21, 'dozigod', 'dozigod@mailinator.com', 'Pa$$w0rd!', 1),
(22, 'bono', 'bono@mailinator.com', 'Pa$$w0rd!', 1),
(23, 'pigudu', 'pigudu@mailinator.com', 'Pa$$w0rd!', 1);

-- --------------------------------------------------------

--
-- Table structure for table `__efmigrationshistory`
--

CREATE TABLE `__efmigrationshistory` (
  `MigrationId` varchar(150) NOT NULL,
  `ProductVersion` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `__efmigrationshistory`
--

INSERT INTO `__efmigrationshistory` (`MigrationId`, `ProductVersion`) VALUES
('20230518094540_initial migration', '7.0.5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IX_Assignments_ProjectId` (`ProjectId`),
  ADD KEY `IX_Assignments_StatusId` (`StatusId`),
  ADD KEY `IX_Assignments_UserId` (`UserId`);

--
-- Indexes for table `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IX_Journals_ProjectId` (`ProjectId`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IX_Projects_UserId` (`UserId`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `IX_Users_RoleId` (`RoleId`);

--
-- Indexes for table `__efmigrationshistory`
--
ALTER TABLE `__efmigrationshistory`
  ADD PRIMARY KEY (`MigrationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `FK_Assignments_Projects_ProjectId` FOREIGN KEY (`ProjectId`) REFERENCES `projects` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Assignments_Statuses_StatusId` FOREIGN KEY (`StatusId`) REFERENCES `statuses` (`Id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `FK_Assignments_Users_UserId` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`) ON DELETE RESTRICT;

--
-- Constraints for table `journals`
--
ALTER TABLE `journals`
  ADD CONSTRAINT `FK_Journals_Projects_ProjectId` FOREIGN KEY (`ProjectId`) REFERENCES `projects` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `FK_Projects_Users_UserId` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_Users_Roles_RoleId` FOREIGN KEY (`RoleId`) REFERENCES `roles` (`Id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
