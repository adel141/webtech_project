-- ================================================================
-- Job Portal Database Schema
-- ================================================================

CREATE DATABASE IF NOT EXISTS job_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE job_portal;

-- ----------------------------------------------------------------
-- 1. users — All platform users
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('seeker','employer','recruiter','admin') NOT NULL,
    profile_pic VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 2. seeker_profiles — Extended profile for job seekers
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS seeker_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    headline VARCHAR(200) DEFAULT NULL,
    summary TEXT DEFAULT NULL,
    skills TEXT DEFAULT NULL,
    years_experience INT DEFAULT 0,
    education_level VARCHAR(50) DEFAULT NULL,
    current_salary DECIMAL(12,2) DEFAULT NULL,
    expected_salary DECIMAL(12,2) DEFAULT NULL,
    preferred_location VARCHAR(100) DEFAULT NULL,
    resume_path VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 3. employer_profiles — Extended company profile
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS employer_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    company_name VARCHAR(200) DEFAULT NULL,
    industry VARCHAR(100) DEFAULT NULL,
    company_size VARCHAR(50) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    logo_path VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 4. recruiter_profiles — Extended recruiter/agency profile
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS recruiter_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    agency_name VARCHAR(200) DEFAULT NULL,
    specialization VARCHAR(200) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 5. recruiter_clients — Recruiter-to-employer relationships
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS recruiter_clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    employer_id INT DEFAULT NULL,
    company_name_override VARCHAR(200) DEFAULT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 6. categories — Job category classifications
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 7. jobs — All job postings
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    recruiter_id INT DEFAULT NULL,
    category_id INT DEFAULT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT DEFAULT NULL,
    benefits TEXT DEFAULT NULL,
    salary_min DECIMAL(12,2) DEFAULT NULL,
    salary_max DECIMAL(12,2) DEFAULT NULL,
    location VARCHAR(150) DEFAULT NULL,
    job_type ENUM('full-time','part-time','remote','contract') DEFAULT 'full-time',
    experience_level ENUM('entry','mid','senior') DEFAULT 'entry',
    deadline DATE DEFAULT NULL,
    status ENUM('active','closed','draft') DEFAULT 'draft',
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 8. applications — Job applications
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    seeker_id INT NOT NULL,
    recruiter_id INT DEFAULT NULL,
    cover_letter TEXT DEFAULT NULL,
    resume_path VARCHAR(255) DEFAULT NULL,
    status ENUM('submitted','reviewed','shortlisted','interview','rejected','withdrawn') DEFAULT 'submitted',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_application (job_id, seeker_id)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 9. saved_jobs — Job seeker bookmarks
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS saved_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_save (user_id, job_id)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 10. job_alerts — Saved search preferences
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS job_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seeker_id INT NOT NULL,
    keyword VARCHAR(200) DEFAULT NULL,
    category_id INT DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    job_type ENUM('full-time','part-time','remote','contract') DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seeker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 11. recruiter_outreach — Recruiter-to-seeker messages
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS recruiter_outreach (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    seeker_id INT NOT NULL,
    job_id INT DEFAULT NULL,
    message TEXT NOT NULL,
    status ENUM('sent','read','responded') DEFAULT 'sent',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 12. messages — In-platform messaging
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    application_id INT DEFAULT NULL,
    body TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 13. complaints — Disputes submitted to admin
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submitter_id INT NOT NULL,
    subject_id INT DEFAULT NULL,
    description TEXT NOT NULL,
    status ENUM('open','resolved') DEFAULT 'open',
    admin_note TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submitter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 14. announcements — Admin platform announcements
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- 15. settings — Platform-wide configuration
-- ----------------------------------------------------------------
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- Default settings
-- ----------------------------------------------------------------
INSERT INTO settings (setting_key, setting_value) VALUES
('max_jobs_per_employer', '50'),
('max_applications_per_seeker', '100'),
('resume_visibility_default', 'visible');

-- ----------------------------------------------------------------
-- Default admin account (password: admin123)
-- ----------------------------------------------------------------
INSERT INTO users (name, email, password_hash, role, is_active, is_verified) VALUES
('Platform Admin', 'admin@jobportal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 1);

-- ----------------------------------------------------------------
-- Default categories
-- ----------------------------------------------------------------
INSERT INTO categories (name, description) VALUES
('Technology', 'Software, IT, and tech-related positions'),
('Finance', 'Banking, accounting, and financial services'),
('Healthcare', 'Medical, pharmaceutical, and health services'),
('Marketing', 'Digital marketing, advertising, and PR'),
('Education', 'Teaching, training, and academic positions'),
('Engineering', 'Civil, mechanical, and electrical engineering'),
('Sales', 'Sales, business development, and retail'),
('Design', 'Graphic design, UX/UI, and creative roles'),
('Human Resources', 'HR, recruitment, and people operations'),
('Operations', 'Logistics, supply chain, and operations management');
