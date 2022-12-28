CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jagel_user_id VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    phone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    status_partner VARCHAR(100) NOT NULL,
    status_driver VARCHAR(100) NOT NULL,
    photo VARCHAR(100) NOT NULL,
    verified_email VARCHAR(100) DEFAULT NULL,
    verified_phone VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address VARCHAR(100) NOT NULL,
    city_name VARCHAR(100) NOT NULL,
    province_name VARCHAR(100) NOT NULL,
    district_name VARCHAR(100) NOT NULL
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE role_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    route_path VARCHAR(100) NOT NULL,
    CONSTRAINT fk_role_routes_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE user_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    CONSTRAINT fk_user_roles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE application (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    jagel_api_key VARCHAR(100) NOT NULL,
    bank_btn_api_key VARCHAR(100) NOT NULL
);

CREATE TABLE migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(100) NOT NULL,
    execute_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE balance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `topup` (
  `id` int(11) NOT NULL,
  `userid` VARCHAR(100) NOT NULL,
  `pesanan_nomor` varchar(45) NOT NULL,
  `pesanan_total` int(11) NOT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `payment_type` varchar(45) NOT NULL,
  `payment_ref` varchar(45) NOT NULL,
  `status` varchar(20) DEFAULT "MENUNGGU",
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_at` datetime DEFAULT NULL
);