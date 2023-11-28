-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2023 at 01:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omise`
--

-- --------------------------------------------------------

--
-- Table structure for table `charge`
--

CREATE TABLE `charge` (
  `id` int(5) NOT NULL,
  `charge_id` varchar(30) NOT NULL,
  `webhook_status` tinyint(3) NOT NULL DEFAULT 0 COMMENT '0=pending,\r\n1=completed,\r\n2=canceled',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `charge`
--

INSERT INTO `charge` (`id`, `charge_id`, `webhook_status`, `created_at`, `updated_at`) VALUES
(22, 'chrg_test_5xvo8i2e5ufrz4pltuv', 1, '2023-11-24 14:33:32', '2023-11-24 14:33:46'),
(23, 'chrg_test_5xvo9m3gam2m85oh016', 0, '2023-11-24 14:36:41', '2023-11-24 14:36:41'),
(24, 'chrg_test_5xvoak4ujthqxmvx71s', 1, '2023-11-24 14:39:22', '2023-11-24 14:39:44'),
(25, 'chrg_test_5xvostsksoit1cr7qyc', 1, '2023-11-24 15:31:16', '2023-11-24 15:31:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `charge`
--
ALTER TABLE `charge`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `charge`
--
ALTER TABLE `charge`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
