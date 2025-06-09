-- Insert default admin user
-- Password: admin123 (hashed with bcrypt)
INSERT INTO users (username, password_hash, role, agent_code) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', 'AGENT001')
ON DUPLICATE KEY UPDATE username = username;