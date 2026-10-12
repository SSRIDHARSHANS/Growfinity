-- GrowFinity database
-- Import this file in phpMyAdmin (Import tab) or run: mysql -u root < database/growfinity.sql

CREATE DATABASE IF NOT EXISTS growfinity CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE growfinity;

DROP TABLE IF EXISTS progress;
DROP TABLE IF EXISTS roadmap_steps;
DROP TABLE IF EXISTS domains;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(80)  NOT NULL,
  email         VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE domains (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(80)  NOT NULL,
  summary     VARCHAR(255) NOT NULL,
  sort_order  INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE roadmap_steps (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  domain_id   INT UNSIGNED NOT NULL,
  step_order  INT NOT NULL,
  title       VARCHAR(120) NOT NULL,
  level       ENUM('Beginner','Intermediate','Advanced') NOT NULL DEFAULT 'Beginner',
  description TEXT NOT NULL,
  video_id    VARCHAR(20) NOT NULL DEFAULT '',   -- YouTube video ID (the part after v=). Empty = "video coming soon"
  FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE,
  UNIQUE KEY uq_domain_order (domain_id, step_order)
) ENGINE=InnoDB;

CREATE TABLE progress (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id      INT UNSIGNED NOT NULL,
  step_id      INT UNSIGNED NOT NULL,
  completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (step_id) REFERENCES roadmap_steps(id) ON DELETE CASCADE,
  UNIQUE KEY uq_user_step (user_id, step_id)
) ENGINE=InnoDB;

-- ---------- Domains ----------
INSERT INTO domains (id, name, summary, sort_order) VALUES
(1, 'Embedded Systems',   'Program microcontrollers and build devices that sense and act.', 1),
(2, 'VLSI Design',        'Design digital chips with Verilog, from logic gates to FPGA.', 2),
(3, 'Internet of Things', 'Connect sensors to the cloud and build live dashboards.', 3),
(4, 'Web Development',    'Build and publish websites with a front end and a database.', 4),
(5, 'AI & Machine Learning', 'Train models with Python and run them on real data.', 5),
(6, 'Cyber Security',     'Learn how systems get attacked and how to defend them.', 6);

-- ---------- Roadmap steps ----------
INSERT INTO roadmap_steps (domain_id, step_order, title, level, description) VALUES
(1,1,'C programming basics','Beginner','Variables, loops, functions, pointers and arrays. Embedded code is mostly C, so this is your foundation.'),
(1,2,'Microcontroller architecture','Beginner','CPU, memory, registers, clocks and power. Understand what runs your code on an Arduino or STM32.'),
(1,3,'GPIO, timers and interrupts','Intermediate','Read buttons, drive LEDs and motors, and respond to events without wasting CPU time.'),
(1,4,'UART, SPI and I2C','Intermediate','The three communication protocols you will meet in almost every sensor and display datasheet.'),
(1,5,'RTOS with FreeRTOS','Advanced','Tasks, queues and semaphores. Run several jobs on one microcontroller reliably.'),
(1,6,'Capstone: build a device','Advanced','Combine sensors, a display and a protocol into one working project you can show in interviews.'),

(2,1,'Digital logic fundamentals','Beginner','Boolean algebra, gates, multiplexers, flip-flops and counters.'),
(2,2,'Verilog HDL basics','Beginner','Modules, wires, registers and always blocks. Describe hardware in code.'),
(2,3,'Finite state machines and RTL','Intermediate','Design controllers with Moore and Mealy machines and write clean RTL.'),
(2,4,'Testbenches and simulation','Intermediate','Verify your design with testbenches and read waveforms.'),
(2,5,'FPGA implementation','Advanced','Synthesize, place and route a design and run it on an FPGA board.'),
(2,6,'CMOS and physical design flow','Advanced','Transistor-level view, timing, and the steps from RTL to layout.'),

(3,1,'Sensors and actuators','Beginner','Temperature, motion, gas and light sensors, plus relays and motors.'),
(3,2,'ESP32 programming','Beginner','Set up the toolchain and read sensors with an ESP32.'),
(3,3,'Wi-Fi and Bluetooth Low Energy','Intermediate','Connect devices to a network and exchange data with a phone.'),
(3,4,'MQTT and HTTP APIs','Intermediate','Send data with publish and subscribe messages or REST calls.'),
(3,5,'Cloud dashboards','Intermediate','Store readings in a cloud service and show them as live charts.'),
(3,6,'Secure IoT project','Advanced','Add authentication and encrypted transport, then finish a complete project.'),

(4,1,'HTML and CSS','Beginner','Structure pages and style them. Learn flexbox and grid.'),
(4,2,'JavaScript fundamentals','Beginner','Variables, functions, the DOM and events.'),
(4,3,'PHP and MySQL','Intermediate','Forms, sessions, login systems and database queries.'),
(4,4,'Git and GitHub','Intermediate','Commit, branch and publish your code.'),
(4,5,'Responsive design and APIs','Intermediate','Make layouts work on phones and fetch data from APIs.'),
(4,6,'Deploy your website','Advanced','Put your project online with hosting and a domain.'),

(5,1,'Python for data','Beginner','Core Python, files and virtual environments.'),
(5,2,'NumPy and Pandas','Beginner','Clean, reshape and explore tabular data.'),
(5,3,'Machine learning fundamentals','Intermediate','Regression, classification, overfitting and evaluation metrics.'),
(5,4,'Build models with scikit-learn','Intermediate','Train, tune and compare models on a real dataset.'),
(5,5,'Deep learning basics','Advanced','Neural networks, CNNs and training loops with TensorFlow or PyTorch.'),
(5,6,'Deploy models to the edge','Advanced','Shrink a model and run it on a Raspberry Pi or ESP32-class device.'),

(6,1,'Networking basics','Beginner','IP, TCP, DNS, HTTP and how data moves across a network.'),
(6,2,'Linux and the command line','Beginner','Files, permissions, processes and shell scripting.'),
(6,3,'Security principles','Intermediate','Confidentiality, integrity, availability, encryption and hashing.'),
(6,4,'Web application vulnerabilities','Intermediate','SQL injection, XSS, CSRF and how to prevent them.'),
(6,5,'Security tools','Advanced','Packet capture, vulnerability scanning and log analysis.'),
(6,6,'Practice labs','Advanced','Solve beginner capture-the-flag challenges to apply everything.');
