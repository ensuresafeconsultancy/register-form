<?php
$servername = "localhost"; // Change this to your database server if different
$username = "ensuresafe"; // Change this to your database username
$password = "ensuresafe123"; // Change this to your database password
$dbname = "ensuresafe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file uploads
$certificatePath = "";
$photoPath = "";
$uploadDir = "uploads/";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES["certificate"]) && $_FILES["certificate"]["error"] == 0) {
    $certificatePath = $uploadDir . basename($_FILES["certificate"]["name"]);
    move_uploaded_file($_FILES["certificate"]["tmp_name"], $certificatePath);
}

if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
    $photoPath = $uploadDir . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO registrations (
    class_type, schedule, type_of_registration, company_name, Company_uen_reg_no,
    contact_person_name, contact_person_email, contact_person_phone, participant_name,
    nric_no, dob, gender, nationality, race, contact, email, experience,
    salary_range, qualification, certificate_path, photo_path, signature
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssssssssssssssssss", 
    $class_type, $schedule, $type_of_registration, $company_name, $Company_uen_reg_no,
    $contact_person_name, $contact_person_email, $contact_person_phone, $participant_name,
    $nric_no, $dob, $gender, $nationality, $race, $contact, $email, $experience,
    $salary_range, $qualification, $certificatePath, $photoPath, $signature
);

// Set parameters and execute
$class_type = $_POST["Class_type"];
$schedule = isset($_POST["Sunday_Class"]) ? $_POST["Sunday_Class"] : 
            (isset($_POST["Day_Class"]) ? $_POST["Day_Class"] : 
            (isset($_POST["Sat&Sun_Class"]) ? $_POST["Sat&Sun_Class"] : ""));
$type_of_registration = $_POST["Type_of_Registration"];
$company_name = isset($_POST["company_name"]) ? $_POST["company_name"] : "";
$Company_uen_reg_no = isset($_POST["Company_uen_reg_no"]) ? $_POST["Company_uen_reg_no"] : "";
$contact_person_name = isset($_POST["Contact_Person_Name"]) ? $_POST["Contact_Person_Name"] : "";
$contact_person_email = isset($_POST["Contact_Person_Email"]) ? $_POST["Contact_Person_Email"] : "";
$contact_person_phone = isset($_POST["Contact_Person_Contact_No"]) ? $_POST["Contact_Person_Contact_No"] : "";
$participant_name = $_POST["single_Participant_name"];
$nric_no = $_POST["nric_no"];
$dob = $_POST["Date_Of_Brith"];
$gender = $_POST["Gender"];
$nationality = $_POST["nationality"];
$race = $_POST["race"];
$contact = $_POST["contact"];
$email = $_POST["email"];
$experience = $_POST["experience"];
$salary_range = $_POST["salary"];
$qualification = $_POST["Qualification"];
$signature = $_POST["signature"];

$stmt->execute();

echo "Register successfully";

$stmt->close();
$conn->close();
?>
