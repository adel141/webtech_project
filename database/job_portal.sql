CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS job_alerts;
DROP TABLE IF EXISTS saved_jobs;
DROP TABLE IF EXISTS recruiter_outreach;
DROP TABLE IF EXISTS recruiter_clients;
DROP TABLE IF EXISTS complaints;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS recruiter_profiles;
DROP TABLE IF EXISTS employer_profiles;
DROP TABLE IF EXISTS seeker_profiles;
DROP TABLE IF EXISTS users;

CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('seeker','employer','recruiter','admin') NOT NULL,
    profile_pic VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    is_verified TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE seeker_profiles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    headline VARCHAR(200),
    summary TEXT,
    skills TEXT,
    years_experience INT DEFAULT 0,
    education_level VARCHAR(100),
    current_salary DECIMAL(12,2),
    expected_salary DECIMAL(12,2) DEFAULT 0,
    preferred_location VARCHAR(150),
    resume_path VARCHAR(255)
);

CREATE TABLE employer_profiles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(150),
    industry VARCHAR(100),
    company_size VARCHAR(50),
    description TEXT,
    website VARCHAR(255),
    address TEXT,
    logo_path VARCHAR(255)
);

CREATE TABLE recruiter_profiles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    agency_name VARCHAR(150),
    specialization VARCHAR(150),
    description TEXT,
    website VARCHAR(255)
);

CREATE TABLE categories(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE jobs(
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NULL,
    recruiter_id INT NULL,
    category_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    requirements TEXT,
    benefits TEXT,
    salary_min DECIMAL(12,2) DEFAULT 0,
    salary_max DECIMAL(12,2) DEFAULT 0,
    location VARCHAR(150),
    job_type ENUM('full-time','part-time','remote','contract'),
    experience_level ENUM('entry','mid','senior'),
    deadline DATE,
    status ENUM('active','closed','draft') DEFAULT 'draft',
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE applications(
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    seeker_id INT NOT NULL,
    recruiter_id INT NULL,
    cover_letter TEXT,
    resume_path VARCHAR(255),
    status ENUM('submitted','reviewed','shortlisted','interview','rejected','withdrawn') DEFAULT 'submitted',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE saved_jobs(
    id INT AUTO_INCREMENT PRIMARY KEY,
    seeker_id INT NOT NULL,
    job_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE job_alerts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    seeker_id INT NOT NULL,
    keyword VARCHAR(120),
    location VARCHAR(120),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recruiter_clients(
    id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    employer_id INT NULL,
    company_name_override VARCHAR(150),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recruiter_outreach(
    id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    seeker_id INT NOT NULL,
    job_id INT NULL,
    message TEXT,
    status ENUM('sent','read','responded') DEFAULT 'sent',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE messages(
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE complaints(
    id INT AUTO_INCREMENT PRIMARY KEY,
    submitter_id INT NOT NULL,
    subject_id INT NOT NULL,
    description TEXT,
    status ENUM('open','resolved') DEFAULT 'open',
    admin_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users(name, email, password_hash, phone, role, is_active, is_verified) VALUES
('Admin User', 'admin@jobportal.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'admin', 1, 1),
('Nadia Recruiter', 'nadia@talenthub.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'recruiter', 1, 1),
('Rafi Recruiter', 'rafi@careerlink.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'recruiter', 0, 0),
('BrightSoft HR', 'hr@brightsoft.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'employer', 1, 1),
('Metro Finance', 'jobs@metrofinance.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'employer', 1, 1),
('Green Retail', 'careers@greenretail.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'employer', 0, 0),
('Ayesha Karim', 'ayesha@example.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'seeker', 1, 1),
('Hasan Mahmud', 'hasan@example.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'seeker', 1, 1),
('Mira Akter', 'mira@example.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'seeker', 1, 1),
('Tanvir Rahman', 'tanvir@example.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'seeker', 1, 1),
('Sadia Islam', 'sadia@example.com', '$2y$10$jalSVXi92jBqt/PmFyeEQ.vS2rMRqGSag5a5zwEBKmbaYSyw8iXE6', '', 'seeker', 1, 1);

INSERT INTO recruiter_profiles(user_id, agency_name, specialization, description, website) VALUES
(2, 'Talent Hub BD', 'Technology hiring', 'Recruiting engineers, designers and product people.', 'https://talenthub.example.com'),
(3, 'Career Link', 'Finance and operations', 'Recruiting mid level business roles.', 'https://careerlink.example.com');

INSERT INTO employer_profiles(user_id, company_name, industry, website, description) VALUES
(4, 'BrightSoft Ltd', 'Software', 'https://brightsoft.example.com', 'Software products and consulting.'),
(5, 'Metro Finance', 'Finance', 'https://metrofinance.example.com', 'Digital finance and accounting services.'),
(6, 'Green Retail', 'Retail', 'https://greenretail.example.com', 'Retail chain with store and warehouse teams.');

INSERT INTO seeker_profiles(user_id, headline, skills, years_experience, expected_salary, preferred_location) VALUES
(7, 'Frontend Developer', 'HTML, CSS, JavaScript, React', 2, 45000, 'Dhaka'),
(8, 'Backend PHP Developer', 'PHP, MySQL, Laravel basics', 3, 55000, 'Dhaka'),
(9, 'UI Designer', 'Figma, UX Research, Design Systems', 2, 50000, 'Remote'),
(10, 'Account Executive', 'Accounts, Excel, Reporting', 4, 48000, 'Chattogram'),
(11, 'Customer Support Specialist', 'Support, CRM, Communication', 1, 30000, 'Dhaka');

INSERT INTO categories(name, description) VALUES
('Software Development', 'Programming and engineering roles'),
('Design', 'UI, UX and creative roles'),
('Finance', 'Accounting and finance roles'),
('Marketing', 'Marketing and brand roles'),
('Customer Support', 'Customer care and support roles');

INSERT INTO recruiter_clients(recruiter_id, employer_id, company_name_override) VALUES
(2, 4, ''),
(2, 5, ''),
(2, NULL, 'North Star Labs');

INSERT INTO jobs(recruiter_id, employer_id, category_id, title, description, requirements, benefits, salary_min, salary_max, location, job_type, experience_level, deadline, status, is_featured) VALUES
(2, 4, 1, 'Junior PHP Developer', 'Build and maintain PHP applications.', 'PHP, MySQL, HTML, CSS', 'Lunch, yearly bonus, training', 35000, 55000, 'Dhaka', 'full-time', 'entry', '2026-07-15', 'active', 1),
(2, 4, 1, 'Frontend Developer', 'Create responsive web interfaces.', 'JavaScript, CSS, responsive design', 'Flexible hours and medical support', 40000, 70000, 'Dhaka', 'full-time', 'mid', '2026-07-20', 'active', 0),
(2, 5, 3, 'Accounts Officer', 'Manage daily accounting reports.', 'Excel, accounting basics', 'Festival bonus and provident fund', 30000, 50000, 'Chattogram', 'full-time', 'mid', '2026-08-01', 'active', 0),
(2, NULL, 2, 'UI Designer', 'Design clean product screens.', 'Figma, wireframes, prototyping', 'Remote friendly work', 45000, 80000, 'Remote', 'remote', 'mid', '2026-08-10', 'draft', 0),
(2, NULL, 5, 'Customer Support Executive', 'Support customers over chat and email.', 'Communication and CRM tools', 'Shift allowance and training', 25000, 38000, 'Dhaka', 'full-time', 'entry', '2026-07-30', 'active', 1);

INSERT INTO applications(job_id, seeker_id, cover_letter, status) VALUES
(1, 8, 'I have worked with PHP and MySQL projects.', 'shortlisted'),
(2, 7, 'I enjoy building clean frontend interfaces.', 'reviewed'),
(3, 10, 'I have four years of accounting experience.', 'submitted'),
(4, 9, 'I can help create a clean design system.', 'interview'),
(5, 11, 'I have support and communication experience.', 'submitted');

INSERT INTO saved_jobs(seeker_id, job_id) VALUES
(7, 1),
(8, 2),
(9, 4);

INSERT INTO job_alerts(seeker_id, keyword, location) VALUES
(7, 'frontend', 'Dhaka'),
(8, 'php', 'Dhaka'),
(9, 'design', 'Remote');

INSERT INTO recruiter_outreach(recruiter_id, seeker_id, job_id, message) VALUES
(2, 8, 1, 'Your PHP experience looks like a strong match for this role.'),
(2, 7, 2, 'We have a frontend role that may fit your profile.'),
(2, 9, 4, 'Please review this UI Designer opening.');

INSERT INTO messages(sender_id, receiver_id, message) VALUES
(2, 8, 'Thanks for applying. We will review your profile.'),
(8, 2, 'Thank you. I am available for an interview.'),
(2, 7, 'Can you share your latest portfolio?');

INSERT INTO complaints(submitter_id, subject_id, description, status, admin_note) VALUES
(7, 1, 'One job has missing salary details.', 'open', ''),
(8, 2, 'My application status has not changed.', 'open', ''),
(5, 2, 'We need faster recruiter feedback.', 'resolved', 'Asked recruiter to follow up.');
