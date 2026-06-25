<?php
require_once 'config.php';

// First, delete existing fake jobs and companies
$pdo->exec('DELETE FROM jobs');
$pdo->exec('DELETE FROM companies');

// Ensure we have an employer
$stmt = $pdo->query("SELECT id FROM users WHERE role = 'employer' LIMIT 1");
$employer = $stmt->fetch();
if (!$employer) {
    die("No employer found. Please register an employer first.");
}
$emp_id = $employer['id'];

// Create 5 companies
$companies = ['TechNova', 'Quantum Solutions', 'Apex Interactive', 'BlueSky Data', 'Zenith Systems'];
$company_ids = [];
foreach ($companies as $cName) {
    $stmt = $pdo->prepare('INSERT INTO companies (employer_id, name, details) VALUES (?, ?, ?)');
    $stmt->execute([$emp_id, $cName, 'A leading company in the tech sector.']);
    $company_ids[] = $pdo->lastInsertId();
}

// Generate 50 jobs
$titles = ['Frontend Developer', 'Backend Engineer', 'Full Stack Developer', 'Data Analyst', 'UX/UI Designer', 'DevOps Engineer', 'Product Manager', 'QA Specialist', 'Security Architect', 'Cloud Consultant'];
$locations = ['San Francisco, CA', 'Remote', 'New York, NY', 'Austin, TX', 'London, UK', 'Berlin, Germany', 'Toronto, ON', 'Singapore', 'Sydney, AUS', 'Seattle, WA'];
$salaries = ['$80k - $100k', '$100k - $130k', '$130k - $160k', '$150k - $190k', '$90k - $120k'];
$types = ['full-time', 'contract', 'remote'];
$experiences = ['entry', 'mid', 'exec'];

for ($i = 1; $i <= 50; $i++) {
    $comp_id = $company_ids[array_rand($company_ids)];
    $title = $titles[array_rand($titles)];
    $loc = $locations[array_rand($locations)];
    $sal = $salaries[array_rand($salaries)];
    $type = $types[array_rand($types)];
    $exp = $experiences[array_rand($experiences)];
    $desc = "We are seeking a talented $title to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.";
    
    $stmt = $pdo->prepare('INSERT INTO jobs (employer_id, company_id, title, description, location, salary, type, experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$emp_id, $comp_id, $title, $desc, $loc, $sal, $type, $exp]);
}

echo "Database seeded with 50 jobs successfully.";
?>
