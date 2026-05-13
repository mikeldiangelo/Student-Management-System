<?php
//CREATING A CLASS OF STUDENT AND ADD PROPERTIES
class Student {
    private $name;
    private $email;
    private $course;

    //CREATE A METHOD TO CALL THE CLASS
    public function register_Student($name, $email, $course) {
        $this->name = $name;
        $this->email = $email;
        $this->course = $course;

        // For now, let's just return a message
        return "This Student {$this->name} has been registered for the course: {$this->course}.";
    }

    //CREATING A CONSTRUCTOR AND ASSIGN VALUES TO THEM
    function __construct($name, $email, $course) {
        $this->name = $name;
        $this->email = $email;
        $this->course = $course;
    }

    //CREATING A GET NAME METHOD THAT DISPLAYS STUDENT NAME
    public function get_name() {
        return $this->name;
    }

    //CREATING A STATIC METHOD THAT TAKES A FUNCTION AND CALLS THE SCHOOL NAME
    public static function schoolname() {
        return "Global Tech";
    }
}

//RUNNING A TEST ON THE CLASS PROPERTY TO DISPLAY THEM ON THE PAGE
// CREATE A STUDENT OBJECT
$student = new Student("Mikel Di Angelo", "mikel@example.com", "PHP");

// TRY TO ACCESS PRIVATE PROPERTY DIRECTLY
echo $student->name; // This will cause an error
?>