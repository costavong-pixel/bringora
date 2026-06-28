CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  plan VARCHAR(50) NOT NULL DEFAULT 'beta',
  monthly_limit INT NOT NULL DEFAULT 500,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS saved_outputs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  access_key VARCHAR(190) NULL,
  category VARCHAR(80) NOT NULL,
  title VARCHAR(190) NULL,
  output_text MEDIUMTEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_saved_outputs_access_key (access_key),
  INDEX idx_saved_outputs_created_at (created_at)
);

CREATE TABLE IF NOT EXISTS usage_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  access_key VARCHAR(190) NOT NULL,
  category VARCHAR(80) NOT NULL,
  input_chars INT NOT NULL DEFAULT 0,
  output_chars INT NOT NULL DEFAULT 0,
  status VARCHAR(40) NOT NULL DEFAULT 'success',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_usage_logs_access_created (access_key, created_at),
  INDEX idx_usage_logs_status (status)
);

CREATE TABLE IF NOT EXISTS redemption_codes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code_value VARCHAR(190) NOT NULL UNIQUE,
  tier VARCHAR(50) NOT NULL DEFAULT 'tier1',
  daily_limit INT NOT NULL DEFAULT 50,
  monthly_limit INT NOT NULL DEFAULT 500,
  redeemed_by_user_id INT NULL,
  redeemed_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
