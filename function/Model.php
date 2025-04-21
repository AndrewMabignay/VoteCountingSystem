<?php 

require '../config/config.php';

class Model {
    private $databaseTable = '';
    private $query = '';

    public function __construct()
    {
        // echo 'Hello Constructor';
    }

    public function setDatabaseTable($databaseTable) {
        $this->databaseTable = $databaseTable;
    }

    public function index() {
        global $conn;

        $this->query = "SELECT * FROM {$this->databaseTable}"; 
        $retrieve = \mysqli_query($conn, $this->query);

        $rows = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $rows[] = $row;
            }
        } 

        return $rows;
    }

    // LOGIN AUTHENTICATION 
    public function authentication($username, $password) {
        global $conn;

        if (empty($username) || empty($password)) {
            return 'Please enter both username and password.';
        }

        $this->query = "SELECT * FROM {$this->databaseTable} WHERE Name = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            $users = $result->fetch_assoc();

            if ($password === $users['Password']) {
                session_start();
                $_SESSION['id'] = $users['ID'];
                $_SESSION['name'] = $users['Name'];
                $_SESSION['age'] = $users['Age'];
                $_SESSION['address'] = $users['Address'];
                $_SESSION['password'] = $users['Password'];
                $_SESSION['role'] = $users['Role'];

                switch ($_SESSION['role']) {
                    case 'Admin':
                        header('Location: ../admin/adminPanel.php');
                        exit;
                    case 'User':
                        header('Location: ../client/standard.php');
                        exit;
                }
            }
        }

        // echo 'Testing pa';
    }    

    public function logout() {
        session_start();
        session_unset();

        header('Location: ../auth/login.php');
        exit;
    }

    // ADMIN | ADMIN PANEL 
    public function insert($name, $position, $partylist, $voteCount) {
        global $conn;

        if (empty($name) || empty($position) || empty($partylist) || empty($votecount)) {
            return 'Fill up all fields!';
        }

        $this->query = "INSERT INTO {$this->databaseTable} (Name, Position, PartyList, VoteCount) VALUES (?, ?, ?, ?)"; 
        $statement = $conn->prepare($this->query);
        $statement->bind_param('sssi', $name, $position, $partylist, $voteCount);
        
        return $statement->execute() ? 'Successfully Inserted' : 'Not Successfully Inserted';
    }

    // ADMIN | CANDIDATE SEARCH
    public function search($name) {
        global $conn;
        $this->query = "SELECT * FROM {$this->databaseTable} WHERE Name LIKE '%$name%'";
        $retrieve = \mysqli_query($conn, $this->query);

        $rows = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $rows[] = $row;
            }
        } 

        return $rows;
    }

    // ADMIN | CANDIDATE STATUS
    public function candidateList() {
        global $conn;

        $this->query = "SELECT * FROM {$this->databaseTable}"; 
        $retrieve = \mysqli_query($conn, $this->query);

        $rows = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $rows[] = $row;
            }
        } 

        return $rows;
    }

    // ADMIN | RESULT
    public function candidateVoteResult() {
        global $conn;

        $this->query = "SELECT * FROM {$this->databaseTable} c JOIN (SELECT Position, MAX(VoteCount) AS MaxVotes FROM {$this->databaseTable} GROUP BY Position) m ON c.Position = m.Position AND c.VoteCount = m.MaxVotes";
        $retrieve = \mysqli_query($conn, $this->query);

        $rows = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $rows[] = $row;
            }
        } 

        return $rows;
    }

    // USER | STANDARD LIST
    public function updateCandidatesVote($id, $voteCount) {
        global $conn;

        $this->query = "UPDATE candidates SET VoteCount = ? WHERE ID = ?";
        $statement = $conn->prepare($this->query); 
        
        $newVoteCount = $voteCount + 1;
        $statement->bind_param('ii', $newVoteCount, $id);

        

        if ($statement->execute()) {
            echo 'Vote count updated successfully.';
        } else {
            echo 'Error siya';
        }
    }
}

?>