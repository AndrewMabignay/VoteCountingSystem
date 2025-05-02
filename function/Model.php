<?php 

require '../config/config.php';

class Model {
    private $databaseTable = '';
    private $query = '';

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

    // 1. LOGIN AUTHENTICATION 
    public function authentication($username, $password) {
        global $conn;

        if (empty($username) || empty($password)) {
            return 'Please enter both username and password.';
        }

        $this->query = "SELECT * FROM {$this->databaseTable} WHERE Username = ?";
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
                $_SESSION['username'] = $users['Username'];
                $_SESSION['password'] = $users['Password'];
                $_SESSION['role'] = $users['Role'];

                switch ($_SESSION['role']) {
                    case 'Admin':
                        header('Location: ../admin/adminPanel.php?page=dashboard');
                        exit;
                    case 'User':
                        header('Location: ../client/standard.php');
                        exit;
                }
            } else {
                return 'Invalid Password.';
            }
        } else {
            return 'Invalid Username and Password.';
        }

        // echo 'Testing pa';
    }    

    public function logout() {
        session_start();
        session_unset();

        header('Location: ../auth/login.php');
        exit;
    }

    // 2. ===================== ADMIN CONTAINER =====================
    // ADD NEW CHANGES 
    // ADMIN | ADMIN PANEL 
    public function insertCandidate($name, $position, $partylist, $voteCount) {
        global $conn;

        if (empty($name) || empty($position) || empty($partylist)) {
            return 'Fill up all fields!';
        }

        // CHECK FOR DUPLICATE NAME + POSITION + PARTYLIST.
        $this->query = "SELECT * FROM {$this->databaseTable} WHERE Name = ? AND Position = ? AND PartyList = ?";
        $statement = $conn->prepare($this->query); 
        $statement->bind_param('sss', $name, $position, $partylist);   

        $statement->execute();
        $search = $statement->get_result();

        if ($search->num_rows > 0) {
            return 'Duplicate candidate in partylist is invalid.';
        }

        $this->query = "INSERT INTO {$this->databaseTable} (Name, Position, PartyList, VoteCount) VALUES (?, ?, ?, ?)"; 
        $statement = $conn->prepare($this->query);
        $statement->bind_param('sssi', $name, $position, $partylist, $voteCount);
        
        return $statement->execute() ? 'Successfully Inserted' : 'Not Successfully Inserted';
    }
    // END NEW CHANGES

    // ADMIN | CANDIDATE SEARCH
    public function search($input) {
        global $conn;
        $this->query = "SELECT * FROM {$this->databaseTable} WHERE Name LIKE '%$input%' OR Position LIKE '%$input%' OR PartyList LIKE '%$input%'";
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

    // ADMIN | CANDIDATE RESULT
    // public function candidateVoteResult() {
    //     global $conn;

    //     $this->query = "SELECT * FROM {$this->databaseTable} c 
    //     JOIN (
    //         SELECT Position, MAX(VoteCount) AS MaxVotes 
    //         FROM {$this->databaseTable} 
    //         GROUP BY Position
    //     ) m 
    //     ON c.Position = m.Position AND c.VoteCount = m.MaxVotes";
    //     $retrieve = \mysqli_query($conn, $this->query);

    //     $grouped = [];

    //     if ($retrieve && mysqli_num_rows($retrieve) > 0) {
    //         while ($row = mysqli_fetch_assoc($retrieve)) {
    //             $position = $row['Position'];
    //             $grouped[$position][] = $row;
    //         }
    //     } 

    //     return $grouped;
    // }

    public function candidateVoteResult() {
        global $conn;

        $this->query = "
            SELECT *
            FROM (
                SELECT
                    c.*,
                    RANK() OVER (PARTITION BY c.Position ORDER BY c.VoteCount DESC) AS Ranking
                FROM candidates c
            ) AS ranked
            WHERE
                CASE
                    WHEN ranked.Position = 'Senator' THEN ranked.Ranking <= 12
                    WHEN ranked.Position = 'Counselor' THEN ranked.Ranking <= 10
                    WHEN ranked.Position = 'President' THEN ranked.Ranking = 1
                    ELSE ranked.Ranking = 1
                END
            ORDER BY
                CASE ranked.Position
                    WHEN 'President' THEN 1
                    WHEN 'Senator' THEN 2
                    WHEN 'Counselor' THEN 3
                    ELSE 999
                END,
                ranked.Ranking;        
        ";

        $retrieve = mysqli_query($conn, $this->query);
        $grouped = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $position = $row['Position'];
                $grouped[$position][] = $row;
            }
        }

        return $grouped;
    }

    public function candidateListTwo() {
        global $conn;

        $this->query = "SELECT * FROM {$this->databaseTable} ORDER BY Position";

        $retrieve = mysqli_query($conn, $this->query);
        $grouped = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $position = $row['Position'];
                $grouped[$position][] = $row;
            }
        }

        return $grouped;
    }


    public function candidateVoteResultSearch($input) {
        global $conn;

        $this->query = "
            SELECT *
            FROM (
                SELECT
                    c.*,
                    RANK() OVER (PARTITION BY c.Position ORDER BY c.VoteCount DESC) AS Ranking
                FROM candidates c
            ) AS ranked
            WHERE
                CASE
                    WHEN ranked.Position = 'Senator' THEN ranked.Ranking <= 12
                    WHEN ranked.Position = 'Counselor' THEN ranked.Ranking <= 10
                    WHEN ranked.Position = 'President' THEN ranked.Ranking = 1
                    ELSE ranked.Ranking = 1
                END AND ranked.Name LIKE '%{$input}%'
            ORDER BY
                CASE ranked.Position
                    WHEN 'President' THEN 1
                    WHEN 'Senator' THEN 2
                    WHEN 'Counselor' THEN 3
                    ELSE 999
                END,
                ranked.Ranking;        
        ";

        $retrieve = mysqli_query($conn, $this->query);
        $grouped = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $position = $row['Position'];
                $grouped[$position][] = $row;
            }
        }

        return $grouped;
    }

    // ADD NEW CHANGES
    // ADMIN | CANDIDATE UPDATE 
    public function candidateEdit($id, $name, $position, $partylist) {
        // CONDITION BEFORE UPDATE

        // START UPDATE
        global $conn;

        // CHECK FOR DUPLICATE NAME + POSITION + PARTYLIST.
        $this->query = "SELECT * FROM {$this->databaseTable} WHERE Name = ? AND Position = ? AND PartyList = ? AND ID != $id";
        $statement = $conn->prepare($this->query); 
        $statement->bind_param('sss', $name, $position, $partylist);   

        $statement->execute();
        $search = $statement->get_result();

        if ($search->num_rows > 0) {
            return 'Invalid Candidate Update.';
        }

        $this->query = "UPDATE candidates SET Name = ?, Position = ?, PartyList = ? WHERE ID = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('sssi', $name, $position, $partylist, $id);

        if ($statement->execute()) {
            return $statement->affected_rows > 0 ? 'Successfully Updated!' : 'No changes made.';
        } else {
            return 'Error siya';
        }
    }

    // ADMIN | CANDIDATE ID
    public function candidateID($id) {
        global $conn;

        $this->query = "SELECT * FROM {$this->databaseTable} WHERE ID = '$id'";
        $retrieve = \mysqli_query($conn, $this->query);

        $rows = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $rows[] = $row;
            }
        } 

        return $rows;
    }
    // END NEW CHANGES

    // ADMIN | CANDIDATE DELETE
    public function candidateDelete($id) {
        global $conn;

        $this->query = "DELETE FROM candidates WHERE ID = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('i', $id);
        $statement->execute();
    }

    // ADD NEW CHANGES
    // ADMIN | USER MANAGEMENT
    public function addUser($name, $age, $address, $username, $password, $role) {        
        global $conn;
        
        // Check if it is empty.
        if (empty($name) || empty($age) || empty($address) || empty($username) || empty($password) || empty($role)) {
            return 'Fill up all fields!';
        }

        if ($age < 18) {
            return 'Invalid age.';
        }

        // Check for duplicate names.
        $this->query = "SELECT * FROM accounts WHERE Username = ?";
        $statement = $conn->prepare($this->query); 
        $statement->bind_param('s', $username);   

        $statement->execute();
        $search = $statement->get_result();

        if ($search->num_rows > 0) {
            return 'Duplicate username invalid';
        }

        $this->query = "INSERT INTO accounts(Name, Age, Address, Username, Password, Role) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('sissss', $name, $age, $address, $username, $password, $role);
        
        return $statement->execute() ? 'Successfully Added' : 'Not Successfully Added';
    }

    public function showUser($id) {
        global $conn;

        $this->query = "SELECT * FROM accounts WHERE ID <> '$id' AND Username <> 'admin'"; 
        $retrieve = \mysqli_query($conn, $this->query);

        $rows = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $rows[] = $row;
            }
        } 

        return $rows;   
    }

    public function searchUser($input) {
        global $conn;

        $this->query = "SELECT * FROM accounts WHERE Name LIKE '%{$input}%' AND Username <> 'admin'"; 
        $retrieve = \mysqli_query($conn, $this->query);

        $rows = [];

        if ($retrieve && mysqli_num_rows($retrieve) > 0) {
            while ($row = mysqli_fetch_assoc($retrieve)) {
                $rows[] = $row;
            }
        } 

        return $rows;
    }

    public function deleteUser($id) {
        global $conn;

        $this->query = "DELETE FROM votes WHERE user_id = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('i', $id);
        $statement->execute();

        $this->query = "DELETE FROM accounts WHERE ID = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('i', $id);
        $statement->execute();

        
    }

    public function editUser($id, $name, $age, $address, $username, $password, $role) {
        global $conn;
        
        // Check if it is empty.
        if (empty($name) || empty($age) || empty($address) || empty($username) || empty($password) || empty($role)) {
            return 'Fill up all fields!';
        }

        // Check Condition.
        if ($age < 18) {
            return 'Invalid age.';
        }

        $this->query = "SELECT * FROM accounts WHERE Username = ? AND ID != ?";
        $statement = $conn->prepare($this->query); 
        $statement->bind_param('si', $username, $id);   

        $statement->execute();
        $search = $statement->get_result();

        if ($search->num_rows > 0) {
            return 'Duplicate username invalid';
        }

        $this->query = "UPDATE accounts SET Name = ?, Age = ?, Address = ?, Username = ?, Password = ?, Role = ? WHERE ID = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('sissssi', $name, $age, $address, $username, $password, $role, $id);

        if ($statement->execute()) {
            if ($statement->affected_rows > 0) {
                return 'Successfully Updated!';
            } else {
                return 'No data was changed. Maybe the values are the same.';
            }
        } else {
            return 'Error siya';
        }       
    }
    // END NEW CHANGES


    // USER | STANDARD LIST
    public function updateCandidatesVote($id, $voteCount) {
        global $conn;

        $this->query = "UPDATE candidates SET VoteCount = ? WHERE ID = ?";
        $statement = $conn->prepare($this->query); 
        
        $newVoteCount = $voteCount + 1;
        $statement->bind_param('ii', $newVoteCount, $id);

        if ($statement->execute()) {
            return 'Vote count updated successfully.';
        } else {
            return 'Error siya';
        }
    }

    // public function getUserVotesGroupedByPosition($userId) {
    //     $stmt = $this->conn->prepare("SELECT position, COUNT(*) as vote_count FROM votes WHERE user_id = ? GROUP BY position");
    //     $stmt->execute([$userId]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }  
    
    public function castVote($accountID, $candidateID, $position, $voteCount) {
        global $conn;

        $positionLimits = [
            'President' => 1,
            'Senator' => 12,
            'Counselor' => 10
        ];
    
        // STEP 1 : 
        $this->query = "SELECT COUNT(*) AS vote_count FROM votes WHERE user_id = ? AND position = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('is', $accountID, $position);
        $statement->execute();

        $result = $statement->get_result();
        $row = $result->fetch_array();
        $userVotes = $row['vote_count'] ?? 0;

        // echo $userVotes;


        // STEP 2 : 
        $limit = $positionLimits[$position] ?? 1;
    
        if ($userVotes < $limit) {
            // Step 3: Insert vote
            $this->query = "INSERT INTO votes (user_id, candidate_id, position) VALUES (?, ?, ?)";
            $statement = $conn->prepare($this->query);
            $statement->bind_param('iis', $accountID, $candidateID, $position);
            $statement->execute();
            $statement->close();
    
            // Step 4: Update vote count in candidates table
            $this->query = "UPDATE candidates SET VoteCount = ? WHERE ID = ?";
            $statement = $conn->prepare($this->query);
            $voteCountNew = $voteCount + 1;
            $statement->bind_param('ii', $voteCountNew, $candidateID);
            $statement->execute();
            $statement->close();
    
            return true;
        }
    
        return false;
    }

    public function getUserVotedCandidateIDs($userId) {
        global $conn;
    
        $this->query = "SELECT candidate_id FROM votes WHERE user_id = ?";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('i', $userId);
        $statement->execute();
        $result = $statement->get_result();
    
        $votedIDs = [];
        while ($row = $result->fetch_assoc()) {
            $votedIDs[] = $row['candidate_id'];
        }
    
        return $votedIDs;
    }
    
    
    public function getUserVotesPerPosition($userId) {
        global $conn;
    
        $this->query = "SELECT position, COUNT(*) as count FROM votes WHERE user_id = ? GROUP BY position";
        $statement = $conn->prepare($this->query);
        $statement->bind_param('i', $userId);
        $statement->execute();
        $result = $statement->get_result();
    
        $votes = [];
        while ($row = $result->fetch_assoc()) {
            $votes[$row['position']] = $row['count'];
        }
    
        return $votes;
    }
    
}
?>